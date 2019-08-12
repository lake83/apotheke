<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Coupon;

/* @var $this yii\web\View */
/* @var $model app\models\Orders */

$this->title = $model->name;

?>
    <p>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'ip',
            'text:ntext',
            'is_active:boolean',
            'created_at:datetime'
        ]
    ]) ?>