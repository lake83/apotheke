<?php

namespace app\models;

use Yii;
use yii\behaviors\SluggableBehavior;
use yii\helpers\Json;

/**
 * This is the model class for table "products".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $image
 * @property string $full_text
 * @property int $position
 * @property string $title
 * @property string $keywords
 * @property string $description
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
            [['image', 'full_text', 'description'], 'string'],
            [['position', 'is_active'], 'integer'],
            [['name', 'slug', 'title', 'keywords'], 'string', 'max' => 255],
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
            'full_text' => Yii::t('app', 'Text'),
            'position' => Yii::t('app', 'Position'),
            'title' => Yii::t('app', 'Title'),
            'keywords' => Yii::t('app', 'Keywords'),
            'description' => Yii::t('app', 'Description'),
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
}