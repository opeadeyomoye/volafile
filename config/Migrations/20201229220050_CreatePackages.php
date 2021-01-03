<?php
declare(strict_types=1);

use Migrations\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreatePackages extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $this->table('packages', ['id' => false, 'primary_key' => 'id'])
            ->addColumn('id', 'string', ['length' => 16, 'null' => false, 'default' => null])
            ->addColumn('access_code', 'string', ['length' => 24, 'null' => false, 'default' => null])
            ->addColumn('password', 'string', ['length' => 60, 'null' => true, 'default' => null])
            ->addColumn('expires_after', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'null' => false, 'default' => 0])
            ->addColumn('expiration_unit', 'string', ['length' => 10, 'null' => false, 'default' => null])
            ->addColumn('created', 'datetime', ['null' => false, 'default' => null])
            ->create();

        $this->table('package_items')
            ->addColumn('package_id', 'string', ['length' => 16, 'null' => false, 'default' => null])
            ->addColumn('name', 'string', ['length' => 255, 'null' => false, 'default' => null])
            ->addColumn('path', 'string', ['length' => 255, 'null' => false, 'default' => null])
            ->addColumn('created', 'datetime', ['null' => false, 'default' => null])
            ->create();

        $this->table('package_downloads')
            ->addColumn('package_id', 'string', ['length' => 70, 'null' => false, 'default' => null])
            ->addColumn('attempted', 'datetime', ['null' => false, 'default' => null])
            ->create();
    }
}
