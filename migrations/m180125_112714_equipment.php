<?php

use yii\db\Migration;

/**
 * Class m180125_112714_equipment
 */
class m180125_112714_equipment extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180125_112714_equipment cannot be reverted.\n";

        return false;
    }

    
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('equipment', [
            'id' => $this->primaryKey(),
            'equip' => $this->string(),
            'pc_name' => $this->string(),
            'player_id' => $this->string(),
            'net_cable_name' => $this->string(),
            'patch_port' => $this->string(),
            'switch_port' => $this->string(),
            'led_screen_fuse' => $this->string(),
            'pc_fuse' => $this->string(),
            'store_id' => $this->integer(),
            'note' => $this->text(),
        ]);
    }

    public function down()
    {
        echo "m180125_112714_equipment cannot be reverted.\n";

        return false;
    }
    
}
