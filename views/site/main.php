<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

echo \yii\widgets\ListView::widget([
    'id' => 'products-grid',
    'dataProvider' => $dataProvider,
    'layout' => "{items}\n{pager}",
    'itemView' => '_main_item'
]);
?>