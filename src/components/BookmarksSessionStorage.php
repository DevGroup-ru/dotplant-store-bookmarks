<?php

namespace DotPlant\StoreBookmarks\components;

use yii\data\ArrayDataProvider;
use Yii;

class BookmarksSessionStorage implements BookmarksStorageInterface {

    private $session = null;

    public function __construct()
    {
        $this->session = Yii::$app->session;
    }

    /**
     * Добавление элемента закладки
     *
     * @param $id
     * @param null $groupId
     * @return int|string
     */
    public function addBookmark($id, $groupId = null)
    {
        $data = $this->session['store-bookmarks'];
        if(is_array($data)) {
            foreach($data as $key => $dat) {
                if(isset($dat['goods_id'])) {
                    if ($dat['goods_id'] == $id) {
                        return $key;
                    }
                }
            }
        } else {
            $data = array();
        }
        $data[]['goods_id'] = $id;
        $this->session->set('store-bookmarks', $data);
        return count($data) - 1;
    }

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
     * Невозможно изменить группу для гостя
     *
     * @param $id
     * @param $groupID
     * @return bool
     */
    public function moveBookmark($id, $groupID)
    {
        return false;
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
        $data = $this->session['store-bookmarks'];
        if(is_array($data)) {
            foreach($data as $key => $dat) {
                if(isset($dat['goods_id'])) {
                    if ($key == $id) {
                        unset($data[$key]);
                        $output = true;
                    }
                }
            }
        } else {
            $data = array();
        }
        $this->session->set('store-bookmarks', $data);
        return $output;
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


    /**
     * Получение списка в виде yii\data\ArrayDataProvide
     *
     * @return bool|ArrayDataProvider
     */
    public function getList()
    {;
        if (!$data = $this->session['store-bookmarks']) {
            $data = [];
        }
        $dataProvider = new ArrayDataProvider([
            'allModels' => $data
        ]);
        return $dataProvider;
    }

}