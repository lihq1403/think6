<?php

use think\migration\Migrator;
use think\migration\db\Column;

class AdminUser extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $table = $this->table('admin_user', array('engine' => 'InnoDB', 'comment' => '管理员表'));

        $table
            ->addColumn('username', 'string', ['default' => '', 'comment' => '用户名', 'null' => false])
            ->addColumn('password', 'string', ['default' => '', 'comment' => '密码', 'null' => false])

            ->addColumn('last_login_time', 'integer', ['default' => 0, 'comment' => '最后登录时间', 'null' => false])
            ->addColumn('last_login_ip', 'integer', ['default' => 0, 'comment' => '最后登录ip', 'null' => false, 'signed' => false])

            ->addColumn('status', 'integer', ['limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY, 'default' => 1, 'comment' => '状态 1、开启 2、禁用', 'null' => false])

            ->addColumn('create_time', 'integer', ['default' => 0, 'comment' => '创建时间', 'null' => false])
            ->addColumn('update_time', 'integer', ['default' => 0, 'comment' => '更新时间', 'null' => false])
            ->addColumn('delete_time', 'integer', ['comment' => '软删', 'null' => true])
            ->addIndex('username')
            ->create();
    }
}
