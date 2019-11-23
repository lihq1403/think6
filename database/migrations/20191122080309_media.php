<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Media extends Migrator
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
        $table = $this->table('media', array('engine' => 'InnoDB', 'comment' => '多媒体文件表'));
        $table
            ->addColumn('type', 'integer', ['limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY, 'default' => 0, 'comment' => '文件类型：待设置', 'null' => false])
            ->addColumn('title', 'string', ['default' => '', 'comment' => '文件名称', 'null' => false])
            ->addColumn('file_type', 'string', ['default' => '', 'comment' => '文件MIME类型', 'null' => false])
            ->addColumn('file_size', 'integer', ['default' => 0, 'comment' => '文件大小，字节', 'null' => false])
            ->addColumn('file_url', 'string', ['limit' => 2048, 'default' => '', 'comment' => '文件访问url', 'null' => false])
            ->addColumn('file_path', 'string', ['limit' => 2048, 'default' => '', 'comment' => '文件路径', 'null' => false])
            ->addColumn('save_way', 'string', ['default' => 'local', 'comment' => '文件保存方式，local本地', 'null' => false])
            ->addColumn('md5', 'string', ['limit' => 32, 'default' => '', 'comment' => '文件md5', 'null' => false])
            ->addColumn('sha1', 'string', ['limit' => 40, 'default' => '', 'comment' => '文件sha1', 'null' => false])
            ->addColumn('status', 'integer', ['limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY, 'default' => 1, 'comment' => '预留字段', 'null' => false])
            ->addColumn('create_time', 'integer', ['default' => 0, 'comment' => '创建时间', 'null' => false])
            ->addColumn('update_time', 'integer', ['default' => 0, 'comment' => '更新时间', 'null' => false])
            ->addColumn('delete_time', 'integer', ['comment' => '软删', 'null' => true])
            ->create();
    }
}
