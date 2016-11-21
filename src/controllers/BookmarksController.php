<?php

namespace DotPlant\StoreBookmarks\controllers;

use Yii;
use DotPlant\StoreBookmarks\models\BookmarksItemsModel;
use DotPlant\StoreBookmarks\models\BookmarksGroupsModel;
use DotPlant\StoreBookmarks\StoreBookmarksModule;

/**
 * Class BookmarksController
 * Работа с закладками для магазина DotPlant3
 *
 * @package DotPlant\StoreBookmarks\controllers
 */
class BookmarksController extends \yii\web\Controller
{
    /**
     * @var \DotPlant\StoreBookmarks\components\BookmarksDbStorage|\DotPlant\StoreBookmarks\components\BookmarksSessionStorage|null
     */
    private $storage = null;

    public function __construct($id, $module, $config = [])
    {
        $this->storage = StoreBookmarksModule::getStorage();
        parent::__construct($id, $module, $config);
    }

    /**
     * Вывод списка закладок
     *
     * @return string|\yii\web\Response
     */
    public function actionIndex()
    {
        $data = $this->storage->getList();
        return $this->render('list', [
            'dataProvider' => $data,
        ]);
    }

    /**
     * Добавление новой закладки
     *
     * @param $id int
     * @param $groupId int
     * @return string|\yii\web\Response
     */
    public function actionAddBookmark($id, $groupId = null)
    {
        if(is_numeric($id)) {
            $this->storage->addBookmark($id, $groupId);
            return $this->redirect('index');
        } else {
            return $this->render('error', [
                'errortype' => "Ошибка создания записи",
                'errormessage' => "Необходимо передать id товара",
            ]);
        }
    }

    /**
     * Вывод списка групп пользователя
     *
     * @return string|\yii\web\Response
     */
    public function actionGroups() {
        $data = $this->storage->getGroups();
        if($data) {
            return $this->render('groups', [
                'dataProvider' => $data,
            ]);
        }
    }

    /**
     * Добаление новой группы закладок для пользователя (только для авторизованного)
     *
     * @param $name string
     * @return string|\yii\web\Response
     */
    public function actionAddGroup($name) {
        if($this->storage->addGroup($name)) {
            return $this->redirect('index');
        } else {
            return $this->render('error', [
                'errortype' => "Ошибка создания группы",
                'errormessage' => "Не удалось создать группу",
            ]);
        }
    }

    /**
     * Удаление закладки
     *
     * @param $id int
     * @return string|\yii\web\Response
     */
    public function actionRemoveBookmark($id) {
        if(is_numeric($id)) {
            if($this->storage->removeBookmark($id)) {
                return $this->redirect('index');
            } else {
                return $this->render('error', [
                    'errortype' => "Ошибка удаления записи",
                    'errormessage' => "Удаление не удалось",
                ]);
            }
        } else {
            return $this->render('error', [
                'errortype' => "Ошибка удаления записи",
                'errormessage' => "Необходимо передать id закладки",
            ]);
        }
    }

    /**
     * Перенос закладки в новую группу (только для авторизованных)
     *
     * @param $id int
     * @param $groupId int
     * @return string|\yii\web\Response
     */
    public function actionMove($id, $groupId) {
        if(is_numeric($id) && is_numeric($groupId)) {
            if($this->storage->moveBookmark($id, $groupId)) {
                return $this->redirect('index');
            } else {
                return $this->render('error', [
                    'errortype' => "Ошибка переноса записи",
                    'errormessage' => "Не удалось перенести запись в группу",
                ]);
            }
        } else {
            return $this->render('error', [
                'errortype' => "Ошибка переноса записи",
                'errormessage' => "Необходимо передать id закладки и id группы",
            ]);
        }
    }

    /**
     * Удаление группы
     *
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionRemoveGroup($id) {
        if(is_numeric($id)) {
            if($this->storage->removeGroup($id)) {
                return $this->redirect('index');
            } else {
                return $this->render('error', [
                    'errortype' => "Ошибка удаления группы",
                    'errormessage' => "Удаление не удалось",
                ]);
            }
        } else {
            return $this->render('error', [
                'errortype' => "Ошибка удаления группы",
                'errormessage' => "Необходимо передать id группы",
            ]);
        }
    }

    /**
     * Перенос
     *
     * @return \yii\web\Response
     */
    public function actionMerge() {
        $session = Yii::$app->session;
        $user_id = Yii::$app->user->id;
        $data = $session['store-bookmarks'];
        if(is_array($data) && !empty($user_id)) {
            foreach($data as $key => $dat) {
                if(isset($dat['goods_id'])) {
                    $bookmarkExist = BookmarksItemsModel::find()
                        ->where(['goods_id' => $dat['goods_id'], 'user_id' => $user_id])
                        ->one();
                    if(empty($bookmarkExist)) {
                        $bookmark = new BookmarksItemsModel();
                        $bookmark->goods_id = $dat['goods_id'];
                        $bookmark->user_id = $user_id;
                        $bookmark->save();
                    }
                }
            }
            $session->remove('store-bookmarks');
        }
        return $this->redirect('index');
    }

}