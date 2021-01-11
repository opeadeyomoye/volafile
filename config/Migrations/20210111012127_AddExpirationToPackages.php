<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddExpirationToPackages extends AbstractMigration
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
        $this->table('packages')
            ->removeColumn('expires_after')
            ->removeColumn('expiration_unit')
            ->addColumn('expires', 'datetime', ['null' => false, 'default' => null, 'after' => 'password'])
            ->addColumn('offloaded', 'datetime', ['null' => true, 'default' => null])
            ->update();
    }
}
