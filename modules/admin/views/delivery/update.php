<?php

/* @var $this yii\web\View */
/* @var $model app\models\Delivery */

$this->title = Yii::t('app', 'Edit delivery') . ' ' . $model->name;

echo $this->render('_form', ['model' => $model]) ?>