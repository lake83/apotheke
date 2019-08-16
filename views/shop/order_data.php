<?php
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ArrayDataProvider */

echo \yii\grid\GridView::widget([
    'dataProvider' => $dataProvider,
    'layout' => '{items}',
    'columns' => [
        'name',
        'quantity',
        [
           'attribute' => 'price',
           'format' => 'currency',
           'value' => function ($model, $index, $widget) {
               return $model['price'];
           }
        ]
    ]
]) ?>