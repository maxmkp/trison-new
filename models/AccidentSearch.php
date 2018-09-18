<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Accident;

/**
 * AccidentSearch represents the model behind the search form about `app\models\Accident`.
 */
class AccidentSearch extends Accident
{
    /**
     * @inheritdoc
     */
    public $stores_store_name;
    public $equipment_equip;
    public $cities_name;
    public $countries_name;
//    public $executorUser_username;
    public $responsibleUser_username;
    public $accountantUser_username;
    public function rules()
    {
        return [
            [['id', 'store_id', 'equipment_id'], 'integer'],
            [['acc_id', 'act_number', 'act_date', 'close_date', 'priority', 'fault', 'stores_store_name', 'equipment_equip', 'cities_name', 'countries_name', 'note', 'acc_status', 'executor', 'responsible', 'responsibleUser_username', 'accountantUser_username'], 'safe'],
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
        $query = Accident::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=>[
                'defaultOrder'=>[
                    'act_date'=>SORT_DESC
                ]
            ]
        ]);


        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->joinWith('stores');
        $query->joinWith('equipment');
        $query->joinWith('works');
        $query->joinWith('cities');
        $query->with('logs');
//        $query->with('countries');

//        $query->joinWith('executorUser AS executorUser');
        $query->joinWith('responsibleUser AS responsibleUser');
        $query->joinWith('accountantUser AS accountantUser');
//        $query->joinWith('logsUser AS logsUser');
//        $query->with('logsWorks AS logsWorks');

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
//        $dataProvider->sort->attributes['countries_name'] = [
//            'asc' => ['countries.name' => SORT_ASC],
//            'desc' => ['countries.name' => SORT_DESC],
//            'default' => SORT_DESC,
//        ];
        $dataProvider->sort->attributes['equipment_equip'] = [
            'asc' => ['equipment.equip' => SORT_ASC],
            'desc' => ['equipment.equip' => SORT_DESC],
            'default' => SORT_DESC,
        ];
//        $dataProvider->sort->attributes['executorUser_username'] = [
//            'asc' => ['executorUser.username' => SORT_ASC],
//            'desc' => ['executorUser.username' => SORT_DESC],
//            'default' => SORT_DESC,
//        ];
        $dataProvider->sort->attributes['responsibleUser_username'] = [
            'asc' => ['responsibleUser.username' => SORT_ASC],
            'desc' => ['responsibleUser.username' => SORT_DESC],
            'default' => SORT_DESC,
        ];
        $dataProvider->sort->attributes['accountantUser_username'] = [
            'asc' => ['accountantUser.username' => SORT_ASC],
            'desc' => ['accountantUser.username' => SORT_DESC],
            'default' => SORT_DESC,
        ];

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'act_date' => $this->act_date,
        ]);

        if ($params["not_acc_status"]) $query->andFilterWhere(['not like', 'acc_status', 'CANCELED']);

        $query->andFilterWhere(['like', 'acc_id', $this->acc_id])
            ->andFilterWhere(['like', 'act_number', $this->act_number])
            ->andFilterWhere(['like', 'priority', $this->priority])
            ->andFilterWhere(['like', 'fault', $this->fault])
            ->andFilterWhere(['like', 'note', $this->note])
            ->andFilterWhere(['like', 'close_date', $this->close_date])
            ->andFilterWhere(['like', 'responsible', $this->responsible])
            ->andFilterWhere(['like', 'acc_status', $this->acc_status])
            ->andFilterWhere(['like', 'stores.store_name', $this->stores_store_name])
            ->andFilterWhere(['like', 'cities.name', $this->cities_name])
            ->andFilterWhere(['like', 'equipment.equip', $this->equipment_equip])
            ->andFilterWhere(['like', 'responsibleUser.username', $this->responsibleUser_username])
            ->andFilterWhere(['like', 'accountantUser.username', $this->accountantUser_username]);
//            ->andFilterWhere(['like', 'countries.name', $this->countries_name]);
//            ->andFilterWhere(['like', 'executorUser.username', $this->executorUser_username]);

        return $dataProvider;
    }
}
