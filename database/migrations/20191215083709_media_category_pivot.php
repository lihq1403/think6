<?php

use think\migration\Migrator;
use think\migration\db\Column;

class MediaCategoryPivot extends Migrator
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
        $table = $this->table('media_category_pivot', array('engine' => 'InnoDB', 'comment' => '媒体分类 多对多中间表'));

        $table
            ->addColumn('media_id', 'integer', ['default' => 0, 'comment' => '媒体id', 'null' => false, 'signed' => false])
            ->addColumn('media_category_id', 'integer', ['default' => 0, 'comment' => '分类id', 'null' => false, 'signed' => false])

            ->addColumn('status', 'integer', ['limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY, 'default' => 1, 'comment' => '预留字段', 'null' => false])

            ->addColumn('create_time', 'integer', ['default' => 0, 'comment' => '创建时间', 'null' => false])
            ->addColumn('update_time', 'integer', ['default' => 0, 'comment' => '更新时间', 'null' => false])
            ->addColumn('delete_time', 'integer', ['comment' => '软删', 'null' => true])
            ->create();
    }
}
