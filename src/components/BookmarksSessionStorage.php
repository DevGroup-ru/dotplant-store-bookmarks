<?php

namespace DotPlant\StoreBookmarks\components;

use yii\data\ArrayDataProvider;
use Yii;

class BookmarksSessionStorage implements BookmarksStorageInterface
{
    private $_session = null;

    public function __construct()
    {
        $this->_session = Yii::$app->session;
    }

    /**
     * Добавление элемента закладки
     *
     * @param $id
     * @param null $groupId
     * @return int|string
     */
    public function add($id, $groupId = null)
    {
        $data = $this->_session['store-bookmarks'];
        if (is_array($data)) {
            foreach ($data as $key => $dat) {
                if (isset($dat['goods_id'])) {
                    if ($dat['goods_id'] == $id) {
                        return $key;
                    }
                }
            }
        } else {
            $data = [];
        }
        $data[]['goods_id'] = $id;
        $this->_session->set('store-bookmarks', $data);
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
    public function move($id, $groupID)
    {
        return false;
    }

    /**
     * Удаление закладки
     *
     * @param $id
     * @return bool
     */
    public function remove($id)
    {
        $output = false;
        $data = $this->_session['store-bookmarks'];
        if (is_array($data)) {
            foreach ($data as $key => $dat) {
                if (isset($dat['goods_id'])) {
                    if ($key == $id) {
                        unset($data[$key]);
                        $output = true;
                    }
                }
            }
        } else {
            $data = [];
        }
        $this->_session->set('store-bookmarks', $data);
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
    {
        if (!$data = $this->_session['store-bookmarks']) {
            $data = [];
        }
        $dataProvider = new ArrayDataProvider(
            [
                'allModels' => $data
            ]
        );
        return $dataProvider;
    }
}
