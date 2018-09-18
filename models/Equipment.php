<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "equipment".
 *
 * @property integer $id
 * @property string $equip
 * @property string $pc_name
 * @property string $player_id
 * @property string $net_cable_name
 * @property string $patch_port
 * @property string $switch_port
 * @property string $led_screen_fuse
 * @property string $pc_fuse
 * @property integer $store_id
 * @property string $note
 */
class Equipment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'equipment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id'], 'integer'],
            [['note'], 'string'],
            [['equip', 'pc_name', 'player_id', 'net_cable_name', 'patch_port', 'switch_port', 'led_screen_fuse', 'pc_fuse'], 'string', 'max' => 255],
        ];
    }

    public function getStores(){
        return $this->hasOne(Stores::className(), ['id'=>'store_id']);
    }

    public function getCities(){
        return $this->hasOne(Cities::className(), ['id'=>'city_id'])->via('stores');
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'equip' => 'Equip',
            'pc_name' => 'Pc Name',
            'player_id' => 'Player ID',
            'net_cable_name' => 'IP Address',
            'patch_port' => 'Patch Port',
            'switch_port' => 'Switch Port',
            'led_screen_fuse' => 'Led Screen Fuse',
            'pc_fuse' => 'Pc Fuse',
            'store_id' => '..Store ID',
            'note' => 'Заметки',
        ];
    }
}
