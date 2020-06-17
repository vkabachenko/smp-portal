<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%spare}}`.
 */
class m200129_103347_create_spare_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%spare}}', [
            'id' => $this->primaryKey(),
            'bid_id' => $this->integer()->notNull(),
            'vendor_code' => $this->string(),
            'name' => $this->string()->notNull(),
            'quantity' => $this->integer(),
            'price' => $this->float(),
            'total_price' => $this->float(),
            'invoice_number' => $this->string(),
            'invoice_date' => $this->date(),
            'description' => $this->text(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);
        $this->addForeignKey(
            'fk_spare_bid_id_bid',
            'spare',
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
        $this->dropForeignKey('fk_spare_bid_id_bid', 'spare');
        $this->dropTable('{{%spare}}');
    }
}
