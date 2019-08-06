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
            [['id', 'product_id', 'delivery_id', 'payment_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'date', 'format' => 'd.m.Y'],
            [['name', 'phone', 'address', 'host', 'referrer', 'ip', 'agent', 'cookie_id', 'language'], 'safe'],
            [['sum', 'delivery_sum'], 'number'],
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
            'query' => $query
        ]);
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            'id' => $this->id,
            'product_id' => $this->product_id,
            'sum' => $this->sum,
            'delivery_id' => $this->delivery_id,
            'delivery_sum' => $this->delivery_sum,
            'payment_id' => $this->payment_id,
            'status' => $this->status,
            'FROM_UNIXTIME(created_at, "%d.%m.%Y")' => $this->created_at,
            'FROM_UNIXTIME(updated_at, "%d.%m.%Y")' => $this->updated_at
        ]);
        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'host', $this->host])
            ->andFilterWhere(['like', 'referrer', $this->referrer])
            ->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'agent', $this->agent])
            ->andFilterWhere(['like', 'cookie_id', $this->cookie_id])
            ->andFilterWhere(['like', 'language', $this->language]);

        return $dataProvider;
    }
}