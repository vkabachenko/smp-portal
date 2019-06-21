<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bid_history}}`.
 */
class m190621_100118_create_bid_history_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bid_history}}', [
            'id' => $this->primaryKey(),
            'bid_id' => $this->integer()->notNull(),
            'user_id' => $this->integer(),
            'status_id' => $this->integer(),
            'comment' => $this->text()
        ]);
        $this->createIndex('ind_bid_history_bid_id', 'bid_history', 'bid_id');
        $this->createIndex('ind_bid_history_user_id', 'bid_history', 'user_id');
        $this->createIndex('ind_bid_history_status_id', 'bid_history', 'status_id');

        $this->addForeignKey(
            'fk_bid_history_bid_id',
            'bid_history',
            'bid_id',
            'bid',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_bid_history_user_id',
            'bid_history',
            'user_id',
            'user',
            'id',
            'SET NULL'
        );

        $this->addForeignKey(
            'fk_bid_history_status_id',
            'bid_history',
            'status_id',
            'bid_status',
            'id',
            'SET NULL'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_bid_history_bid_id', 'bid_history');
        $this->dropForeignKey('fk_bid_history_user_id', 'bid_history');
        $this->dropForeignKey('fk_bid_history_status_id', 'bid_history');
        $this->dropTable('{{%bid_history}}');
    }
}
