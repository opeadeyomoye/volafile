<?php
declare(strict_types=1);

namespace App\Domain\Core;

class File
{
    protected string $packageId;
    protected string $name;
    protected string $path;

    public function __construct(string $packageId, string $name, string $path)
    {
        $this->packageId = $packageId;
        $this->name = $name;
        $this->path = $path;
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
}
