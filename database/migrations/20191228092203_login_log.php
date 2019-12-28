<?php

use think\migration\Migrator;
use think\migration\db\Column;

class LoginLog extends Migrator
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
        $table = $this->table('login_log', array('engine' => 'InnoDB', 'comment' => '登录日志 表'));

        $table
            ->addColumn('type', 'string', ['default'=>'', 'comment'=>'登录类型'])
            ->addColumn('uid', 'integer', ['signed' => true, 'comment' => '关联用户id'])
            ->addColumn('ip', 'integer', ['default' => 0, 'comment' => '请求ip', 'null' => false, 'signed' => false])
            ->addColumn('ip_show', 'string', ['default' => '', 'comment' => '请求ip', 'null' => false])
            ->addColumn('agent', 'text', ['comment'=>'请求agent'])
            ->addColumn('create_time', 'integer', ['default' => 0, 'comment' => '创建时间', 'null' => false])
            ->addColumn('update_time', 'integer', ['default' => 0, 'comment' => '更新时间', 'null' => false])
            ->addColumn('delete_time', 'integer', ['comment' => '软删', 'null' => true])
            ->save();
    }
}
