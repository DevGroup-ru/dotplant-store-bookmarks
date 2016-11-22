<?php

namespace DotPlant\StoreBookmarks\components;

use DotPlant\StoreBookmarks\models\Bookmark;
use DotPlant\StoreBookmarks\models\BookmarkGroup;
use Yii;
use yii\data\ActiveDataProvider;

class BookmarksDbStorage implements BookmarksStorageInterface
{
    /**
     * @inheritdoc
     */
    public function add($id, $groupId = null)
    {
        if ($this->loadBookmark($id) === null) {
            $bookmark = new Bookmark;
            $bookmark->attributes = [
                'goods_id' => $id,
                'user_id' => Yii::$app->user->id,
                'bookmark_group_id' => $groupId,
            ];
            return (int) $bookmark->save();
        } else {
            return 1;
        }
    }

    /**
     * @inheritdoc
     */
    public function move($id, $groupId)
    {
        $bookmark = $this->loadBookmark($id);
        if ($bookmark !== null) {
            $bookmark->bookmark_group_id = $groupId;
            return (int) $bookmark->save(true, ['bookmark_group_id']);
        } else {
            return 0;
        }
    }

    /**
     * @inheritdoc
     */
    public function remove($id)
    {
        $bookmark = $this->loadBookmark($id);
        if ($bookmark !== null) {
            return (int) $bookmark->delete();
        } else {
            return 0;
        }
    }

    /**
     * @inheritdoc
     */
    public function getList($groupId = null)
    {
        $query = Bookmark::find()
            ->asArray(true)
            ->where(['user_id' => Yii::$app->user->id]);
        if ($groupId !== null) {
            $query->andWhere(['bookmark_group_id' => $groupId]);
        }
        return $query->all();
    }

    /**
     * ====================================    Groups    ====================================
     */

    public function addGroup($name)
    {
        $output = false;
        if (!empty($this->user_id)) {
            $bookmarkG = new BookmarkGroup();
            $bookmarkGExist = BookmarkGroup::find()
                ->where(['name' => $name, 'user_id' => $this->user_id])
                ->one();
            if (empty($bookmarkGExist)) {
                $bookmarkG->name = $name;
                $bookmarkG->user_id = $this->user_id;
                $bookmarkG->save();
            }
            $output = $bookmarkG->id;
        }
        return $output;
    }

    /**
     * Получает список групп
     *
     * @return bool|ActiveDataProvider
     */
    public function getGroups()
    {
        $output = false;
        if (!empty($this->user_id)) {
            $this->user_id = Yii::$app->user->id;
            $dataProvider = new ActiveDataProvider(
                [
                    'query' => BookmarkGroup::find()->where(['user_id' => $this->user_id]),
                ]
            );
            $output = $dataProvider;
        }
        return $output;
    }

    public function removeGroup($id)
    {
        $output = false;
        if (!empty($this->user_id)) {
            $bookmark = BookmarkGroup::findOne($id);
            if (!empty($bookmark)) {
                if ($bookmark->delete()) {
                    $output = true;
                }
            }
        }
        return $output;
    }

    /**
     * Load the bookmark by id
     * @param int $id
     */
    protected function loadBookmark($id)
    {
        return Bookmark::findOne(['user_id' => Yii::$app->user->id, 'goods_id' => $id]);
    }
}
