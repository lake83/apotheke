<?php

namespace app\models;

use Yii;
use yii\behaviors\SluggableBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "products".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $image
 * @property int $position
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
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'name',
                'immutable' => true
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['image'], 'string'],
            [['position', 'is_active'], 'integer'],
            [['name', 'slug'], 'string', 'max' => 255],
            ['image', 'safe']
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
            'slug' => Yii::t('app', 'Alias'),
            'image' => Yii::t('app', 'Image'),
            'position' => Yii::t('app', 'Position'),
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