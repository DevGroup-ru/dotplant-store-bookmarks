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
 * @property integer $bookmarks_groups_id
 */
class BookmarksItemsModel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%dotplant_store_bookmarks_items}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'goods_id'], 'required'],
            [['user_id', 'goods_id', 'bookmarks_groups_id'], 'integer'],
            [['bookmarks_groups_id'], 'exist', 'skipOnError' => true, 'targetClass' => Goods::className(), 'targetAttribute' => ['bookmarks_groups_id' => 'id']],
            [['goods_id'], 'exist', 'skipOnError' => true, 'targetClass' => Goods::className(), 'targetAttribute' => ['goods_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelMapHelper::User()['class'], 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('dotplant.store-bookmarks', 'ID'),
            'user_id' => Yii::t('dotplant.store-bookmarks', 'User ID'),
            'goods_id' => Yii::t('dotplant.store-bookmarks', 'Goods ID'),
            'bookmarks_groups_id' => Yii::t('dotplant.store-bookmarks', 'Bookmarks Groups ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(BookmarksGroupsModel::className(), ['id' => 'bookmarks_groups_id']);
    }
}
