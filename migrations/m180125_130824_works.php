<?php

use yii\db\Migration;

/**
 * Class m180125_130824_works
 */
class m180125_130824_works extends Migration
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
        echo "m180125_130824_works cannot be reverted.\n";

        return false;
    }

    
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('works', [
            'id' => $this->primaryKey(),
            'accident_id' => $this->integer(),
            'start_date' => $this->dateTime(),
            'completion_date' => $this->dateTime(),
            'pause_date' => $this->dateTime(),
            'reason' => $this->text(),
            'end_pause_date' => $this->dateTime(),
            'act_time' => $this->string(),
            'real_time' => $this->string(),
            'full_work_performed' => $this->text(),
            'engineer_id' => $this->integer(),
            'status' => $this->string(),
            'payment' => $this->integer(),
            'note' => $this->text(),
            'act_pdf' => $this->string(),
            'act_scan' => $this->string(),
            'pics' => $this->string()
        ]);
    }

    public function down()
    {
        echo "m180125_130824_works cannot be reverted.\n";

        return false;
    }
    
}
