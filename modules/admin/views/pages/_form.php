<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Pages */
/* @var $form yii\bootstrap\ActiveForm */

$form = ActiveForm::begin(['layout' => 'horizontal']); ?>

    <?php if ($model->slug !== 'main') {
        echo $form->field($model, 'name')->textInput(['maxlength' => true]);
        echo $form->field($model, 'slug')->textInput(['maxlength' => true])->hint(Yii::t('app', 'Generated from name.'));
    } ?>
    
    <?php if ($model->is_product) {
        echo $form->field($model, 'position')->textInput();
    } ?>
    
    <?= $form->field($model, 'image')->widget(\app\components\FilemanagerInput::className()) ?>

    <?= $form->field($model, 'content')->widget(\app\components\RedactorTinymce::className()) ?>
    
    <?php if ($model->slug !== 'main') {
        echo $form->field($model, 'is_product')->checkbox();
        echo $form->field($model, 'is_active')->checkbox();
    } ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'keywords')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <div class="box-footer">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>