<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%status_history_1c}}`.
 */
class m201124_105524_create_status_history_1c_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%status_history_1c}}', [
            'id' => $this->primaryKey(),
            'bid_id' => $this->integer()->notNull(),
            'status' => $this->string(),
            'date' => $this->dateTime(),
            'sum_doc' => $this->float(),
            'author' => $this->string()->notNull()
        ]);

        $this->addForeignKey(
            'fk_status_history_1c_bid_id_bid_id',
        'status_history_1c',
            'bid_id',
            'bid',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk_status_history_1c_bid_id_bid_id',
            'status_history_1c');
        $this->dropTable('{{%status_history_1c}}');
    }
}
