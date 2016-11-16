<?php

namespace DotPlant\StoreBookmarks\models;

use DotPlant\StoreBookmarks\Module;
use DevGroup\ExtensionsManager\models\BaseConfigurationModel;
use Yii;

class StoreBookmarksConfiguration extends BaseConfigurationModel
{
    /**
     * @inheritdoc
     */
    public function getModuleClassName()
    {
        return StoreBookmarksModule::className();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['enableBookmarks'], 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'enableBookmarks' => Yii::t('dotplant.store-bookmarks', 'Enable Bookmarks'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function webApplicationAttributes()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function consoleApplicationAttributes()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function commonApplicationAttributes()
    {
        return [
            'components' => [
                'i18n' => [
                    'translations' => [
                        'dotplant.store-bookmarks' => [
                            'class' => 'yii\i18n\PhpMessageSource',
                            'basePath' => dirname(__DIR__) . DIRECTORY_SEPARATOR . 'messages',
                        ]
                    ]
                ],
            ],
            'modules' => [
                'moduleName' => [
                    'class' => BookMarksModule::class,
                    'enableBookmarks' => (bool) $this->enableBookmarks
                ]
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function appParams()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function aliases()
    {
        return [
            '@DotPlant/StoreBookmarks' => realpath(dirname(__DIR__)),
        ];
    }
}
