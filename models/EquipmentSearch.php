<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Equipment;

/**
 * EquipmentSearch represents the model behind the search form about `app\models\Equipment`.
 */
class EquipmentSearch extends Equipment
{
    /**
     * @inheritdoc
     */
    public $stores_store_name;
    public $cities_name;
    public function rules()
    {
        return [
            [['id', 'store_id'], 'integer'],
            [['equip', 'pc_name', 'player_id', 'net_cable_name', 'patch_port', 'switch_port', 'led_screen_fuse', 'pc_fuse', 'note', 'stores_store_name', 'cities_name'], 'safe'],
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
        $query = Equipment::find();

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
        $query->joinWith('cities');

        $dataProvider->sort->attributes['stores_store_name'] = [
            'asc' => ['stores.store_name' => SORT_ASC],
            'desc' => ['stores.store_name' => SORT_DESC],
            'default' => SORT_DESC,
        ];

        $dataProvider->sort->attributes['cities_name'] = [
            'asc' => ['cities.name' => SORT_ASC],
            'desc' => ['cities.name' => SORT_DESC],
            'default' => SORT_DESC,
        ];

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'equip', $this->equip])
            ->andFilterWhere(['like', 'pc_name', $this->pc_name])
            ->andFilterWhere(['like', 'player_id', $this->player_id])
            ->andFilterWhere(['like', 'net_cable_name', $this->net_cable_name])
            ->andFilterWhere(['like', 'patch_port', $this->patch_port])
            ->andFilterWhere(['like', 'switch_port', $this->switch_port])
            ->andFilterWhere(['like', 'led_screen_fuse', $this->led_screen_fuse])
            ->andFilterWhere(['like', 'pc_fuse', $this->pc_fuse])
            ->andFilterWhere(['like', 'note', $this->note])
            ->andFilterWhere(['like', 'stores.store_name', $this->stores_store_name])
            ->andFilterWhere(['like', 'cities.name', $this->cities_name]);

        return $dataProvider;
    }
}
