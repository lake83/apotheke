<?php
use yii\bootstrap\ActiveForm;

$form = ActiveForm::begin(['id' => 'clearData', 'layout' => 'horizontal']);

echo $form->field($model, 'period')->dropDownList([
        '30' => Yii::t('app', 'Over 30 days'),
        '60' => Yii::t('app', 'Over 60 days'),
        '90' => Yii::t('app', 'Over 90 days'),
        'all' => Yii::t('app', 'All'),
    ], ['prompt' => Yii::t('app', '- choose -')])->label(Yii::t('app', 'Period'));

echo \yii\helpers\Html::submitButton(Yii::t('app', 'Delete'), ['class' => 'btn btn-danger', 'data-confirm' => Yii::t('app', 'Are you sure you want to delete orders and traffic?')]);

ActiveForm::end(); ?>