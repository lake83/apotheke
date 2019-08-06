<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\components\SiteHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OrdersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Orders');

echo GridView::widget([
    'layout' => '{items}{pager}',
    'dataProvider' => $dataProvider,
    'pjax' => true,
    'export' => false,
    'showPageSummary' => true,
    'filterModel' => $searchModel,
        'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

            'name',
            'phone',
            [
                'attribute' => 'product_id',
                'value' => function ($model, $index, $widget) {
                    return $model->product->name;
                }
            ],
            [
                'attribute' => 'status',
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'status',
                    $searchModel->getStatuses(),
                    ['class' => 'form-control', 'prompt' => Yii::t('app', '- choose -')]
                ),
                'value' => function ($model, $index, $widget) {
                    return $model->getStatuses($model->status);}
            ],
            [
                'attribute' => 'sum',
                'pageSummary' => true
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{delete}',
                'options' => ['width' => '50px']
            ]
        ]
    ]);
?>