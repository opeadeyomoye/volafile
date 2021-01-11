<?php
declare(strict_types=1);

namespace App\Model\Repository;

use App\Domain\Core\File;
use App\Domain\Core\Package;
use App\Domain\Core\Repository\PackageRepositoryInterface;
use App\Model\Table\PackagesTable;
use Cake\Chronos\Chronos;
use Cake\ORM\Entity;

/**
 * Cake ORM implementation of the core domain's PackageRepositoryInterface.
 *
 * Not proud of what's going on here, but it was too late.
 *
 * I was in too deep.
 *
 * Might refactor if this application lives beyond the DO hackathon.
 */
class PackageRepository implements PackageRepositoryInterface
{
    protected PackagesTable $packagesTable;

    public function __construct(PackagesTable $table)
    {
        $this->packagesTable = $table;
    }

    public function save(Package $package): Package
    {
        // max downloads
        // expiration time
        // both

        $packageEntity = new Entity([
            'id' => $package->id(),
            'access_code' => $package->accessCode(),
            'password' => $package->key(),
            'expires' => (new Chronos())->addDay()->toDateTimeString(),
            'created' => (new Chronos())->toDateTimeString(),
        ]);

        $packageItemsEntities = [];
        $items = $package->items();

        foreach ($items as $item) {
            $packageItemsEntities[] = new Entity([
                'package_id' => $item->packageId(),
                'name' => $item->name(),
                'path' => $item->path(),
                'created' => new Chronos(),
            ]);
        }
        $packageEntity->set('package_items', $packageItemsEntities);

        $this->packagesTable->save($packageEntity);

        return $package;
    }

    public function get(string $packageId): ?Package
    {
        $entity = $this->packagesTable->find()
            ->where(['id' => $packageId])
            ->contain(['PackageItems'])
            ->first();

        if (!$entity) {
            return null;
        }

        $package = new Package(
            $entity->id,
            $entity->get('access_code'),
            $entity->get('password'),
            $entity->get('expires') ? $entity->get('expires')->isPast() : true
        );

        /** @var Entity[] */
        $entityItems = $entity->get('package_items');
        foreach ($entityItems as $entityItem) {
            $package->addItem(new File(
                $package->id(),
                $entityItem->get('name'),
                $entityItem->get('path'),
                $entityItem->id
            ));
        }

        return $package;
    }
}
