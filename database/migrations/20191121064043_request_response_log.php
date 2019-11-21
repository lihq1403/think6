<?php

use think\migration\Migrator;
use think\migration\db\Column;

class RequestResponseLog extends Migrator
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
        $table = $this->table('request_response_log', array('engine' => 'InnoDB', 'comment' => '接口请求返回日志记录表'));

        $table
            ->addColumn('uuid', 'string', ['default' => '', 'comment' => 'log唯一标识', 'null' => false])
            ->addColumn('request_data', 'text', ['comment' => '请求', 'limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_LONG])
            ->addColumn('response_data', 'text', ['comment' => '返回', 'limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_LONG])
            ->addColumn('response_code', 'integer', ['default' => 0, 'comment' => '返回code', 'null' => false])
            ->addColumn('ip', 'integer', ['default' => 0, 'comment' => '访问ip', 'null' => false, 'signed' => false])
            ->addColumn('status', 'integer', ['limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY, 'default' => 1, 'comment' => '预留字段', 'null' => false])
            ->addColumn('create_time', 'integer', ['default' => 0, 'comment' => '创建时间', 'null' => false])
            ->addColumn('update_time', 'integer', ['default' => 0, 'comment' => '更新时间', 'null' => false])
            ->addColumn('delete_time', 'integer', ['comment' => '软删', 'null' => true])
            ->create();
    }
}
