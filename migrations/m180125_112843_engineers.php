<?php

use yii\db\Migration;

/**
 * Class m180125_112843_engineers
 */
class m180125_112843_engineers extends Migration
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
        echo "m180125_112843_engineers cannot be reverted.\n";

        return false;
    }

    
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('engineers', [
            'id' => $this->primaryKey(),
            'city_id' => $this->integer(),
            'name' => $this->string(),
            'tel1' => $this->string(),
            'tel2' => $this->string(),
            'email1' => $this->string(),
            'email2' => $this->string(),
            'url' => $this->string(),
            'company_name' => $this->string(),
            'payment' => $this->text(),
            'note' => $this->text()
        ]);
    }

    public function down()
    {
        echo "m180125_112843_engineers cannot be reverted.\n";

        return false;
    }
    
}
