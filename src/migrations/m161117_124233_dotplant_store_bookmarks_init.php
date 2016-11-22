<?php

use yii\db\Migration;
use DevGroup\Users\helpers\ModelMapHelper;
use DotPlant\Store\models\goods\Goods;
use DotPlant\StoreBookmarks\models\BookmarkGroup;
use DotPlant\StoreBookmarks\models\Bookmark;

class m161117_124233_dotplant_store_bookmarks_init extends Migration
{
    public function up()
    {
        $this->createTable(
            Bookmark::tableName(),
            [
                'id' => $this->primaryKey(),
                'user_id' => $this->integer()->notNull(),
                'goods_id' => $this->integer()->notNull(),
                'bookmark_group_id' => $this->integer()->null(),
            ]
        );
        $this->createTable(
            BookmarkGroup::tableName(),
            [
                'id' => $this->primaryKey(),
                'user_id' => $this->integer()->notNull(),
                'name' => $this->string(255),
            ]
        );
        $this->addForeignKey(
            'fk-dotplant_store_bookmark-user_id-user-id',
            Bookmark::tableName(),
            'user_id',
            call_user_func([ModelMapHelper::User()['class'], 'tableName']),
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-dotplant_store_bookmark-goods_id-goods-id',
            Bookmark::tableName(),
            'goods_id',
            Goods::tableName(),
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-dotplant_store_bookmark-bookmark_group_id-bookmark_group-id',
            Bookmark::tableName(),
            'bookmark_group_id',
            BookmarkGroup::tableName(),
            'id',
            'SET NULL',
            'SET NULL'
        );
        $this->addForeignKey(
            'fk-dotplant_store_bookmark_group-user_id-user-id',
            BookmarkGroup::tableName(),
            'user_id',
            call_user_func([ModelMapHelper::User()['class'], 'tableName']),
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropTable(Bookmark::tableName());
        $this->dropTable(BookmarkGroup::tableName());
    }
}
