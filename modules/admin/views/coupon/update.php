<?php

/* @var $this yii\web\View */
/* @var $model app\models\Coupon */

$this->title = Yii::t('app', 'Edit coupon') . ' ' . $model->name;

echo $this->render('_form', ['model' => $model]) ?>