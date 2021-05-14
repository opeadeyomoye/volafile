<?php
declare(strict_types=1);

namespace App\Domain\Core;

use App\DomainObject;

/**
 * @property string $packageId
 * @property string $name
 * @property string $path
 * @property int|null $id
 */
class File extends DomainObject
{
    protected string $packageId;
    protected string $name;
    protected string $path;
    protected ?int $id = null;
}
