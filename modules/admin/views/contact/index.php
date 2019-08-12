<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\components\SiteHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ContactSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Contact');

echo GridView::widget([
    'layout' => '{items}{pager}',
    'dataProvider' => $dataProvider,
    'pjax' => true,
    'export' => false,
    'filterModel' => $searchModel,
        'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

            'name',
            'email:email',
            'subject',
            SiteHelper::created_at($searchModel),

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {delete}',
                'options' => ['width' => '50px']
            ]
        ]
    ]);
?>