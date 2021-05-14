<?php
declare(strict_types=1);

namespace App\Storage;

use Cake\Utility\Text;

/**
 * A file persisted / to-be-persisted in object storage somewhere.
 */
class StorageObject
{
    protected string $name;
    protected string $path;

    public function __construct(string $name, ?string $path = null)
    {
        $this->name = $this->cleanName($name);

        if (!$path) {
            $path = $this->generatePath();
        }
        $this->path = $path;
    }

    /**
     * Identifier for this object in storage.
     *
     * @return string
     */
    public function path(): string
    {
        return $this->path;
    }

    /**
     * The filename for this object. Clean.
     *
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    protected function cleanName(string $name): string
    {
        return Text::slug($name, ['preserve' => '.']);
    }

    /**
     * Returns a unique name/path that'll be used to identify this object in storage.
     *
     * @return string
     */
    protected function generatePath(): string
    {
        return date('Y/m/d/') . uniqid('', true);
    }
}
