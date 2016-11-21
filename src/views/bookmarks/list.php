<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('dotplant.store-bookmarks', 'Bookmarks Items Models');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bookmarks-items-model-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'goods_id',
            'group.name',
        ],
    ]); ?>
</div>