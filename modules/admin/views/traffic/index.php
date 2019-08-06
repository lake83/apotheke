<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\components\SiteHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TrafficSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Traffic');

echo GridView::widget([
    'layout' => '{items}{pager}',
    'dataProvider' => $dataProvider,
    'pjax' => true,
    'export' => false,
    'filterModel' => $searchModel,
        'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

            'host',
            'referrer',
            'ip',
            'agent',
            'cookie_id',
            'language',
            SiteHelper::created_at($searchModel),
            
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
                'options' => ['width' => '50px']
            ]
        ]
    ]);
?>