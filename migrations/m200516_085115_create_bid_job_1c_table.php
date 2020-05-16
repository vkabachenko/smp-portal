<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bid_job_1c}}`.
 */
class m200516_085115_create_bid_job_1c_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bid_job_1c}}', [
            'id' => $this->primaryKey(),
            'bid_id' => $this->integer()->notNull(),
            'name' => $this->string(),
            'quantity' => $this->integer(),
            'price' => $this->float(),
            'total_price' => $this->float(),
        ]);
        $this->addForeignKey(
            'fk_bid_job_1c_bid_id_bid_id',
            'bid_job_1c',
            'bid_id',
            'bid',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk_bid_job_1c_bid_id_bid_id',
            'bid_job_1c'
        );
        $this->dropTable('{{%bid_job_1c}}');
    }
}
