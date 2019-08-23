<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>

<p><?= Yii::t('main', 'Thank you for contacting us. We will reply as soon as possible.') ?></p>

<?php else: ?>

<?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('name')])->label(false) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('email')])->label(false) ?>

    <?= $form->field($model, 'subject')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('subject')])->label(false) ?>

    <?= $form->field($model, 'text')->textarea(['rows' => 4, 'placeholder' => $model->getAttributeLabel('text')])->label(false) ?>

    <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
        'template' => '<div class="col-md-5 col-xs-5">{image}</div><div class="col-md-7 col-xs-7">{input}</div><div class="clearfix"></div>'
    ])->label(false) ?>

    <?= Html::submitButton(Yii::t('main', 'Send'), ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>

<?php ActiveForm::end(); ?>

<?php endif; ?>