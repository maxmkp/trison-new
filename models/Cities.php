<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cities".
 *
 * @property integer $id
 * @property string $name
 */
class Cities extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cities';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255],
        ];
    }

    public function getEngineers(){
        return $this->hasMany(Engineers::className(), ['city_id'=>'id']);
    }

    public function getStores(){
        return $this->hasMany(Stores::className(), ['city_id'=>'id']);
    }

    public function getCountries(){
        return $this->hasOne(Countries::className(), ['id'=>'country_id']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Наименование',
            'country_id' => 'Страна',
            'rate_work' => 'Тариф',
            'rate_hol' => 'Тариф(вых)',
            'rate_depart_work' => 'Тариф(выезд)',
            'rate_depart_hol' => 'Тариф(выезд,вых)',
        ];
    }
}
