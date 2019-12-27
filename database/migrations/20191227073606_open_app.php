<?php

use think\migration\Migrator;
use think\migration\db\Column;

class OpenApp extends Migrator
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
        $table = $this->table('open_app', array('engine' => 'InnoDB', 'comment' => '开放商户 表'));

        $table
            ->addColumn('appid', 'string', ['default' => '', 'comment' => '应用标识', 'null' => false])
            ->addColumn('secret', 'string', ['default' => '', 'comment' => '应用密钥', 'null' => false])
            ->addColumn('name', 'string', ['default' => '', 'comment' => '应用名称', 'null' => false])

            ->addColumn('status', 'integer', ['limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY, 'default' => 1, 'comment' => '状态 1、开启 0、禁用', 'null' => false])

            ->addColumn('create_time', 'integer', ['default' => 0, 'comment' => '创建时间', 'null' => false])
            ->addColumn('update_time', 'integer', ['default' => 0, 'comment' => '更新时间', 'null' => false])
            ->addColumn('delete_time', 'integer', ['comment' => '软删', 'null' => true])
            ->addIndex('appid')
            ->create();
    }
}
