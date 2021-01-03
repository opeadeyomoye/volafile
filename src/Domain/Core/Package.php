<?php
declare(strict_types=1);

namespace App\Domain\Core;

use DateTime;
use InvalidArgumentException;

/**
 * A package contains all the files that a sender's trying to get across
 * to receivers, along with instructions on how the receivers can access
 * said files, and how long the files should be available for.
 */
class Package
{
    protected const KEY_HASH_ALGO = PASSWORD_DEFAULT;

    protected ?string $id = null;
    protected ?string $key = null;
    protected ?string $accessCode = null;
    protected object $creationTime;
    protected DateTime $expirationTime;

    /**
     * @var File[]
     */
    protected array $items = [];

    public function __construct(
        ?string $id = null,
        ?string $accessCode = null,
        ?string $key = null,
        ?object $expirationTime = null,
        ?object $creationTime = null
    ) {
        $this->id = empty($id)
            ? bin2hex(random_bytes(8))
            : $id;

        $this->accessCode = empty($accessCode)
            ? base64_encode(random_bytes(16))
            : $accessCode;

        $this->expirationTime = empty($expirationTime)
            ? new DateTime()
            : $expirationTime;

        $this->creationTime = empty($creationTime)
            ? new DateTime()
            : $creationTime;

        if ($key) {
            $this->key = $key;
        }
    }

    public function seal(string $key): self
    {
        if (empty($key)) {
            throw new InvalidArgumentException('A package can not be sealed with an empty key.');
        }

        $this->key = password_hash($key, self::KEY_HASH_ALGO);

        return $this;
    }

    /**
     * Add an item to this package.
     *
     * @param File $item
     * @return Package
     */
    public function addItem(File $item): self
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * @return bool
     */
    public function isSealed(): bool
    {
        return (bool)$this->key;
    }

    /**
     * Take a peek at the items in this package.
     *
     * @param string|null $key Required if the package is sealed.
     * @return Files[]
     *
     * @throws InvalidArgumentException For invalid keys.
     */
    public function peek(?string $key = null): array
    {
        if ($this->isSealed()) {
            if (!password_verify((string)$key, $this->key)) {
                throw new InvalidArgumentException('Invalid package key.');
            }
        }

        return $this->items;
    }

    /**
     * @return string
     */
    public function id(): ?string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function accessCode(): ?string
    {
        return $this->accessCode;
    }

    /**
     * @return string
     */
    public function key(): ?string
    {
        return $this->key;
    }

    /**
     * @return File[]
     */
    public function items(): array
    {
        return $this->items;
    }
}
