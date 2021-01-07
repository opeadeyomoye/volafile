<?php
declare(strict_types=1);

namespace App\Storage;

interface ObjectStorageInterface
{
    /**
     * Place an object in storage.
     *
     * @param string $file Path to file on local fs.
     * @param string $name What the file should be called.
     * @param array $options Storage-client-specific configuration.
     *
     * @return Object
     */
    public function store(string $file, string $name, array $options = []): StorageObject;

    public function retrieve(string $identifier);

    public function delete(string $identifier): bool;
}
