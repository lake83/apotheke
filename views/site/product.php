<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\grid\GridView;
use yii\helpers\Html;

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'tableOptions' => ['class' => 'table product-table'],
    'layout' => '{items}',
    'columns' => [
        [
           'attribute' => 'name',
           'header' => Yii::t('main', 'Product')
        ],
        [
           'attribute' => 'price',
           'format' => 'currency',
           'header' => Yii::t('main', 'Price'),
           'value' => function ($model, $index, $widget) {
               return $model->price;
           }
        ],
        
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{buy}',
            'buttons' => [
                'buy' => function ($url, $model, $key) {
                    return Html::a('<img src="/images/shopping-cart-buy.png" width="36" />', ['shop/buy', 'id' => $model->id], [
                        'title' => Yii::t('main', 'Buy'),
                        'data-method' => 'POST'
                    ]);
                }
            ],
            'options' => ['width' => '30px']
        ]
    ]
]) ?>