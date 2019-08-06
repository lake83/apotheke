<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "traffic".
 *
 * @property int $id
 * @property string $host
 * @property string $referrer
 * @property string $ip
 * @property string $agent
 * @property string $cookie_id
 * @property string $language
 * @property int $created_at
 */
class Traffic extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'traffic';
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp'  => [
                'class' => TimestampBehavior::className(),
                'updatedAtAttribute' => false
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['host', 'referrer', 'ip', 'agent', 'language'], 'required'],
            [['created_at'], 'integer'],
            [['host', 'agent'], 'string', 'max' => 100],
            ['cookie_id', 'string', 'max' => 50],
            [['referrer'], 'string', 'max' => 255],
            [['ip', 'language'], 'string', 'max' => 30]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'host' => Yii::t('app', 'Host'),
            'referrer' => Yii::t('app', 'Referrer'),
            'ip' => Yii::t('app', 'Ip'),
            'agent' => Yii::t('app', 'Agent'),
            'cookie_id' => Yii::t('app', 'Cookie ID'),
            'language' => Yii::t('app', 'Language'),
            'created_at' => Yii::t('app', 'Created'),
        ];
    }
}