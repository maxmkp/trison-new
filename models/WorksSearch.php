<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Works;

/**
 * WorksSearch represents the model behind the search form about `app\models\Works`.
 */
class WorksSearch extends Works
{
    /**
     * @inheritdoc
     */
    public $accident_acc_id;
    public $engineers_name;
    public function rules()
    {
        return [
            [['id', 'accident_id', 'engineer_id', 'payment', 'workers_number'], 'integer'],
            [['engineers_name', 'accident_acc_id', 'start_date', 'completion_date', 'pause_date', 'reason', 'end_pause_date', 'act_time', 'act_time_hour', 'real_time', 'full_work_performed', 'status', 'note', 'act_pdf', 'act_scan', 'pics', 'summary', 'rate', 'own_equip_sum'], 'safe'],
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
        $query = Works::find();

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

        $query->joinWith('accident');
        $query->joinWith('engineers');
//        $query->joinWith('files');

        $dataProvider->sort->attributes['accident_acc_id'] = [
            'asc' => ['accident.acc_id' => SORT_ASC],
            'desc' => ['accident.acc_id' => SORT_DESC],
            'default' => SORT_DESC,
        ];
        $dataProvider->sort->attributes['engineers_name'] = [
            'asc' => ['engineers.name' => SORT_ASC],
            'desc' => ['engineers.name' => SORT_DESC],
            'default' => SORT_DESC,
        ];

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'start_date' => $this->start_date,
            'completion_date' => $this->completion_date,
            'pause_date' => $this->pause_date,
            'end_pause_date' => $this->end_pause_date,
            'payment' => $this->payment,
            'workers_number' => $this->workers_number,
            'summary' => $this->summary,
            'rate' => $this->rate,
            'own_equip_sum' => $this->own_equip_sum,
            'full_price' => $this->full_price,
        ]);

        $query->andFilterWhere(['like', 'reason', $this->reason])
            ->andFilterWhere(['like', 'act_time_hour', $this->act_time_hour])
            ->andFilterWhere(['like', 'act_time', $this->act_time])
            ->andFilterWhere(['like', 'real_time', $this->real_time])
            ->andFilterWhere(['like', 'full_work_performed', $this->full_work_performed])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'note', $this->note])
            ->andFilterWhere(['like', 'act_pdf', $this->act_pdf])
            ->andFilterWhere(['like', 'act_scan', $this->act_scan])
            ->andFilterWhere(['like', 'pics', $this->pics])
            ->andFilterWhere(['like', 'accident.acc_id', $this->accident_acc_id])
            ->andFilterWhere(['like', 'engineers.name', $this->engineers_name]);

        return $dataProvider;
    }
}
