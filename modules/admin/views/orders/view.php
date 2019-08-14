<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Coupon;
use yii\data\ArrayDataProvider;

/* @var $this yii\web\View */
/* @var $model app\models\Orders */

$this->title = $model->name;

?>
    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
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
            'number',
            'name',
            'phone',
            'address',
            [
                'attribute' => 'products',
                'format' => 'raw',
                'value' => function ($model) {
                    return $this->render('_order_table', [
                        'dataProvider' => new ArrayDataProvider([
                            'allModels' => $model->products,
                            'pagination' => false
                        ])
                    ]);
                }
            ],
            [
                'attribute' => 'coupon_id',
                'format' => 'raw',
                'value' => $model->coupon_id ? ('<b>' . $model->couponData->code . '</b> ' . Yii::t('app', 'discount') . ' ' . $model->couponData->value .
                    ($model->couponData->type == Coupon::TYPE_PERCENT ? ' %' : ' €')) : null
            ],
            [
                'attribute' => 'sum',
                'format' => 'raw',
                'value' => '<b>' . $model->sum . ' €</b>'
            ],
            [
                'attribute' => 'delivery_id',
                'value' => $model->delivery->name
            ],
            'delivery_sum',
            [
                'attribute' => 'payment_id',
                'value' => $model->payment->name
            ],
            [
                'attribute' => 'status',
                'value' => $model->getStatuses($model->status)
            ],
            'created_at:datetime',
            'updated_at:datetime',
            'host',
            'referrer',
            'ip',
            'agent',
            'cookie_id',
            'language'
        ]
    ]) ?>