<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\grid\GridView;

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'layout' => '{items}',
    'columns' => [
        'amount',
        'price'
    ]
]) ?>