<?php

use yii\db\Migration;

/**
 * Class m180125_112912_cities
 */
class m180125_112912_cities extends Migration
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
        echo "m180125_112912_cities cannot be reverted.\n";

        return false;
    }

    
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('cities', [
            'id' => $this->primaryKey(),
            'name' => $this->string()
        ]);
    }

    public function down()
    {
        echo "m180125_112912_cities cannot be reverted.\n";

        return false;
    }
    
}
