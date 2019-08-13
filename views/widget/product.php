<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\grid\GridView;
use yii\helpers\Html;

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'layout' => '{items}',
    'columns' => [
        [
           'attribute' => 'name',
           'header' => Yii::t('main', 'Product')
        ],
        [
           'attribute' => 'price',
           'header' => Yii::t('main', 'Price'),
           'value' => function ($model, $index, $widget) {
               return $model->price . ' €';
           }
        ],
        
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{buy}',
            'buttons' => [
                'buy' => function ($url, $model, $key) {
                    return Html::a('<span class="glyphicon glyphicon-gift"></span>', ['site/buy', 'id' => $model->id], [
                        'title' => Yii::t('main', 'Buy')
                    ]);
                }
            ],
            'options' => ['width' => '30px']
        ]
    ]
]) ?>