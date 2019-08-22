<?php
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ArrayDataProvider */

echo \yii\grid\GridView::widget([
    'dataProvider' => $dataProvider,
    'tableOptions' => ['class' => 'table cart-table'],
    'layout' => '{items}',
    'columns' => [
        [
           'attribute' => 'name',
           'header' => Yii::t('main', 'Product')
        ],
        [
           'attribute' => 'quantity',
           'header' => Yii::t('main', 'Amount')
        ],
        [
           'attribute' => 'price',
           'header' => Yii::t('main', 'Price'),
           'format' => 'currency'
        ]
    ]
]) ?>