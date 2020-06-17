<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bid_status}}`.
 */
class m190621_082117_create_bid_status_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bid_status}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%bid_status}}');
    }
}
