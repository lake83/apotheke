<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Coupon */
/* @var $form yii\bootstrap\ActiveForm */

$form = ActiveForm::begin(['layout' => 'horizontal']); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $model->isNewRecord ? $form->field($model, 'code')->textarea(['rows' => 6]) : $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->dropDownList($model->getTypes(), ['prompt' => Yii::t('app', '- choose -')]) ?>
    
    <?= $form->field($model, 'value')->textInput() ?>
    
    <?= $form->field($model, 'date_from')->widget(DatePicker::className(), [
        'options' => ['class' => 'form-control'],
        'dateFormat' => 'dd.MM.yyyy'
    ]) ?>
    
    <?= $form->field($model, 'date_to')->widget(DatePicker::className(), [
        'options' => ['class' => 'form-control'],
        'dateFormat' => 'dd.MM.yyyy'
    ]) ?>

    <?= $form->field($model, 'is_active')->checkbox() ?>

    <div class="box-footer">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>