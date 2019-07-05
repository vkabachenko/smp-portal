<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `{{%bid_history_update}}`.
 */
class m190705_080039_drop_bid_history_update_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk_update_history_bid_history_id_bid_history', 'bid_update_history');
        $this->dropTable('{{%bid_update_history}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
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
}
