<?php

namespace DotPlant\StoreBookmarks\controllers;

use DevGroup\Frontend\Universal\SuperAction;
use DotPlant\Monster\Universal\ServiceMonsterAction;
use DotPlant\Store\models\goods\Goods;
use DotPlant\StoreBookmarks\Module;
use Yii;
use yii\web\Response;

/**
 * Class BookmarksController
 * Работа с закладками для магазина DotPlant3
 *
 * @package DotPlant\StoreBookmarks\controllers
 */
class BookmarkController extends \yii\web\Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => SuperAction::class,
                'actions' => [
                    [
                        'class' => ServiceMonsterAction::class,
                        'serviceTemplateKey' => Module::module()->bookmarksListServiceTemplateKey,
                    ],
                ],
            ]
        ];
    }

    /**
     * @param int $id
     * @param null|int $groupId
     * @return int
     */
    public function actionAdd($id, $groupId = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $goods = Goods::get($id);
        if ($goods !== null) {
            return Module::getStorage()->add($id, $groupId);
        } else {
            return 0;
        }
    }

    /**
     * @param int $id
     * @return int
     */
    public function actionRemove($id) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return Module::getStorage()->remove($id);
    }

    /**
     * @param int $id
     * @param int $groupId
     * @return int
     */
    public function actionMove($id, $groupId) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return Module::getStorage()->move($id, $groupId);
    }

    /**
     * ==========================================      Groups      ==========================================
     */

    /**
     * Вывод списка групп пользователя
     *
     * @return string|\yii\web\Response
     */
    public function actionGroups() {
        $data = $this->storage->getGroups();
        if ($data) {
            return $this->render(
                'groups',
                [
                    'dataProvider' => $data,
                ]
            );
        }
    }

    /**
     * Добаление новой группы закладок для пользователя (только для авторизованного)
     *
     * @param $name string
     * @return string|\yii\web\Response
     */
    public function actionAddGroup($name) {
        if ($this->storage->addGroup($name)) {
            return $this->redirect('index');
        } else {
            return $this->render(
                'error',
                [
                    'errortype' => "Ошибка создания группы",
                    'errormessage' => "Не удалось создать группу",
                ]
            );
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