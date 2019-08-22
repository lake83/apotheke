<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Json;

/**
 * This is the model class for table "orders".
 *
 * @property int $id
 * @property string $name
 * @property string $surname
 * @property string $phone
 * @property string $email
 * @property string $street
 * @property string $city
 * @property string $region
 * @property string $postcode
 * @property string $products
 * @property string $number
 * @property double $sum
 * @property int $coupon_id
 * @property int $delivery_id
 * @property double $delivery_sum
 * @property int $payment_id
 * @property string $comment
 * @property string $host
 * @property string $referrer
 * @property string $ip
 * @property string $agent
 * @property string $cookie_id
 * @property string $language
 * @property int $status 1-new,2-processing,3-closed,4-canceled
 * @property int $created_at
 * @property int $updated_at
 */
class Orders extends \yii\db\ActiveRecord
{
    const STATUS_NEW = 1;
    const STATUS_PROCESSING = 2;
    const STATUS_CLOSED = 3;
    const STATUS_CANSELED = 4;
    
    public $coupon;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orders';
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className()
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'surname', 'phone', 'email', 'street', 'city', 'region', 'postcode', 'delivery_id', 'payment_id'], 'required'],
            [['coupon_id', 'payment_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['sum', 'delivery_sum'], 'number'],
            ['email', 'email'],
            ['number', 'unique'],
            [['coupon_id', 'delivery_sum'], 'default', 'value' => 0],
            [['name', 'surname', 'street', 'city', 'region', 'referrer'], 'string', 'max' => 255],
            [['postcode', 'number'], 'string', 'max' => 12],
            ['phone', 'string', 'max' => 20],
            [['email', 'host', 'agent'], 'string', 'max' => 100],
            [['ip', 'language'], 'string', 'max' => 30],
            ['cookie_id', 'string', 'max' => 50],
            [['products', 'comment'], 'string'],
            ['status', 'default', 'value' => self::STATUS_NEW],
            [['name', 'surname', 'street', 'city', 'region', 'postcode', 'comment'], 'filter', 'filter' => function ($value) {
                return \yii\helpers\HtmlPurifier::process($value);
            }],
            ['coupon', function ($attribute, $params, $validator) {
                $coupon = Coupon::find()->select(['id'])->where(['code' => $this->$attribute, 'is_active' => 1])
                    ->andWhere(['<', 'date_from', time()])->andWhere(['>', 'date_to', time()])->scalar();
                if (!$coupon) {
                    $this->addError($attribute, Yii::t('main', 'Invalid coupon code.'));
                } else {
                    $this->coupon_id = $coupon;
                }
            }]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => Yii::t('main', 'Name'),
            'surname' => Yii::t('main', 'Surname'),
            'phone' => Yii::t('main', 'Phone'),
            'email' => 'E-Mail',
            'street' => Yii::t('main', 'Street'),
            'city' => Yii::t('main', 'City'),
            'region' => Yii::t('main', 'Region'),
            'postcode' => Yii::t('main', 'Postcode'),
            'products' => Yii::t('app', 'Products'),
            'number' => Yii::t('main', 'Number'),
            'sum' => Yii::t('main', 'Sum'),
            'coupon_id' => Yii::t('main', 'Coupon'),
            'coupon' => Yii::t('main', 'Coupon'),
            'delivery_id' => Yii::t('main', 'Delivery'),
            'delivery_sum' => Yii::t('main', 'Delivery Sum'),
            'payment_id' => Yii::t('main', 'Payment'),
            'comment' => Yii::t('main', 'Comment'),
            'host' => Yii::t('app', 'Host'),
            'referrer' => Yii::t('app', 'Referrer'),
            'ip' => 'IP',
            'agent' => Yii::t('app', 'Agent'),
            'cookie_id' => Yii::t('app', 'Cookie ID'),
            'language' => Yii::t('app', 'Language'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created'),
            'updated_at' => Yii::t('app', 'Updated')
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if ($insert) {
            $request = Yii::$app->request;
            $cart = Yii::$app->session->get('cart');
            $sum = $cart['sum'];
            
            $this->products = Json::encode($cart['products']);
            $this->number = mt_rand(100000, 999999);
            $this->delivery_sum = $this->delivery->free_sum < $sum ? 0 : $this->delivery->price;
            
            if (!empty($this->coupon_id) && ($this->coupon = Coupon::findOne($this->coupon_id))) {
                if ($this->coupon->type == Coupon::TYPE_SUM) {
                    $sum-= $this->coupon->value;
                } else {
                    $sum = $sum - (($this->coupon->value*$sum)/100);
                }
            }
            $this->sum = round(($sum + $this->delivery_sum), 2);
            
            $this->host = $request->absoluteUrl;
            $this->referrer = $request->referrer;
            $this->ip = $request->userIP;
            $this->agent = $request->userAgent;
            $this->cookie_id = $request->cookies['_csrf']->value;
            $this->language = implode(',', $request->acceptableLanguages);
        }
        return parent::beforeSave($insert);
    }
    
    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            Yii::$app->session->remove('cart');
            
            if (!empty($this->coupon_id)) {
                $this->coupon->is_active = 0;
                $this->coupon->save();
            }
        }
        return parent::afterSave($insert, $changedAttributes);
    }
    
    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        $this->products = Json::decode($this->products);
        
        parent::afterFind();
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCouponData()
    {
        return $this->hasOne(Coupon::className(), ['id' => 'coupon_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDelivery()
    {
        return $this->hasOne(Delivery::className(), ['id' => 'delivery_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayment()
    {
        return $this->hasOne(Payment::className(), ['id' => 'payment_id']);
    }
    
    /**
     * Returns a list of order statuses or name
     * 
     * @param integer $key key in an array of names
     * @return mixed
     */
    public static function getStatuses($key = null)
    {
        $array = [
            self::STATUS_NEW => Yii::t('app', 'New'),
            self::STATUS_PROCESSING => Yii::t('app', 'Processing'),
            self::STATUS_CLOSED => Yii::t('app', 'Closed'),
            self::STATUS_CANSELED => Yii::t('app', 'Canceled')
        ];
        return is_null($key) ? $array : $array[$key];
    }
}