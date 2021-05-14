<?php

declare(strict_types=1);

namespace App\Storage\Client;

use App\Storage\ObjectStorageInterface;
use App\Storage\StorageObject;
use Google\Cloud\Storage\StorageClient;
use InvalidArgumentException;
use RuntimeException;

/**
 * GCP Cloud storage implementation of the ObjectStorageInterface.
 */
class CloudStorage implements ObjectStorageInterface
{
    /**
     * @var StorageClient
     */
    protected $_storageClient;

    /**
     * @var \Google\Cloud\Storage\Bucket
     */
    protected $_bucket;

    /**
     * Constructor.
     *
     * @param string|null $bucketName Where to store things.
     * @param string|null $credentials Base64 encoded JSON credentials for a GCP service account.
     */
    public function __construct(string $bucketName = null, string $credentials = null)
    {
        $bucketName = $bucketName ?: env('GCS_BUCKET');
        $credentials = $credentials ?: env('GCS_CREDENTIALS');

        $config = [];
        if ($credentials) {
            $credentials = json_decode(base64_decode($credentials), true);
            $config['keyFile'] = $credentials;
        }

        $client = new StorageClient($config);
        $bucket = $client->bucket($bucketName);

        $this->_storageClient = $client;
        $this->_bucket = $bucket;
    }

    /**
     * Set the Google Cloud StorageClient instance to use.
     *
     * @param  StorageClient  $client
     *
     * @return static
     */
    public function setStorageClient(StorageClient $client): CloudStorage
    {
        $this->_storageClient = $client;

        return $this;
    }

    /**
     * Change the storage bucket to store stuff in.
     *
     * @param  string  $bucketName
     *
     * @return CloudStorage
     */
    public function setBucketName(string $bucketName): CloudStorage
    {
        $this->_bucket = $this->_storageClient->bucket($bucketName);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function store(string $file, ?string $name = null, array $options = []): StorageObject
    {
        if (!file_exists($file)) {
            throw new InvalidArgumentException(
                sprintf('Can\'t place missing file "%s" into storage.', $file)
            );
        }

        $file = fopen($file, 'r');
        if (!$file) {
            throw new RuntimeException('Could not open file for object storage upload.');
        }

        $object = new StorageObject($name);
        $this->_bucket->upload($file, ['name' => $object->path()]);

        return $object;
    }

    /**
     * {@inheritDoc}
     *
     * @return resource
     */
    public function retrieve(string $identifier)
    {
        $object = $this->_bucket->object($identifier);

        $temp = tmpfile();
        fwrite($temp, $object->downloadAsString());

        return $temp;
    }

    /**
     * {@inheritDoc}
     */
    public function delete(string $identifier): bool
    {
        return true;
    }
}
