<?php

namespace DotPlant\StoreBookmarks\components;

use Yii;
use yii\data\ActiveDataProvider;
use DotPlant\StoreBookmarks\models\BookmarksItemsModel;
use DotPlant\StoreBookmarks\models\BookmarksGroupsModel;

class BookmarksDbStorage implements BookmarksStorageInterface {

    private $user_id = null;

    public function __construct()
    {
        $this->user_id = Yii::$app->user->id;
    }

    /**
     * Добавление элемента закладки
     *
     * @param int $id
     * @param null $groupId
     * @return bool|int
     */
    public function addBookmark($id, $groupId = null)
    {
        $output = false;
        $bookmark = new BookmarksItemsModel();

        if (!empty($this->user_id)) {
            $bookmarkExist = BookmarksItemsModel::find()
                ->where(['goods_id' => $groupId, 'user_id' => $this->user_id])
                ->one();

            // Сохраняем если итем новый
            if(empty($bookmarkExist)) {
                $bookmark->goods_id = $id;
                $bookmark->user_id = $this->user_id;
                if(is_numeric($groupId)) {
                    $bookmark->bookmarks_groups_id = $groupId;
                }
                $bookmark->save();
            }
            $output = $bookmark->id;
        }
        return $output;
    }

    public function addGroup($name)
    {
        $output = false;
        if (!empty($this->user_id)) {
            $bookmarkG = new BookmarksGroupsModel();
            $bookmarkGExist = BookmarksGroupsModel::find()
                ->where(['name' => $name, 'user_id' => $this->user_id])
                ->one();
            if(empty($bookmarkGExist)) {
                $bookmarkG->name = $name;
                $bookmarkG->user_id = $this->user_id;
                $bookmarkG->save();
            }
            $output = $bookmarkG->id;
        }
        return $output;
    }

    /**
     * Перенос закладки в группу
     *
     * @param $id
     * @param $groupId
     * @return bool
     */
    public function moveBookmark($id, $groupId)
    {
        $output = false;
        if(!empty($this->user_id)) {
            $bookmark = BookmarksItemsModel::findOne($id);
            $bookmarkG = BookmarksGroupsModel::findOne($groupId);
            if(!empty($bookmark) && !empty($bookmarkG)) {
                $bookmark->bookmarks_groups_id = $groupId;
                $bookmark->save();
                $output = true;
            } else {
                $output = false;
            }
        }
        return $output;
    }

    /**
     * Удаление закладки
     *
     * @param $id
     * @return bool
     */
    public function removeBookmark($id)
    {
        $output = false;
        if(!empty($this->user_id)) {
            $bookmark = BookmarksItemsModel::findOne($id);
            if(!empty($bookmark)) {
                if($bookmark->delete()) {
                    $output = true;
                }
            }
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
        if(!empty($this->user_id)) {
            $this->user_id = Yii::$app->user->id;
            $dataProvider = new ActiveDataProvider([
                'query' => BookmarksGroupsModel::find()->where(['user_id' => $this->user_id]),
            ]);
            $output = $dataProvider;
        }
        return $output;
    }

    public function removeGroup($id)
    {
        $output = false;
        if(!empty($this->user_id)) {
            $bookmark = BookmarksGroupsModel::findOne($id);
            if(!empty($bookmark)) {
                if($bookmark->delete()) {
                    $output = true;
                }
            }
        }
        return $output;
    }

    /**
     * Получение списка в виде yii\data\ActiveDataProvide
     *
     * @return bool|ActiveDataProvider
     */
    public function getList()
    {
        $output = false;
        if(!empty($this->user_id)) {
            $this->user_id = Yii::$app->user->id;
            $dataProvider = new ActiveDataProvider([
                'query' => BookmarksItemsModel::find()->where(['user_id' => $this->user_id])->with('group'),
            ]);
            $output = $dataProvider;
        }
        return $output;
    }

}