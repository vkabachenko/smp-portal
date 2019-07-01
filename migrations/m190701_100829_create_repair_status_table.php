<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%repair_status}}`.
 */
class m190701_100829_create_repair_status_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%repair_status}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%repair_status}}');
    }
}
