<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%replacement_part}}`.
 */
class m200510_060055_create_replacement_part_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%replacement_part}}', [
            'id' => $this->primaryKey(),
            'bid_id' => $this->integer()->notNull(),
            'vendor_code' => $this->string(),
            'vendor_code_replacement' => $this->string(),
            'is_agree' => $this->boolean(),
            'name' => $this->string(),
            'price' => $this->float(),
            'quantity' => $this->integer(),
            'total_price' => $this->float(),
            'manufacturer' => $this->string(),
            'link1C' => $this->string(),
            'comment' => $this->string(),
            'status' => $this->string(),
            'is_to_buy' => $this->boolean()
        ]);

        $this->addForeignKey(
            'fk_replacement_part_bid_id_bid_id',
            'replacement_part',
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
            'fk_replacement_part_bid_id_bid_id',
            'replacement_part'
        );
        $this->dropTable('{{%replacement_part}}');
    }
}
