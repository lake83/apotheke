<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\Products;
use app\models\Delivery;
use app\models\Payment;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model app\models\Orders */
/* @var $form yii\bootstrap\ActiveForm */

$options = ['prompt' => Yii::t('app', '- choose -')];

$form = ActiveForm::begin(['layout' => 'horizontal']); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->widget(MaskedInput::className(), ['mask' => Yii::$app->params['phone_mask']]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'product_id')->dropDownList(Products::getAll(), $options) ?>

    <?= $form->field($model, 'sum')->textInput() ?>

    <?= $form->field($model, 'delivery_id')->dropDownList(Delivery::getAll(), $options) ?>

    <?= $form->field($model, 'delivery_sum')->textInput() ?>

    <?= $form->field($model, 'payment_id')->dropDownList(Payment::getAll(), $options) ?>

    <?= $form->field($model, 'status')->dropDownList($model->getStatuses(), $options) ?>

    <div class="box-footer">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>