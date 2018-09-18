<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Logs;

/**
 * LogsSearch represents the model behind the search form of `app\models\Logs`.
 */
class LogsSearch extends Logs
{
    /**
     * @inheritdoc
     */
    public $accident_acc_id;
    public $user_username;
//    public $works_;
    public function rules()
    {
        return [
            [['id', 'accident_id', 'work_id', 'user_id'], 'integer'],
            [['text', 'fields_json', 'created', 'accident_acc_id', 'user_username', 'act_type'], 'safe'],
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
        $query = Logs::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=>[
                'defaultOrder'=>[
                    'created'=>SORT_DESC
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->joinWith('accident');
        $query->joinWith('user');

        $dataProvider->sort->attributes['accident_acc_id'] = [
            'asc' => ['accident.acc_id' => SORT_ASC],
            'desc' => ['accident.acc_id' => SORT_DESC],
            'default' => SORT_DESC,
        ];
        $dataProvider->sort->attributes['user_username'] = [
            'asc' => ['user.username' => SORT_ASC],
            'desc' => ['user.username' => SORT_DESC],
            'default' => SORT_DESC,
        ];

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'accident_id' => $this->accident_id,
            'work_id' => $this->work_id,
            'user_id' => $this->user_id,
            'created' => $this->created,
        ]);

        $query->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'fields_json', $this->fields_json])
            ->andFilterWhere(['like', 'act_type', $this->act_type])
            ->andFilterWhere(['like', 'accident.acc_id', $this->accident_acc_id])
            ->andFilterWhere(['like', 'user.username', $this->user_username]);

        return $dataProvider;
    }
}
