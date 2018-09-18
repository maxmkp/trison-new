<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * This is the model class for table "works".
 *
 * @property integer $id
 * @property integer $accident_id
 * @property string $start_date
 * @property string $completion_date
 * @property string $pause_date
 * @property string $reason
 * @property string $end_pause_date
 * @property string $act_time
 * @property string $real_time
 * @property string $full_work_performed
 * @property integer $engineer_id
 * @property string $status
 * @property integer $payment
 * @property string $note
 * @property string $act_pdf
 * @property string $act_scan
 * @property string $pics
 */
class Works extends \yii\db\ActiveRecord
{
    public $worker;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'works';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['accident_id', 'worker', 'payment', 'workers_number'], 'integer'],
            [['start_date', 'completion_date', 'pause_date', 'end_pause_date'], 'safe'],
            [['reason', 'full_work_performed', 'note'], 'string'],
            [['act_time', 'act_time_hour', 'act_time_hour_begin', 'act_time_hour_end', 'real_time', 'status', 'summary', 'rate', 'own_equip_sum'], 'string', 'max' => 255],
        ];
    }

    public function getAccident(){
        return $this->hasOne(Accident::className(), ['id'=>'accident_id']);
    }

    public function getWorkers(){
        return $this->hasMany(Workers::className(), ['wor_id'=>'id']);
    }

    public function getEngineers(){
//        return $this->hasOne(Engineers::className(), ['id'=>'engineer_id']);
        return $this->hasOne(Engineers::className(), ['id'=>'eng_id'])->via('workers');
    }

    public function getStores(){
        return $this->hasOne(Stores::className(), ['id'=>'store_id'])->via('accident');
    }

    public function getCities(){
        return $this->hasOne(Cities::className(), ['id'=>'city_id'])->via('stores');
    }

    public function getFiles(){
        return $this->hasMany(Files::className(), ['link_id'=>'id'])->andWhere(['table_name' => 'works']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'accident_id' => '..Accident ID',
            'start_date' => 'Start date',
            'completion_date' => 'Completion Date',
            'pause_date' => 'Pause Date',
            'reason' => 'Reason',
            'end_pause_date' => 'End pause date',
            'act_time_hour_begin' => 'Act time hour of beginning',
            'act_time_hour_end' => 'Act time hour of ending',
            'act_time_hour' => 'Act time hour',
            'act_time' => 'Act time',
            'real_time' => 'Real Time',
            'full_work_performed' => 'Full work performed',
            'engineer_id' => '..Engineer ID',
            'status' => 'Status',
            'payment' => 'Payment',
            'summary' => 'Сумма оплаты',
            'rate' => 'Курс валюты',
            'workers_number' => 'Число рабочих',
            'own_equip_sum' => 'Сумма собственного оборудования, руб.',
            'note' => 'Заметки',
            'act_pdf' => 'PDF акта',
            'act_scan' => 'Скан акта',
            'pics' => 'Изображения',
            'full_price' => 'Сумма, руб'
        ];
    }

    static function setDates($model){
        if (!empty($model->start_date)) {
            $ex_date=explode('.', $model->start_date);
            $model->start_date=$ex_date[2].$ex_date[1].$ex_date[0];
        }
        if (!empty($model->completion_date)) {
            $ex_date=explode('.', $model->completion_date);
            $model->completion_date=$ex_date[2].$ex_date[1].$ex_date[0];
        }
        if (!empty($model->pause_date)) {
            $ex_date=explode('.', $model->pause_date);
            $model->pause_date=$ex_date[2].$ex_date[1].$ex_date[0];
        }
        if (!empty($model->end_pause_date)) {
            $ex_date=explode('.', $model->end_pause_date);
            $model->end_pause_date=$ex_date[2].$ex_date[1].$ex_date[0];
        }

        return $model;
    }

    static function setStatus($model, $status=false){

        $carried_out = false;
//        if ($acc_model!==false) {
        if (!empty($model->accident->status)) {
            if (($model->accident->status == 'ПРОВЕДЕНО') || ($model->accident->status == 'ВЫГРУЖЕНО')) $carried_out = true;
        }
//        }
        if (!$carried_out) {
            if (!empty($status)) $model->status = $status;
            else {
                if (!empty($model->payment)) {
                    if ($model->payment == 1) $model->status = 'ОПЛАЧЕНА';
                    elseif ($model->payment == 10) $model->status = 'ОЖИДАЕТ ОПЛАТЫ';
                } else {
//                if (!empty($acc_model->id)) {
//                    $acc_model->acc_status = 'ОТКРЫТА';
//                    $acc_model->save();
//                }

                    if (!empty($model->completion_date)) {
                        $model->status = 'ЗАВЕРШЕНА';
                    } elseif ((!empty($model->pause_date)) && (empty($model->end_pause_date))) {
                        $model->status = 'ПАУЗА';
                    } elseif ((!empty($model->start_date)) && (!empty($model->engineer_id))) {
                        $model->status = 'НАЗНАЧЕНА';
                    } elseif (!empty($model->start_date)) {
                        $model->status = 'ПРИНЯТА';
                    } else {
                        $model->status = 'ВНЕСЕНА';
                    }
                }
            }
        }
        return $model;
    }

    static function takingPart($model) {
        $users = array();
        if (!empty($model->accident->responsibleUser)) $users[$model->accident->responsibleUser->id] = $model->accident->responsibleUser->username;
        if (!empty($model->accident->accountantUser)) $users[$model->accident->accountantUser->id] = $model->accident->accountantUser->username;

        return $users;
//        if
    }

    static function checkStatus($model) {
        $user = User::findCurrentUser();
        if (!empty($model->accident_id)) $acc_id=$model->accident_id;
        else $acc_id = false;

        $arChange['status']['from'] = $model->status;

        $model = self::setStatus($model);

        $arChange['status']['to'] = $model->status;
        if ($arChange['status']['from'] != $arChange['status']['to']) {
            $model->save();
            Logs::createLog('WORKS CHANGE STATUS', $user->id, $arChange, $acc_id, $model->id);
        }

        if (!empty($acc_id)) {
            $acc_model = Accident::find(['id' => $acc_id])->one();
            Accident::checkStatus($acc_model);
        }


//        проверка/установка статуса работы

        return $model;
    }

    static function what_day ( $day ) {
        $date_array = explode('-', $day);

        $xml_dates = simplexml_load_file('http://xmlcalendar.ru/data/ru/'.$date_array[0].'/calendar.xml')->days->day;

        $day_of_week='';
        foreach ($xml_dates as $xml_date) {
            $xml_array_date=Works::xml2array($xml_date);
            if ($xml_array_date['@attributes']['d']==$date_array[1].'.'.$date_array[2]) {
                if ($xml_array_date['@attributes']['t']==1) $day_of_week='holiday';
                else $day_of_week='work';
            }
        }
        if (empty($day_of_week)) {
            if (date('w', strtotime($day))<=5) $day_of_week='work';
            else $day_of_week='holiday';
        }

        return $day_of_week;
    }

    static function what_rate ( $day )
    {
        $date_array = explode('-', $day);

        $xml_rates = simplexml_load_file('http://www.cbr.ru/scripts/XML_daily_eng.asp?date_req='.$date_array[2].'/'.$date_array[1].'/'.$date_array[0]);
        $rate_of_day=0;
        foreach ($xml_rates as $xml_rate) {
            $xml_array_rate = self::xml2array($xml_rate);
            if ($xml_array_rate['CharCode']=='EUR') {
                $rate_of_day=str_replace(',', '.', $xml_array_rate['Value'])/$xml_array_rate['Nominal'];
                break 1;
            }
        }

        return $rate_of_day;
    }

    static function xml2array ( $xmlObject, $out = array () )
    {
        foreach ( (array) $xmlObject as $index => $node )
            $out[$index] = ( is_object ( $node ) ) ? self::xml2array ( $node ) : $node;

        return $out;
    }

    static function actionCalculateAccidentWork($acc_id, $notSave = false) {

        $models = self::find()->where(['accident_id' => $acc_id])->all();

        if (!empty($models)) {
            foreach ($models as $model) {
                self::actionCalculateWork($model, $notSave);
            }
        }
        return true;

    }

    static function getCalculatedAccidentWork($acc_id) {
        $models = self::find()->where(['accident_id' => $acc_id])->all();
        $full_price = 0;
        if (!empty($models)) {
            foreach ($models as $model) {
                if (!empty($model->full_price)) $full_price+=$model->full_price;
                else {
                    return false;
                    break;
                }
            }
        }
        return $full_price;
    }

    static function getCalculatedAccidentWorkByAccidentModel($acc_model) {
        $full_price = 0;
        if (!empty($acc_model->works)) {
            foreach ($acc_model->works as $model) {
                if (!empty($model->full_price)) {
                    $full_price+=$model->full_price;
                    if (!empty($model->own_equip_sum)) $full_price+=$model->own_equip_sum;
                } else {
                    return false;
                    break;
                }
            }
        }
        return $full_price;
    }

    static function getHoursAccidentWorkByAccidentModel($acc_model) {
        $full_hours = 0;
        if (!empty($acc_model->works)) {
            foreach ($acc_model->works as $model) {
                if (!empty($model->act_time_hour)) $full_hours+=$model->act_time_hour;
                else {
                    return false;
                    break;
                }
            }
        }
        return $full_hours;
    }

    static function actionCalculateWork($model, $notSave = false) {

        if ((!empty($model->completion_date))&&(!empty($model->act_time_hour))) {
            $city = $model->cities;

            $what_day = self::what_day($model->completion_date);
            $what_rate = self::what_rate($model->completion_date);

            $summary = round($what_rate * ($model->act_time_hour * $city->{'rate_' . $what_day} + $city->{'rate_depart_' . $what_day}), 2);

            $model->rate = $what_rate;
            $model->full_price = $summary;

            if (!$notSave) {
                $model->save(false);
                return true;
            } else {
                return $model;
            }
        } else return false;

    }

}
