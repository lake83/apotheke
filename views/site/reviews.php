<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model app\models\Reviews */
/* @var $form yii\bootstrap\ActiveForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('main', 'Reviews');
?>

<h1><?= $this->title ?></h1>

<?php $form = ActiveForm::begin(['id' => 'add-review-form', 'layout' => 'horizontal']); ?>

<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

<?= $form->field($model, 'ip', ['enableAjaxValidation' => true])->hiddenInput(['value'=> Yii::$app->request->userIP])->label(false) ?>

<?= Html::submitButton(Yii::t('main', 'Send'), ['class' => 'btn btn-primary col-md-offset-3 mb-5']) ?>

<?php ActiveForm::end(); ?>

<?= \yii\widgets\ListView::widget([
    'dataProvider' => $dataProvider,
    'layout' => "{items}\n{pager}",
    'itemView' => '_reviews_item'
]) ?>