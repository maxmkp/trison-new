<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "stores".
 *
 * @property integer $id
 * @property integer $inner_id
 * @property integer $city_id
 * @property string $store_name
 * @property string $address
 * @property string $tel1
 * @property string $dept1
 * @property string $note1
 * @property string $tel2
 * @property string $dept2
 * @property string $note2
 * @property string $tel3
 * @property string $dept3
 * @property string $note3
 * @property string $tel4
 * @property string $dept4
 * @property string $note4
 * @property string $tel5
 * @property string $dept5
 * @property string $note5
 * @property string $end_of_work
 * @property string $pics
 */
class Stores extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'stores';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['inner_id', 'city_id'], 'integer'],
            [['store_name', 'address', 'tel1', 'dept1', 'note1', 'tel2', 'dept2', 'note2', 'tel3', 'dept3', 'note3', 'tel4', 'dept4', 'note4', 'tel5', 'dept5', 'note5'], 'string', 'max' => 255],
        ];
    }

    public function getCities(){
        return $this->hasOne(Cities::className(), ['id'=>'city_id']);
    }

    public function getEquipment(){
        return $this->hasMany(Equipment::className(), ['store_id'=>'id']);
    }

//    public function getFiles(){
//        return $this->hasMany(Files::className(), ['link_id'=>'id'])->andWhere(['type' => 'stores']);
//    }
    public function getFiles(){
        return $this->hasMany(Files::className(), ['link_id'=>'id'])->andWhere(['table_name' => 'stores']);
    }

    public function getAccident(){
        return $this->hasMany(Accident::className(), ['store_id'=>'id']);
    }

    public function getWorks(){
        return $this->hasMany(Works::className(), ['accident_id'=>'id'])->via('accident');
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'inner_id' => 'Inner ID',
            'city_id' => '..City ID',
            'store_name' => 'Store Name',
            'address' => 'address',
            'tel1' => 'tel1',
            'dept1' => 'dept1',
            'note1' => 'note1',
            'tel2' => 'tel2',
            'dept2' => 'dept2',
            'note2' => 'note2',
            'tel3' => 'tel3',
            'dept3' => 'dept3',
            'note3' => 'note3',
            'tel4' => 'tel4',
            'dept4' => 'dept4',
            'note4' => 'note4',
            'tel5' => 'tel5',
            'dept5' => 'dept5',
            'note5' => 'note5',
            'end_of_work' => 'End Of Work',
            'pics' => 'Картинки',
        ];
    }
}
