<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "products_price".
 *
 * @property int $id
 * @property int $product_id
 * @property int $amount
 * @property double $price
 *
 * @property Products $product
 */
class ProductsPrice extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'products_price';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['amount', 'price'], 'required'],
            [['product_id', 'amount'], 'integer'],
            [['price'], 'number'],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => Yii::t('app', 'Product'),
            'amount' => Yii::t('app', 'Amount'),
            'price' => Yii::t('app', 'Price') . ', EUR'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Products::className(), ['id' => 'product_id']);
    }
}