<?php

use yii\db\Migration;
use DevGroup\Users\helpers\ModelMapHelper;
use DotPlant\Store\models\goods\Goods;
use DotPlant\StoreBookmarks\models\BookmarksGroupsModel;
use DotPlant\StoreBookmarks\models\BookmarksItemsModel;


class m161117_124233_create_bookmarks_tables extends Migration
{
    public function up()
    {
        $this->createTable(BookmarksItemsModel::tableName(), [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'goods_id' => $this->integer()->notNull(),
            'bookmarks_groups_id' => $this->integer()->null(),
        ]);

        $this->createIndex(
            'idx-bookmarks_items-user_id',
            BookmarksItemsModel::tableName(),
            ['user_id']
        );

        $this->createIndex(
            'idx-bookmarks_items-bookmarks_groups_id',
            BookmarksItemsModel::tableName(),
            ['bookmarks_groups_id']
        );

        $this->createTable(BookmarksGroupsModel::tableName(),[
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'name' => $this->string(255),
        ]);

        $this->createIndex(
            'idx-bookmarks_groups-user_id',
            BookmarksGroupsModel::tableName(),
            ['user_id']
        );

        $this->addForeignKey(
            'fk-dotplant_store_bookmarks_items-user',
            BookmarksItemsModel::tableName(),
            'user_id',
            call_user_func([ModelMapHelper::User()['class'], 'tableName']),
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-dotplant_store_bookmarks_items-dotplant_store_goods',
            BookmarksItemsModel::tableName(),
            'goods_id',
            Goods::tableName(),
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-bookmarks_items-bookmarks_groups',
            BookmarksItemsModel::tableName(),
            'bookmarks_groups_id',
            Goods::tableName(),
            'id',
            'SET NULL',
            'SET NULL'
        );

        $this->addForeignKey(
            'fk-dotplant_store_bookmark_groups-user_id-user-id',
            BookmarksGroupsModel::tableName(),
            'user_id',
            call_user_func([ModelMapHelper::User()['class'], 'tableName']),
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropTable(BookmarksItemsModel::tableName());
        $this->dropTable(BookmarksGroupsModel::tableName());
    }
}
