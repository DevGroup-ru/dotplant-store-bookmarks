<?php

namespace DotPlant\StoreBookmarks\components;

use yii\data\ArrayDataProvider;
use Yii;

class BookmarksSessionStorage implements BookmarksStorageInterface
{
    const SESSION_KEY_BOOKMARKS = 'DotPlant.StoreBookmarks.List';
    /**
     * @inheritdoc
     */
    public function add($id, $groupId = null)
    {
        $goodsIds = Yii::$app->session->get(self::SESSION_KEY_BOOKMARKS, []);
        if (!in_array($id, $goodsIds)) {
            $goodsIds[] = $id;
            Yii::$app->session->set(self::SESSION_KEY_BOOKMARKS, $goodsIds);
        }
        return 1;
    }

    /**
     * @inheritdoc
     */
    public function move($id, $groupID)
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function remove($id)
    {
        $goodsIds = Yii::$app->session->get(self::SESSION_KEY_BOOKMARKS, []);
        $index = array_search($id, $goodsIds);
        if ($index !== false) {
            unset($goodsIds[$index]);
            Yii::$app->session->set(self::SESSION_KEY_BOOKMARKS, $goodsIds);
            return 1;
        }
        return 0;
    }

    /**
     * @inheritdoc
     */
    public function getList($groupId = null)
    {
        $goodsIds = Yii::$app->session->get(self::SESSION_KEY_BOOKMARKS, []);
        $result = [];
        foreach ($goodsIds as $goodsId) {
            $result[] = [
                'user_id' => null,
                'goods_id' => $goodsId,
                'bookmark_group_id' => null,
            ];
        }
        return $result;
    }

    /**
     * ===============================    Groups    ===============================
     */

    /**
     * Невозможно добавить группу для гостя
     *
     * @param $name
     * @return bool
     */
    public function addGroup($name)
    {
        return false;
    }

    /**
     * Невозможно вывести список групп для гостя
     *
     * @return bool
     */
    public function getGroups()
    {
        return false;
    }

    /**
     * Невозможно удалить группу для гостя
     *
     * @param $id
     * @return bool
     */
    public function removeGroup($id)
    {
        return false;
    }
}
