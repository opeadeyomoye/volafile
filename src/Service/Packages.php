<?php
declare(strict_types=1);

namespace App\Service;

use App\Domain\Core\File;
use App\Domain\Core\Package;
use App\Domain\Core\Repository\PackageRepositoryInterface;
use App\Storage\ObjectStorageInterface;
use Cake\Chronos\Chronos;
use Cake\ORM\Locator\LocatorAwareTrait;
use Psr\Http\Message\UploadedFileInterface;
use RuntimeException;
use Throwable;

class Packages
{
    use LocatorAwareTrait;

    protected ObjectStorageInterface $storage;
    protected PackageRepositoryInterface $packageRepository;

    public function __construct(
        PackageRepositoryInterface $repository,
        ObjectStorageInterface $storage
    ) {
        $this->storage = $storage;
        $this->packageRepository = $repository;
    }

    /**
     * Undocumented function
     *
     * @param UploadedFileInterface[] $files
     * @return Package
     *
     * @throws RuntimeException If we fail to store any of the files.
     */
    public function makePackagefromUploadedFiles(array $files): Package
    {
        $package = new Package();

        foreach ($files as $file) {
            try {
                $object = $this->storage->store(
                    $file->getStream()->getMetadata('uri'),
                    $file->getClientFilename()
                );
            } catch (Throwable $e) {
                throw new RuntimeException('Error adding package file to storage.');
            }

            $package->addItem(new File([
                'packageId' => $package->id,
                'name' => $object->name(),
                'path' => $object->path()
            ]));
        }

        return $package;
    }

    /**
     * Load a package onto our courier service 👀.
     *
     * @param Package $package
     * @return Package
     */
    public function load(Package $package): Package
    {
        return $this->packageRepository->save($package);
    }

    public function offload(Package $package): bool
    {
        $table = $this->getTableLocator()->get('Packages');

        $entity = $table->get($package->id, ['contain' => 'PackageItems']);
        if ($entity->get('offloaded')) {
            return true;
        }

        foreach ($entity->get('package_items') as $item) {
            $this->storage->delete($item->path);
        }

        $entity->set('offloaded', new Chronos());
        $table->save($entity);

        return true;
    }

    public function retrieveFile(string $path)
    {
        return $this->storage->retrieve($path);
    }
}
