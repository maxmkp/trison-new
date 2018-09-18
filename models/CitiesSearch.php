<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Cities;

/**
 * CitiesSearch represents the model behind the search form about `app\models\Cities`.
 */
class CitiesSearch extends Cities
{
    public $stores_store_name;
    public $engineers_name;
    public $countries_name;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'stores_store_name', 'engineers_name', 'countries_name'], 'safe'],
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
        $query = Cities::find();

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

        $query->joinWith('stores');
        $query->joinWith('engineers');
        $query->joinWith('countries');

//        $dataProvider->sort->attributes['stores_store_name'] = [
//            'asc' => ['stores.store_name' => SORT_ASC],
//            'desc' => ['stores.store_name' => SORT_DESC],
//            'default' => SORT_DESC,
//        ];
//        $dataProvider->sort->attributes['engineers_name'] = [
//            'asc' => ['engineers.name' => SORT_ASC],
//            'desc' => ['engineers.name' => SORT_DESC],
//            'default' => SORT_DESC,
//        ];

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'cities.name', $this->name])
        ->andFilterWhere(['like', 'stores.store_name', $this->stores_store_name])
        ->andFilterWhere(['like', 'engineers.name', $this->engineers_name])
        ->andFilterWhere(['like', 'countries.name', $this->countries_name]);

        return $dataProvider;
    }
}
