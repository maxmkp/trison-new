<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "accident".
 *
 * @property integer $id
 * @property string $acc_id
 * @property string $act_number
 * @property string $act_date
 * @property string $priority
 * @property integer $store_id
 * @property integer $equipment_id
 * @property string $fault
 */
class Accident extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'accident';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['act_date', 'close_date'], 'safe'],
            [['store_id', 'equipment_id'], 'integer'],
            [['note'], 'string'],
            [['acc_id', 'act_number', 'priority', 'fault'], 'string', 'max' => 255],
            [['acc_status'], 'string', 'max' => 20],
        ];
    }

    public function getAccountantUser(){
        return $this->hasOne(User::className(), ['id'=>'accountant']);
    }

    public function getResponsibleUser(){
        return $this->hasOne(User::className(), ['id'=>'responsible']);
    }

    public function getLogs(){
        return $this->hasMany(Logs::className(), ['accident_id'=>'id']);
    }

//    public function getLogsUser(){
//        return $this->hasOne(User::className(), ['id'=>'user_id'])->via('logs');
//    }
//
//    public function getLogsWorks(){
//        return $this->hasOne(Works::className(), ['id'=>'work_id'])->via('logs');
//    }

    public function getStores(){
        return $this->hasOne(Stores::className(), ['id'=>'store_id']);
    }

    public function getCities(){
        return $this->hasOne(Cities::className(), ['id'=>'city_id'])->via('stores');
    }

//    public function getCountries(){
//        return $this->hasOne(Countries::className(), ['id'=>'country_id'])->via('cities');
//    }

    public function getEquipment(){
        return $this->hasOne(Equipment::className(), ['id'=>'equipment_id']);
    }

    public function getWorks(){
        return $this->hasMany(Works::className(), ['accident_id'=>'id']);
    }

    public function getExecutorUser(){
        return $this->hasMany(Engineers::className(), ['id'=>'engineer_id'])->via('works');
    }

    public function getFiles(){
        return $this->hasMany(Files::className(), ['link_id'=>'id'])->andWhere(['table_name' => 'accident']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'acc_id' => 'Accident ID',
            'act_number' => 'Act Number',
            'act_date' => 'Act Date',
            'priority' => 'Priority',
            'store_id' => '..Store ID',
            'equipment_id' => '..Equipment ID',
            'fault' => 'Fault',
            'note' => 'Заметки',
            'act_pdf' => 'PDF',
            'pics' => 'Изображения',
            'acc_status' => 'Статус'
        ];
    }

    static function setDates($model){
        if (!empty($model->act_date)) {
            $ex_date = explode('.', $model->act_date);
            $model->act_date = $ex_date[2] . $ex_date[1] . $ex_date[0];
        }

        return $model;
    }

    static function takingPart($model) {
        $users = array();
        if (!empty($model->responsibleUser)) $users[$model->responsibleUser->id] = $model->responsibleUser->username;
        if (!empty($model->accountantUser)) $users[$model->accountantUser->id] = $model->accountantUser->username;

        return $users;
//        if
    }

    static function checkStatus($model) {
        $user = User::findCurrentUser();
        $arChange['status']['from'] = $model->status;

        if (($model->status != 'ОШИБОЧНО')&&($model->status != 'ЗАКРЫТО')&&($model->status != 'ВЫГРУЖЕНО')&&($model->status != 'ПРОВЕДЕНО')) {
            if (self::checkForINWORKStatus($model)) {
                if ($model->status != 'В РАБОТЕ') $model = self::setStatus($model, 'В РАБОТЕ');
            } elseif ($model->status != 'ОТКРЫТА') {
                $model = self::setStatus($model, 'ОТКРЫТА');
            }
        }

        $arChange['status']['to'] = $model->status;
        if ($arChange['status']['from'] != $arChange['status']['to']) {
            $model->save();
            Logs::createLog('ACCIDENT CHANGE STATUS', $user->id, $arChange, $model->id);
        }

        return $model;
    }
    static function checkForINWORKStatus($model) {
        $acc_id = $model->id;

        $flag = true;

        $works = Works::find()->where(['acc_id' => $acc_id])->all();
        foreach ($works as $work) {
            if (empty($work->engineer_id)) $flag = false;
        }

        return $flag;

    }
    static function setStatus($model, $status=false) {

        if(!empty($status)) {
            $model->acc_status = $status;
        } else {
            if (empty($model->acc_status)) {
                $model->acc_status = 'CLOSED';
            } elseif ($model->acc_status == 'CLOSED') {
                $model->acc_status = 'OPEN';
            } else $model->acc_status = 'CLOSED';
        }

        $model->save();
    }
}
