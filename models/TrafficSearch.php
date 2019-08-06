<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Traffic;

/**
 * TrafficSearch represents the model behind the search form of `app\models\Traffic`.
 */
class TrafficSearch extends Traffic
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['id', 'integer'],
            ['created_at', 'date', 'format' => 'd.m.Y'],
            [['host', 'referrer', 'ip', 'agent', 'cookie_id', 'language'], 'safe']
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
        $query = Traffic::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            'id' => $this->id,
            'FROM_UNIXTIME(created_at, "%d.%m.%Y")' => $this->created_at
        ]);
        $query->andFilterWhere(['like', 'host', $this->host])
            ->andFilterWhere(['like', 'referrer', $this->referrer])
            ->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'agent', $this->agent])
            ->andFilterWhere(['like', 'cookie_id', $this->cookie_id])
            ->andFilterWhere(['like', 'language', $this->language]);

        return $dataProvider;
    }
}