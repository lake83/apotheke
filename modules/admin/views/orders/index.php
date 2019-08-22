<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\daterange\DateRangePicker;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OrdersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Orders');

echo GridView::widget([
    'layout' => '{items}{pager}',
    'dataProvider' => $dataProvider,
    'pjax' => true,
    'pjaxSettings' => ['loadingCssClass' => false],
    'export' => false,
    'showPageSummary' => true,
    'filterModel' => $searchModel,
        'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

            'name',
            'surname',
            'phone',
            'email:email',
            [
                'attribute' => 'products',
                'value' => function ($model, $index, $widget) {
                    $names = [];
                    foreach ($model->products as $product) {
                        $names[] = $product['name'];
                    }
                    return implode(',', $names);
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
                'attribute' => 'created_at',
                'format' => 'datetime',
                'filterType' => GridView::FILTER_DATE_RANGE,
                'filterWidgetOptions' => [       
                'attribute' => 'created_at',
                'presetDropdown' => true,
                'convertFormat' => false,
                    'pluginOptions' => [
                        'locale' => [
                            'format' => 'D.MM.Y',
                            'separator' => '-'
                        ]
                    ]
                ]
            ],
            [
                'attribute' => 'sum',
                'format' => ['decimal', 2],
                'pageSummary' => true
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'options' => ['width' => '70px']
            ]
        ]
    ]);
?>