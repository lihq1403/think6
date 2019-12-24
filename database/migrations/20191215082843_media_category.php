<?php

use think\migration\Migrator;
use think\migration\db\Column;

class MediaCategory extends Migrator
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
        $table = $this->table('media_category', array('engine' => 'InnoDB', 'comment' => '媒体资源分类 表'));

        $table
            ->addColumn('name', 'string', ['default' => '', 'comment' => '类目名称', 'null' => true])
            ->addColumn('pid', 'integer', ['default' => 0, 'comment' => '父目录id', 'null' => false, 'signed' => false])
            ->addColumn('sort', 'integer', ['default' => 0, 'comment' => '排序', 'null' => false])
            ->addColumn('is_show', 'integer', ['limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY, 'default' => 1, 'comment' => '是否显示 1是 2否', 'null' => false])

            ->addColumn('status', 'integer', ['limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY, 'default' => 1, 'comment' => '预留字段', 'null' => false])
            ->addColumn('create_time', 'integer', ['default' => 0, 'comment' => '创建时间', 'null' => false])
            ->addColumn('update_time', 'integer', ['default' => 0, 'comment' => '更新时间', 'null' => false])
            ->addColumn('delete_time', 'integer', ['comment' => '软删', 'null' => true])
            ->addIndex('pid')
            ->create();
    }
}
