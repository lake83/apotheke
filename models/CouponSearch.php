<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Coupon;

/**
 * CouponSearch represents the model behind the search form of `app\models\Coupon`.
 */
class CouponSearch extends Coupon
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'type', 'is_active'], 'integer'],
            [['date_from', 'date_to', 'created_at'], 'date', 'format' => 'd.m.Y'],
            [['value'], 'number'],
            [['name', 'code', 'value'], 'safe']
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
        $query = Coupon::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            'id' => $this->id,
            'type' => $this->type,
            'value' => $this->value,
            'FROM_UNIXTIME(date_from, "%d.%m.%Y")' => $this->date_from,
            'FROM_UNIXTIME(date_to, "%d.%m.%Y")' => $this->date_to,
            'FROM_UNIXTIME(created_at, "%d.%m.%Y")' => $this->created_at,
            'is_active' => $this->is_active,
        ]);
        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'code', $this->code]);

        return $dataProvider;
    }
}