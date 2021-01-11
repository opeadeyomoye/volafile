<?php
declare(strict_types=1);

namespace App\Controller;

use App\Domain\Core\Repository\PackageRepositoryInterface;
use App\Form\UploadPackageForm;
use App\Service\Packages;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;
use Throwable;

/**
 * Controller for all our packaging needs.
 *
 * @property Component\PackageAuthComponent $PackageAuth
 */
class PackagesController extends AppController
{
    protected const LAST_PACKAGE_SESSION_KEY = 'Packages.lastUploadedPackage';

    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('PackageAuth');
    }

    public function upload(Packages $packages): ?Response
    {
        $form = new UploadPackageForm();

        if ($this->request->is('post')) {
            if (!$form->execute($this->request->getData())) {
                $this->Flash->error(
                    __('There were some errors processing your upload. Please see them below.')
                );

                $this->set('form', $form);

                return null;
            }

            $package = $packages->makePackagefromUploadedFiles([$form->getData('file')]);

            if (!empty($key = $form->getData('key'))) {
                $package->seal($key);
            }
            $packages->load($package);

            $this->request->getSession()->write(self::LAST_PACKAGE_SESSION_KEY, $package->id());

            return $this->redirect(['action' => 'uploadResult']);
            // store package
            // announce package creation
        }

        $this->set('form', $form);

        return null;
    }

    public function uploadResult(PackageRepositoryInterface $repository): ?Response
    {
        $uploadedPackageId = $this->request->getSession()->consume(self::LAST_PACKAGE_SESSION_KEY);

        if (empty($uploadedPackageId)) {
            return $this->redirect(['action' => 'upload']);
        }

        $package = $repository->get($uploadedPackageId);
        if (!$package) {
            throw new InternalErrorException();
        }

        $this->set(compact('package'));

        return null;
    }

    public function download(
        string $id,
        PackageRepositoryInterface $repository,
        Packages $packages
    ): ?Response {
        $package = $repository->get($id);
        $accessCode = urldecode($this->request->getQuery('ac') ?? '');

        if (!$package || ($accessCode !== $package->accessCode())) {
            throw new NotFoundException('Sorry, we could not find that package.');
        }

        if ($package->isExpired()) {
            $packages->offload($package);
            $this->set('expired', true);

            return null;
        }

        $files = [];
        $requiresKey = $package->isSealed();

        if (!$requiresKey) {
            $this->PackageAuth->allowDownloadsFor($package->id());
            $files = $package->peek();
        }

        if ($this->request->is('post')) {
            // this package needs to be unsealed with a password,
            // and the visitor's attempting to unseal it
            $key = $this->request->getData('key');

            try {
                $files = $package->peek($key);
                $this->PackageAuth->allowDownloadsFor($package->id());
            } catch (Throwable $e) {
                $this->Flash->error(__('That password is incorrect.'));
            }
        }

        $this->set(compact('package', 'requiresKey', 'files'));

        return null;
    }

    /**
     * Initiates an actual file download if the conditions are right.
     *
     * @param string $packageId
     * @param string $fileId
     * @param Packages $packages
     *
     * @return Response|null
     */
    public function downloadFile(string $packageId, string $fileId, Packages $packages): ?Response
    {
        if (!$this->PackageAuth->downloadsAreAllowedFor($packageId)) {
            throw new BadRequestException('Invalid request.');
        }

        // 👀👀👀...
        $file = $this->getTableLocator()->get('PackageItems')
            ->find()
            ->where(['id' => (int)$fileId])
            ->first();

        if (!$file || $file->package_id !== $packageId) {
            throw new BadRequestException('Invalid request.');
        }

        $resource = $packages->retrieveFile($file->get('path'));
        $localPath = stream_get_meta_data($resource)['uri'];

        // deliver the file
        return $this->response
            ->withHeader('Content-Description', 'File Transfer')
            ->withType(mime_content_type($localPath))
            ->withFile($localPath, [
                'name' => $file->get('name'),
                'download' => true,
            ]);
    }
}
