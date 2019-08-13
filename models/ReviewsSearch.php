<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Reviews;

/**
 * ReviewsSearch represents the model behind the search form of `app\models\Reviews`.
 */
class ReviewsSearch extends Reviews
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'is_active'], 'integer'],
            ['created_at', 'date', 'format' => 'd.m.Y'],
            [['name', 'email', 'ip', 'text'], 'safe']
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
        $query = Reviews::find();

        if (isset($params['front'])) {
            $query->where(['is_active' => 1])->orWhere(['ip' => Yii::$app->request->userIP]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['created_at' => SORT_ASC]]
        ]);
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            'id' => $this->id,
            'is_active' => $this->is_active,
            'FROM_UNIXTIME(created_at, "%d.%m.%Y")' => $this->created_at
        ]);
        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'text', $this->text]);

        return $dataProvider;
    }
}