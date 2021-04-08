<?php
declare(strict_types=1);

namespace App\Domain\Core;

use App\DomainObject;
use InvalidArgumentException;

/**
 * A package contains all the files that a sender's trying to get across
 * to receivers, along with instructions on how the receivers can access
 * said files, and how long the files should be available for.
 *
 * @property string|null $id
 * @property string|null $key
 * @property string|null $accessCode
 * @property File[] $items
 */
class Package extends DomainObject
{
    protected const KEY_HASH_ALGO = PASSWORD_DEFAULT;

    protected ?string $id = null;
    protected ?string $key = null;
    protected ?string $accessCode = null;
    protected bool $expired = false;

    /**
     * @var \App\Domain\Core\File[]
     */
    protected array $items = [];

    /**
     * {@inheritDoc}
     */
    public function initialize(array $parameters = []): void
    {
        $this->id = $this->id ?? bin2hex(random_bytes(8));
        $this->accessCode = $this->accessCode ?? base64_encode(random_bytes(16));
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
     * @return bool
     */
    public function isExpired(): bool
    {
        return $this->expired;
    }

    /**
     * Take a peek at the items in this package.
     *
     * @param string|null $key Required if the package is sealed.
     * @return File[]
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
}
