<?php

namespace app\models;

use Yii;
use yii\caching\TagDependency;

/**
 * This is the model class for table "{{%pages}}".
 *
 * @property int $id
 * @property string $slug
 * @property string $name
 * @property string $image
 * @property string $content
 * @property int $position
 * @property int $is_product
 * @property string $title
 * @property string $keywords
 * @property string $description
 * @property int $is_active
 */
class Pages extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%pages}}';
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => \yii\behaviors\SluggableBehavior::className(),
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
            [['name', 'content'], 'required'],
            [['image', 'content', 'description'], 'string'],
            [['position', 'is_product', 'is_active'], 'integer'],
            [['name', 'slug', 'title', 'keywords'], 'string', 'max' => 255],
            ['position', 'default', 'value' => 0],
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
            'content' => Yii::t('app', 'Content'),
            'position' => Yii::t('app', 'Position'),
            'is_product' => Yii::t('app', 'Product'),
            'title' => Yii::t('app', 'Title'),
            'keywords' => Yii::t('app', 'Keywords'),
            'description' => Yii::t('app', 'Description'),
            'is_active' => Yii::t('app', 'Active')
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        TagDependency::invalidate(Yii::$app->cache, 'pages');
        
        return parent::afterSave($insert, $changedAttributes);
    }
    
    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
        TagDependency::invalidate(Yii::$app->cache, 'pages');
        
        parent::afterDelete();
    }
}