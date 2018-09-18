<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "logs".
 *
 * @property int $accident_id
 * @property int $work_id
 * @property int $user_id
 * @property string $text
 * @property string $created
 */
class Logs extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'logs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'act_type'], 'required'],
            [['accident_id', 'work_id', 'user_id'], 'integer'],
            [['text', 'fields_json'], 'string'],
            [['created'], 'safe'],
        ];
    }

    public function getUser(){
        return $this->hasOne(User::className(), ['id'=>'user_id']);
    }

    public function getWorks(){
        return $this->hasOne(Works::className(), ['id'=>'work_id']);
    }

    public function getAccident(){
        return $this->hasOne(Accident::className(), ['id'=>'accident_id']);
    }

    public function getFiles(){
        return $this->hasMany(Files::className(), ['link_id'=>'id'])->andWhere(['table_name' => 'logs']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'accident_id' => 'Accident ID',
            'work_id' => 'Work ID',
            'user_id' => 'User ID',
            'act_type' => 'Type of the action',
            'text' => 'Text',
            'fields_json' => 'Fields',
            'created' => 'Created',
        ];
    }

    static function getLastChanges($model)
    {
        $arChanges = [];

        foreach ($model as $nameAttribute => $valueAttribute) {
            if (!empty($valueAttribute)) $arChanges[$nameAttribute] = array('from' => $valueAttribute, 'to' => '');
        }

        return $arChanges;
    }

    static function getChanges($model)
    {
        $arChanges = [];

        if(!empty($model->getOldAttributes())) {
            $oldAttributes = $model->getOldAttributes();

            foreach ($oldAttributes as $nameAttribute => $valueAttribute) {
                if (strpos($nameAttribute, '_date') !== false) {
                    if (str_replace('-', '', $valueAttribute) != $model->$nameAttribute) $arChanges[$nameAttribute] = array('from' => $valueAttribute, 'to' => $model->$nameAttribute);
                } elseif ($valueAttribute != $model->$nameAttribute) $arChanges[$nameAttribute] = array('from' => $valueAttribute, 'to' => $model->$nameAttribute);
            }
        } else {
            foreach ($model as $nameAttribute => $valueAttribute ) {
                if (!empty($valueAttribute)) $arChanges[$nameAttribute] = array('from' => '', 'to' => $valueAttribute);
            }
        }

        return $arChanges;
    }


    static function createLog($act_type='UNKNOWN', $user_id, $arLog, $accident_id=false, $work_id=false)
    {
        $modelLog = new Logs();
        if($accident_id!==false) $modelLog->accident_id=$accident_id;
        if($work_id!==false) $modelLog->work_id=$work_id;
        $modelLog->act_type=$act_type;
        $modelLog->user_id=$user_id;
        $modelLog->fields_json=json_encode($arLog);
//отправка бухгалтеру и т.п по т.з.
        if ($modelLog->save()) return true;
        else return false;
    }

    static function createMessage($user_id, $text, $accident_id=false, $work_id=false, $act_type='MESSAGE', $recipients=false, $recipients_eng=false, $files=false)
    {
        $modelLog = new Logs();

        if(!empty($text)) {
            if($accident_id!==false) $modelLog->accident_id=$accident_id;
            if($work_id!==false) $modelLog->work_id=$work_id;
            if($recipients!==false) $modelLog->recipients=$recipients;
            if($recipients_eng!==false) $modelLog->recipients_eng=$recipients_eng;
            $modelLog->act_type=$act_type;
            $modelLog->user_id=$user_id;
            $modelLog->text=$text;

            if ($modelLog->save()) {
                if (!empty($files)) $files_uploaded = Files::saveFiles('logs', $modelLog->id, $files);
                else $files_uploaded=false;

                if (!empty($recipients)) self::sendModelLog($modelLog, $files_uploaded);
//                отправка сообщения
                return true;
            } else return 'error:text==Сообщение не отправлено';
        } else return 'error:text==Отсутствует текст';
    }

    static function createLogCancel($user_id, $text, $accident_id=false, $work_id=false, $act_type='CANCELLED: ', $arLog, $files=false)
    {
        $modelLog = new Logs();

        if(!empty($text)) {
            if($accident_id!==false) $modelLog->accident_id=$accident_id;
            if($work_id!==false) { $modelLog->work_id=$work_id; $act_type.='WORK'; }
            else { $act_type.='ACCIDENT'; }
            $modelLog->act_type=$act_type;
            $modelLog->user_id=$user_id;
            $modelLog->text=$text;
            $modelLog->fields_json=json_encode($arLog);

            if ($modelLog->save()) {
                if (!empty($files)) $files_uploaded = Files::saveFiles('logs', $modelLog->id, $files);
                else $files_uploaded=false;

//                if (!empty($recipients)) self::sendModelLog($modelLog, $files_uploaded);
//отправка бухгалтеру и т.п по т.з.
                return true;
            } else return 'error:text==Сообщение не отправлено';
        } else return 'error:text==Отсутствует текст';
    }

    static function sendModelLog($modelLog, $files_uploaded) {
        $recipients = explode(',', $modelLog->recipients);
        $text = '';
        $user_from = User::findIdentity($modelLog->user_id);
        $text .= 'От: '.$user_from->username.'('.$user_from->email.')';
        $text .= '<br>';
        $text .= 'Тип события: '.$modelLog->act_type;
        $text .= '<br>';
        if (!empty($modelLog->fields_json)) {
            $changes_obj = json_decode($modelLog->fields_json);

            if (!empty($changes_obj)) {

                $text .= '<table><thead style="font-weight:bold"><tr><td>Field</td><td>From</td><td>To</td></tr></thead><tbody>';
                foreach ($changes_obj as $field_name => $field_values) {
                    $text .= '<tr><td>' . $field_name . '</td><td>' . $field_values->from . '</td><td>' . $field_values->to . '</td>';
                }

                $text .= '</tbody></table>';
            }

            $text .= '<br>';
        }
        if (!empty($modelLog->text)) {
            $text .= 'Текст: '.$modelLog->text;
            $text .= '<br>';
        }
        if (!empty($files_uploaded)) {
            foreach ($files_uploaded as $file_uploaded) {
                $text .= $file_uploaded['to'];
                $text .= '<br>';
            }
        }

        foreach ($recipients as $recipient) {
            $recipient_user = User::findIdentity($recipient);

            if (!empty($recipient_user->email)) {
                Yii::$app->mailer->compose()
                    ->setTo($recipient_user->email)
                    ->setFrom(['trison@banki-poisk.ru' => 'Trison'])
                    ->setSubject('Новое '.$modelLog->act_type.' на сайте')
                    ->setHtmlBody($text)
                    ->send();
            }
        }
    }

//    public static function findByAccident($accident_id=false, $work_id=false)
//    {
//        if ($accident_id!=false) return static::find(['accident_id' => $accident_id])->all();
//        elseif ($work_id!=false) return static::find(['work_id' => $work_id])->all();
//        else return false;
//    }
}
