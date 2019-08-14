<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * This is the model class for table "orders".
 *
 * @property int $id
 * @property string $name
 * @property string $phone
 * @property string $address
 * @property string $products
 * @property string $number
 * @property double $sum
 * @property int $coupon_id
 * @property int $delivery_id
 * @property double $delivery_sum
 * @property int $payment_id
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
            [['name', 'phone', 'address', 'delivery_id', 'payment_id'], 'required'],
            [['coupon_id', 'delivery_id', 'payment_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['sum', 'delivery_sum'], 'number'],
            [['coupon_id', 'delivery_sum'], 'default', 'value' => 0],
            [['name', 'address', 'referrer'], 'string', 'max' => 255],
            ['number', 'string', 'max' => 12],
            ['phone', 'string', 'max' => 20],
            [['host', 'agent'], 'string', 'max' => 100],
            [['ip', 'language'], 'string', 'max' => 30],
            ['cookie_id', 'string', 'max' => 50],
            ['products', 'string'],
            ['status', 'default', 'value' => self::STATUS_NEW],
            ['coupon', 'string', 'whenClient' => "function (attribute, value) {
                if (value != '') {
                    deferred.push($.post('" . Url::to(['shop/check-coupon']) . "', {number: value}).done(function(data) {
                        if (data.status == 'error') {
                            messages.push(data.message);
                        } else {
                            $('a.cart strong').text(data.sum);
                            $('td.total').text(data.sum + ' €');
                            $('#orders-coupon_id').val(data.coupon_id);
                            return true;
                        }
                    }));
                }
                $('a.cart strong').text(" . ($sum = Yii::$app->session->get('cart')['sum']) . ");
                $('td.total').text(" . $sum . " + ' €');
                $('#orders-coupon_id').val('');
                return false;
            }"]
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
            'phone' => Yii::t('main', 'Phone'),
            'address' => Yii::t('main', 'Address'),
            'products' => Yii::t('app', 'Products'),
            'number' => Yii::t('app', 'Number'),
            'sum' => Yii::t('app', 'Sum'),
            'coupon_id' => Yii::t('app', 'Coupon'),
            'coupon' => Yii::t('main', 'Coupon'),
            'delivery_id' => Yii::t('main', 'Delivery'),
            'delivery_sum' => Yii::t('app', 'Delivery Sum'),
            'payment_id' => Yii::t('main', 'Payment'),
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
            
            if (!empty($this->coupon_id) && ($this->coupon = Coupon::findOne($this->coupon_id))) {
                if ($this->coupon->type == Coupon::TYPE_SUM) {
                    $sum-= $this->coupon->value;
                } else {
                    $sum = $sum - (($this->coupon->value*$sum)/100);
                }
            }
            $this->sum = round($sum, 2);
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