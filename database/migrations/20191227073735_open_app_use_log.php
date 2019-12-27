<?php

use think\migration\Migrator;
use think\migration\db\Column;

class OpenAppUseLog extends Migrator
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
        $table = $this->table('open_app_use_log', array('engine' => 'InnoDB', 'comment' => '开放商户请求日志 表'));

        $table->addColumn('open_app_id', 'integer', ['signed' => true, 'comment' => '关联商户id'])
            ->addColumn('method', 'string', ['default'=>'', 'comment'=>'请求方式'])
            ->addColumn('path', 'string', ['default'=>'', 'comment'=>'请求路径'])
            ->addColumn('ip', 'integer', ['default' => 0, 'comment' => '请求ip', 'null' => false, 'signed' => false])
            ->addColumn('input', 'text', ['comment'=>'请求参数'])
            ->addColumn('output', 'text', ['comment'=>'返回参数'])
            ->addColumn('create_time', 'integer', ['default' => 0, 'comment' => '创建时间', 'null' => false])
            ->addColumn('update_time', 'integer', ['default' => 0, 'comment' => '更新时间', 'null' => false])
            ->addColumn('delete_time', 'integer', ['comment' => '软删', 'null' => true])
            ->addIndex(['open_app_id'])
            ->save();
    }
}
