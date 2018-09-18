<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Engineers;

/**
 * EngineersSearch represents the model behind the search form about `app\models\Engineers`.
 */
class EngineersSearch extends Engineers
{
    /**
     * @inheritdoc
     */
    public $cities_name;
    public function rules()
    {
        return [
            [['id', 'city_id'], 'integer'],
            [['name', 'tel1', 'tel2', 'email1', 'email2', 'url', 'company_name', 'note', 'tariff', 'payment_target', 'payment', 'cities_name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
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
        $query = Engineers::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->joinWith('cities');

        $dataProvider->sort->attributes['cities_name'] = [
            'asc' => ['cities.name' => SORT_ASC],
            'desc' => ['cities.name' => SORT_DESC],
            'default' => SORT_DESC,
        ];

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'tariff' => $this->tariff,
            'payment_target' => $this->payment_target,
            'payment' => $this->payment,
        ]);

        $query->andFilterWhere(['like', 'engineers.name', $this->name])
            ->andFilterWhere(['like', 'tel1', $this->tel1])
            ->andFilterWhere(['like', 'tel2', $this->tel2])
            ->andFilterWhere(['like', 'email1', $this->email1])
            ->andFilterWhere(['like', 'email2', $this->email2])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'company_name', $this->company_name])
            ->andFilterWhere(['like', 'note', $this->note])
            ->andFilterWhere(['like', 'cities.name', $this->cities_name]);

        return $dataProvider;
    }
}
