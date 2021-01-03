<?php
declare(strict_types=1);

namespace App\Controller;

use App\Domain\Core\Repository\PackageRepositoryInterface;
use App\Form\UploadPackageForm;
use App\Service\Packages;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;
use Cake\Routing\Router;

/**
 * Controller for all our packaging needs.
 */
class PackagesController extends AppController
{
    protected const LAST_PACKAGE_SESSION_KEY = 'Session.lastUploadedPackage';

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
            // there's a problem
        }

        $this->set('packageLink', Router::url([
            'action' => 'download',
            $package->id(),
            '?' => ['ac' => urlencode($package->accessCode())]
        ]));

        return null;
    }

    public function download(
        string $id,
        PackageRepositoryInterface $repository
    ): ?Response {
        if ($this->request->is('post')) {
            return $this->response;
        }

        /** @todo clean package id before use */
        $package = $repository->get($id);
        $accessCode = urldecode($this->request->getQuery('ac'));

        if (!$package || ($accessCode !== $package->accessCode())) {
            throw new NotFoundException('Sorry, we could not find that package.');
        }

        $packageIsSealed = $package->isSealed();

        $this->set([
            'package' => $package,
            'requiresKey' => $packageIsSealed,
            'files' => $packageIsSealed ? [] : $package->peek(),
        ]);

        return null;
    }

    /**
     * Initiates an actual file download if the conditions are right.
     *
     * @param string $packageId
     * @param string $fileId
     * @param PackageRepositoryInterface $repository
     *
     * @return Response|null
     */
    public function downloadFile(
        string $packageId,
        string $fileId,
        PackageRepositoryInterface $repository
    ): ?Response {

        return null;
    }

}
