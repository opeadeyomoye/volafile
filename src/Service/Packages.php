<?php
declare(strict_types=1);

namespace App\Service;

use App\Domain\Core\File;
use App\Domain\Core\Package;
use App\Domain\Core\Repository\PackageRepositoryInterface;
use App\Storage\ObjectStorageInterface;
use Psr\Http\Message\UploadedFileInterface;
use RuntimeException;
use Throwable;

class Packages
{
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

            $package->addItem(new File($package->id(), $object->name(), $object->path()));
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

    public function retrieveFile(string $path)
    {
        return $this->storage->retrieve($path);
    }
}
