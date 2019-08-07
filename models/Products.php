<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "products".
 *
 * @property int $id
 * @property string $name
 * @property string $number
 * @property int $is_active
 */
class Products extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'products';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['name', 'required'],
            ['is_active', 'integer'],
            ['number', 'string', 'max' => 20],
            ['name', 'string', 'max' => 255]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => Yii::t('app', 'Name'),
            'number' => Yii::t('app', 'Number'),
            'is_active' => Yii::t('app', 'Active')
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductsPrices()
    {
        return $this->hasMany(ProductsPrice::className(), ['product_id' => 'id']);
    }
    
    /**
     * List of all products
     */
    public static function getAll()
    {
        return ArrayHelper::map(self::find()->select(['id', 'name'])->where(['is_active' => 1])->asArray()->orderBy('name ASC')->all(), 'id', 'name');
    }
}