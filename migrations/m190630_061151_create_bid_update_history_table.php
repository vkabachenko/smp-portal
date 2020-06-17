<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bid_update_history}}`.
 */
class m190630_061151_create_bid_update_history_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bid_update_history}}', [
            'id' => $this->primaryKey(),
            'bid_history_id' => $this->integer()->notNull(),
            'updated_attributes' => $this->json()
        ]);
        $this->createIndex('ind_update_history_bid_history_id', 'bid_update_history', 'bid_history_id');
        $this->addForeignKey(
            'fk_update_history_bid_history_id_bid_history',
            'bid_update_history',
            'bid_history_id',
            'bid_history',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_update_history_bid_history_id_bid_history', 'bid_update_history');
        $this->dropTable('{{%bid_update_history}}');
    }
}
