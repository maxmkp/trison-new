<?php

use yii\db\Migration;

/**
 * Class m180125_112927_accident
 */
class m180125_112927_accident extends Migration
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
        echo "m180125_112927_accident cannot be reverted.\n";

        return false;
    }

    
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('accident', [
            'id' => $this->primaryKey(),
            'accident_id' => $this->string(),
            'act_number' => $this->string(),
            'act_date' => $this->dateTime(),
            'priority' => $this->string(),
            'store_id' => $this->integer(),
            'equipment_id' => $this->integer(),
            'fault' => $this->string(),
        ]);
    }

    public function down()
    {
        echo "m180125_112927_accident cannot be reverted.\n";

        return false;
    }
    
}
