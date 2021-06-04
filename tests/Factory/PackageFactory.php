<?php
declare(strict_types=1);

namespace App\Test\Factory;

use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;

/**
 * PackageFactory
 */
class PackageFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'Packages';
    }

    /**
     * Defines the factory's default values. This is useful for
     * not nullable fields. You may use methods of the present factory here too.
     *
     * @return void
     */
    protected function setDefaultTemplate(): void
    {
        $this->setDefaultData(function (Generator $faker) {
            return [
                // set the model's default values
                // For example:
                // 'name' => $faker->lastName
            ];
        });
    }

    /**
     * @param array|callable|null|int $parameter
     * @param int $n
     * @return PackageFactory
     */
    public function withPackageDownloads($parameter = null, int $n = 1): PackageFactory
    {
        return $this->with(
            'PackageDownloads',
            \App\Test\Factory\PackageDownloadFactory::make($parameter, $n)
        );
    }

    /**
     * @param array|callable|null|int $parameter
     * @param int $n
     * @return PackageFactory
     */
    public function withPackageItems($parameter = null, int $n = 1): PackageFactory
    {
        return $this->with(
            'PackageItems',
            \App\Test\Factory\PackageItemFactory::make($parameter, $n)
        );
    }
}
