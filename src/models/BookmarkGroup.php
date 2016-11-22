<?php

namespace DotPlant\StoreBookmarks\models;

use Yii;
use DevGroup\Users\helpers\ModelMapHelper;

/**
 * This is the model class for table "dotplant_store_bookmarks_groups".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 */
class BookmarkGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%dotplant_store_bookmark_group}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [
                ['user_id'],
                'exist',
                'skipOnError' => true,
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
            'name' => Yii::t('dotplant.store-bookmarks', 'Group name'),
        ];
    }
}
