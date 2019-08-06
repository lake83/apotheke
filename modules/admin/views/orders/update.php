<?php

/* @var $this yii\web\View */
/* @var $model app\models\Orders */

$this->title = Yii::t('app', 'Edit order') . ' ' . $model->name;

echo $this->render('_form', ['model' => $model]) ?>