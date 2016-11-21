<?php

namespace DotPlant\StoreBookmarks;

use Yii;
use DotPlant\StoreBookmarks\components\BookmarksSessionStorage;
use DotPlant\StoreBookmarks\components\BookmarksDbStorage;

class StoreBookmarksModule extends \yii\base\Module
{

    /**
     * @var BookmarksDbStorage|BookmarksSessionStorage Storage for bookmarks
     */
    private static $_storage;


    /**
     * @return BookmarksDbStorage|BookmarksSessionStorage
     */
    public static function getStorage() {
        if(!self::$_storage) {
            self::$_storage = Yii::$app->user->isGuest
                ? new BookmarksSessionStorage
                : new BookmarksDbStorage;
        }
        return self::$_storage;
    }

    /**
     * @var bool module enabling
     */
    public $enableBookmarks = false;

    /**
     * @return self Module instance in application
     */
    public static function module()
    {
        $module = Yii::$app->getModule('store-bookmarks');
        if ($module === null) {
            $module = Yii::createObject(self::class, ['store-bookmarks']);
        }
        return $module;
    }
}
