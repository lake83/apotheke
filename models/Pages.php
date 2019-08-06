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
 * @property string $content
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
            [['content', 'description'], 'string'],
            [['is_active'], 'integer'],
            [['name', 'slug', 'title', 'keywords'], 'string', 'max' => 255]
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
            'content' => Yii::t('app', 'Content'),
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