<?php

namespace DotPlant\StoreBookmarks\models;

use DotPlant\StoreBookmarks\Module;
use DevGroup\ExtensionsManager\models\BaseConfigurationModel;
use Yii;

class StoreBookmarksConfiguration extends BaseConfigurationModel
{
    public $bookmarksListServiceTemplateKey = 'bookmarksList';

    /**
     * @inheritdoc
     */
    public function getModuleClassName()
    {
        return Module::class;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bookmarksListServiceTemplateKey'], 'required'],
            [['bookmarksListServiceTemplateKey'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'bookmarksListServiceTemplateKey' => Yii::t('dotplant.store-bookmarks', 'Key from service template for bookmarks list'),
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
                'store-bookmarks' => [
                    'class' => Module::class,
                    'bookmarksListServiceTemplateKey' => $this->bookmarksListServiceTemplateKey,
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
