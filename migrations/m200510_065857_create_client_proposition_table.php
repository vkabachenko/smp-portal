<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%client_proposition}}`.
 */
class m200510_065857_create_client_proposition_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%client_proposition}}', [
            'id' => $this->primaryKey(),
            'bid_id' => $this->integer()->notNull(),
            'name' => $this->string(),
            'price' => $this->float(),
            'quantity' => $this->integer(),
            'total_price' => $this->float(),
        ]);

        $this->addForeignKey(
            'fk_client_proposition_bid_id_bid_id',
            'client_proposition',
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
            'fk_client_proposition_bid_id_bid_id',
            'client_proposition'
        );
        $this->dropTable('{{%client_proposition}}');
    }
}
