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
            'surname',
            'phone',
            'email:email',
            'street',
            'city',
            'region',
            'postcode',
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
                'attribute' => 'delivery_id',
                'value' => $model->delivery->name
            ],
            [
                'attribute' => 'delivery_sum',
                'format' => 'raw',
                'value' => '<b>' . $model->delivery_sum  . ' €</b>'
            ],
            [
                'attribute' => 'sum',
                'format' => 'raw',
                'value' => '<b>' . $model->sum . ' €</b>'
            ],
            [
                'attribute' => 'payment_id',
                'value' => $model->payment->name
            ],
            'comment:ntext',
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