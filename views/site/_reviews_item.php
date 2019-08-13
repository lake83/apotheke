<?php

/* @var $this \yii\web\View */
/* @var $model \app\models\Reviews */
?>

<div class="review-item" class="col-md-12">
    <b><?= Yii::$app->formatter->asDate($model->created_at, 'short') . ' - ' . $model->name ?></b>
    <p><?= $model->text ?></p>
</div>