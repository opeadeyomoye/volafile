<?php
declare(strict_types=1);

namespace App\Storage\Client;

use App\Storage\ObjectStorageInterface;
use App\Storage\StorageObject;
use Aws\S3\S3Client;
use InvalidArgumentException;

/**
 * Object storage client implementation for DigitalOcean's Spaces.
 */
class Spaces implements ObjectStorageInterface
{
    protected S3Client $s3Client;
    protected string $spaceName;

    public function __construct(?S3Client $s3Client = null, ?string $spaceName = null)
    {
        $this->s3Client = !is_null($s3Client)
            ? $s3Client
            : new S3Client([
                'version' => 'latest',
                'region'  => 'us-east-1',
                'endpoint' => env('SPACES_ENDPOINT'),
                'credentials' => [
                    'key'    => env('SPACES_KEY'),
                    'secret' => env('SPACES_SECRET'),
                ],
            ]);

        $this->spaceName = !empty($spaceName) ? $spaceName : env('SPACE_NAME');
    }

    /**
     * {@inheritDoc}
     */
    public function store(string $file, string $name, array $options = []): StorageObject
    {
        if (!file_exists($file)) {
            throw new InvalidArgumentException(
                "Can't upload unknown file \"{$file}\" to DigitalOcean Spaces."
            );
        }

        $object = new StorageObject($name);

        $this->s3Client->putObject([
            'Bucket' => $this->spaceName,
            'Key' => $object->path(),
            'Body' => file_get_contents($file),
            'ACL' => 'private',
        ]);

        return $object;
    }

    /**
     * {@inheritDoc}
     *
     * @return resource
     */
    public function retrieve(string $identifier)
    {
        $result = $this->s3Client->getObject([
            'Bucket' => $this->spaceName,
            'Key' => $identifier,
        ]);

        $temp = tmpfile();
        fwrite($temp, (string)$result['Body']);

        return $temp;
    }

    /**
     * {@inheritDoc}
     */
    public function delete(string $identifier): bool
    {
        $this->s3Client->deleteObject([
            'Bucket' => $this->spaceName,
            'Key' => $identifier
        ]);

        return true;
    }
}
