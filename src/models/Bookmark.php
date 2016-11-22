<?php

namespace DotPlant\StoreBookmarks\models;

use Yii;
use DotPlant\Store\models\goods\Goods;
use DevGroup\Users\helpers\ModelMapHelper;

/**
 * This is the model class for table "dotplant_store_bookmarks_items".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $goods_id
 * @property integer $bookmark_group_id
 */
class Bookmark extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%dotplant_store_bookmark}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'goods_id'], 'required'],
            [['user_id', 'goods_id', 'bookmark_group_id'], 'integer'],
            [
                ['bookmark_group_id'],
                'exist',
                'targetClass' => BookmarkGroup::class,
                'targetAttribute' => ['bookmarks_groups_id' => 'id'],
            ],
            [
                ['goods_id'],
                'exist',
                'skipOnEmpty' => false,
                'skipOnError' => false,
                'targetClass' => Goods::class,
                'targetAttribute' => ['goods_id' => 'id'],
            ],
            [
                ['user_id'],
                'exist',
                'skipOnEmpty' => false,
                'skipOnError' => false,
                'targetClass' => ModelMapHelper::User()['class'],
                'targetAttribute' => ['user_id' => 'id'],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('dotplant.store-bookmarks', 'ID'),
            'user_id' => Yii::t('dotplant.store-bookmarks', 'User'),
            'goods_id' => Yii::t('dotplant.store-bookmarks', 'Goods'),
            'bookmark_group_id' => Yii::t('dotplant.store-bookmarks', 'Bookmarks group'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(BookmarkGroup::class, ['id' => 'bookmark_group_id']);
    }
}
