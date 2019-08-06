<?php

/* @var $this yii\web\View */
/* @var $model app\models\Products */

$this->title = 'Edit product: ' . $model->name;

echo $this->render('_form', ['model' => $model, 'models' => $models]) ?>