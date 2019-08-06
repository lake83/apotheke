<?php

/* @var $this yii\web\View */
/* @var $model app\models\Pages */

$this->title = Yii::t('app', 'Edit page') . ' ' . $model->slug;

echo $this->render('_form', ['model' => $model]) ?>