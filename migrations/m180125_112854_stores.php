<?php

use yii\db\Migration;

/**
 * Class m180125_112854_stores
 */
class m180125_112854_stores extends Migration
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
        echo "m180125_112854_stores cannot be reverted.\n";

        return false;
    }

    
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('stores', [
            'id' => $this->primaryKey(),
            'inner_id' => $this->integer(),
            'city_id' => $this->integer(),
            'store_name' => $this->string(),
            'address' => $this->string(),
            'tel1' => $this->string(),
            'dept1' => $this->string(),
            'note1' => $this->string(),
            'tel2' => $this->string(),
            'dept2' => $this->string(),
            'note2' => $this->string(),
            'tel3' => $this->string(),
            'dept3' => $this->string(),
            'note3' => $this->string(),
            'tel4' => $this->string(),
            'dept4' => $this->string(),
            'note4' => $this->string(),
            'tel5' => $this->string(),
            'dept5' => $this->string(),
            'note5' => $this->string(),
            'end_of_work' => $this->string(),
            'pics' => $this->string()
        ]);
    }

    public function down()
    {
        echo "m180125_112854_stores cannot be reverted.\n";

        return false;
    }
    
}
