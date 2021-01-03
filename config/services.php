<?php
/**
 * @var \Cake\Core\Container $container
 * @var \App\Application $this
 */

use App\Domain\Core\Repository\PackageRepositoryInterface;
use App\Model\Repository\PackageRepository;
use App\Service\Packages;
use App\Storage\Client\Spaces as SpacesStorage;
use App\Storage\ObjectStorageInterface;
use Cake\ORM\TableRegistry;

$tableLocator = TableRegistry::getTableLocator();

$container->share(Packages::class)
    ->addArgument(PackageRepositoryInterface::class)
    ->addArgument(ObjectStorageInterface::class);

$container->share(PackageRepositoryInterface::class, PackageRepository::class)
    ->addArgument($tableLocator->get('Packages'));

$container->share(ObjectStorageInterface::class, SpacesStorage::class);
