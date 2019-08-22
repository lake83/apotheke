<?php

/* @var $this \yii\web\View */
/* @var $model \app\models\Reviews */
?>

<div class="review-item">
    <p><b><?= $model->name ?></b><span class="pull-right"><?= Yii::$app->formatter->asDate($model->created_at, 'long') ?></span></p>
    <div class="col-md-2 no-padding"><?= Yii::t('main', 'Feedback') ?>:</div>
    <div class="col-md-10"><?= $model->text ?></div>
    <div class="clearfix"></div>
</div>