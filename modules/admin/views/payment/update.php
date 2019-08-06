<?php

/* @var $this yii\web\View */
/* @var $model app\models\Payment */

$this->title = Yii::t('app', 'Edit payment') . ' ' . $model->name;

echo $this->render('_form', ['model' => $model]) ?>