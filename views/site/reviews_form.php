<?php

/* @var $this yii\web\View */
/* @var $model app\models\Reviews */
/* @var $form yii\bootstrap\ActiveForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$form = ActiveForm::begin(['id' => 'add-review-form', 'layout' => 'horizontal']); ?>

<?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('name')])->label(false) ?>

<?= $form->field($model, 'email')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('email')])->label(false) ?>

<?= $form->field($model, 'text')->textarea(['rows' => 6, 'placeholder' => $model->getAttributeLabel('text')])->label(false) ?>

<?= $form->field($model, 'ip', ['enableAjaxValidation' => true])->hiddenInput(['value'=> Yii::$app->request->userIP])->label(false) ?>

<?= Html::submitButton(Yii::t('main', 'Add review'), ['class' => 'btn btn-primary']) ?>

<?php ActiveForm::end(); ?>