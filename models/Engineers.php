<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "engineers".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $name
 * @property string $tel1
 * @property string $tel2
 * @property string $email1
 * @property string $email2
 * @property string $url
 * @property string $company_name
 * @property integer $payment
 * @property string $note
 */
class Engineers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'engineers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'tel1', 'payment', 'payment_target', 'city_id'], 'required'],
            [['id', 'city_id', 'tariff'], 'integer'],
            [['note', 'payment'], 'string'],
            [['name', 'tel1', 'tel2', 'email1', 'email2', 'url', 'company_name', 'payment_target'], 'string', 'max' => 255],
        ];
    }

    public function getCities(){
        return $this->hasOne(Cities::className(), ['id'=>'city_id']);
    }
    public function getWorks(){
        return $this->hasMany(Works::className(), ['engineer_id'=>'id']);
    }
    public function getFiles(){
        return $this->hasMany(Files::className(), ['link_id'=>'id'])->andWhere(['table_name' => 'engineers']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'city_id' => '..City ID',
            'name' => 'Ф.И.О.',
            'tel1' => 'Телефон 1',
            'tel2' => 'Телефон 2',
            'email1' => 'E-mail 1',
            'email2' => 'E-mail 2',
            'url' => 'Url',
            'company_name' => 'Company name',
            'tariff' => 'Тариф, руб/час',
            'payment_target' => 'Оплата куда',
            'payment' => 'Реквизиты',
            'note' => 'Заметки',
        ];
    }
}
