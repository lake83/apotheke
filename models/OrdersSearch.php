<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Orders;

/**
 * OrdersSearch represents the model behind the search form of `app\models\Orders`.
 */
class OrdersSearch extends Orders
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'coupon_id', 'delivery_id', 'region', 'payment_id', 'status'], 'integer'],
            [['updated_at'], 'date', 'format' => 'd.m.Y'],
            [['name', 'surname', 'products', 'number', 'phone', 'email', 'street', 'city', 'postcode', 'comment', 'created_at', 'host', 'referrer', 'ip', 'agent', 'cookie_id', 'language'], 'safe'],
            [['sum', 'delivery_sum'], 'number']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Orders::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['created_at' => SORT_DESC]]
        ]);
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        if ($this->created_at && $this->created_at !== ' ') {
            $created = explode('-', $this->created_at);
            $query->andFilterWhere(['>=', 'created_at', strtotime($created[0] . ' 00:00:00')])
                ->andFilterWhere(['<', 'created_at', strtotime($created[1] . ' 23:59:59')]);
        }
        $query->andFilterWhere([
            'id' => $this->id,
            'sum' => $this->sum,
            'coupon_id' => $this->coupon_id,
            'delivery_id' => $this->delivery_id,
            'delivery_sum' => $this->delivery_sum,
            'region' => $this->region,
            'payment_id' => $this->payment_id,
            'status' => $this->status,
            'FROM_UNIXTIME(updated_at, "%d.%m.%Y")' => $this->updated_at
        ]);
        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'surname', $this->surname])
            ->andFilterWhere(['like', 'products', $this->products])
            ->andFilterWhere(['like', 'number', $this->number])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'street', $this->street])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'postcode', $this->postcode])
            ->andFilterWhere(['like', 'comment', $this->comment])
            ->andFilterWhere(['like', 'host', $this->host])
            ->andFilterWhere(['like', 'referrer', $this->referrer])
            ->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'agent', $this->agent])
            ->andFilterWhere(['like', 'cookie_id', $this->cookie_id])
            ->andFilterWhere(['like', 'language', $this->language]);

        return $dataProvider;
    }
}