<?php
declare(strict_types=1);

namespace App\Domain\Core;

class File
{
    protected string $packageId;
    protected string $name;
    protected string $path;
    protected ?int $id;

    public function __construct(string $packageId, string $name, string $path, ?int $id = null)
    {
        $this->packageId = $packageId;
        $this->name = $name;
        $this->path = $path;
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function path(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function packageId(): string
    {
        return $this->packageId;
    }

    /**
     * @return int|null
     */
    public function id(): ?int
    {
        return $this->id;
    }
}
