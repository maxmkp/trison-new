<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Stores;

/**
 * StoresSearch represents the model behind the search form about `app\models\Stores`.
 */
class StoresSearch extends Stores
{
    /**
     * @inheritdoc
     */
    public $cities_name;
    public function rules()
    {
        return [
            [['id', 'inner_id', 'city_id'], 'integer'],
            [['store_name', 'address', 'tel1', 'dept1', 'note1', 'tel2', 'dept2', 'note2', 'tel3', 'dept3', 'note3', 'tel4', 'dept4', 'note4', 'tel5', 'dept5', 'note5', 'end_of_work', 'pics', 'cities_name'], 'safe'],
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
        $query = Stores::find();

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
        $query->joinWith('equipment');
        $query->joinWith('accident');

        $dataProvider->sort->attributes['cities_name'] = [
            'asc' => ['cities.name' => SORT_ASC],
            'desc' => ['cities.name' => SORT_DESC],
            'default' => SORT_DESC,
        ];

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'inner_id' => $this->inner_id,
        ]);

        $query->andFilterWhere(['like', 'store_name', $this->store_name])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'tel1', $this->tel1])
            ->andFilterWhere(['like', 'dept1', $this->dept1])
            ->andFilterWhere(['like', 'note1', $this->note1])
            ->andFilterWhere(['like', 'tel2', $this->tel2])
            ->andFilterWhere(['like', 'dept2', $this->dept2])
            ->andFilterWhere(['like', 'note2', $this->note2])
            ->andFilterWhere(['like', 'tel3', $this->tel3])
            ->andFilterWhere(['like', 'dept3', $this->dept3])
            ->andFilterWhere(['like', 'note3', $this->note3])
            ->andFilterWhere(['like', 'tel4', $this->tel4])
            ->andFilterWhere(['like', 'dept4', $this->dept4])
            ->andFilterWhere(['like', 'note4', $this->note4])
            ->andFilterWhere(['like', 'tel5', $this->tel5])
            ->andFilterWhere(['like', 'dept5', $this->dept5])
            ->andFilterWhere(['like', 'note5', $this->note5])
            ->andFilterWhere(['like', 'end_of_work', $this->end_of_work])
            ->andFilterWhere(['like', 'pics', $this->pics])
            ->andFilterWhere(['like', 'cities.name', $this->cities_name]);

        return $dataProvider;
    }
}
