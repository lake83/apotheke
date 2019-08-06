<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

echo "<?php\n";
?>

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

$this->title = <?= $generator->generateString(Yii::t('app', 'Edit ' . Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?> . ' ' . $model-><?= $generator->getNameAttribute() ?>;

echo $this->render('_form', ['model' => $model]) ?>