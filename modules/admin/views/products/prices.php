<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;

/* @var $this yii\web\View */
/* @var $model app\models\Products */
/* @var $models app\models\ProductsPrice */
/* @var $form yii\bootstrap\ActiveForm */

$this->title = Yii::t('app', 'Prices of product') . ': ' . $model->name;

$form = ActiveForm::begin(['id' => 'dynamic-form', 'layout' => 'horizontal']); ?>
    
    <div class="col-sm-12">
    
    <?php DynamicFormWidget::begin([
          'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
          'widgetBody' => '.container-items', // required: css class selector
          'widgetItem' => '.item', // required: css class
          'insertButton' => '.add-item', // css class
          'deleteButton' => '.remove-item', // css class
          'model' => $models[0],
          'formId' => 'dynamic-form',
          'formFields' => [
              'amount',
              'price'
          ]
    ]);
    echo Html::activeHiddenInput($model, 'name'); ?>

        <div class="container-items">
            <button type="button" class="add-item btn btn-default" style="margin: 0 0 30px -15px;"><i class="glyphicon glyphicon-plus"></i> <?= Yii::t('app', 'Add') ?></button>
            <div class="clearfix"></div>
            
            <div class="row">
                <div class="col-sm-5"><b><?= $models[0]->getAttributeLabel('amount') ?></b></div>
                <div class="col-sm-5"><b><?= $models[0]->getAttributeLabel('price') ?></b></div>
            </div>
            
            <?php foreach ($models as $i => $one): ?>
            <div class="item">
            <?php
                // necessary for update action.
                if (!$one->isNewRecord) {
                    echo Html::activeHiddenInput($one, "[{$i}]id");
                }
            ?>
                <div class="row">
                <div class="col-md-5" style="padding: 0 25px;">
                    <?= $form->field($one, "[{$i}]amount", ['template' => "{input}\n{error}"])->textInput(['maxlength' => true]) ?>
                </div>
                
                <div class="col-md-5" style="padding: 0 25px;">
                    <?= $form->field($one, "[{$i}]price", ['template' => "{input}\n{error}"])->textInput(['maxlength' => true]) ?>
                </div>
                
                <button type="button" class="remove-item btn btn-danger" title="<?= Yii::t('app', 'Delete') ?>"><i class="glyphicon glyphicon-minus"></i></button>
                
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
    <?php DynamicFormWidget::end(); ?>
    
    </div>

    <div class="box-footer">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>