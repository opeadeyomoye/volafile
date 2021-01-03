<?php
declare(strict_types=1);

namespace App\Domain\Core\Repository;

use App\Domain\Core\Package;

interface PackageRepositoryInterface
{
    public function save(Package $package): Package;

    public function get(string $packageId): ?Package;
}
