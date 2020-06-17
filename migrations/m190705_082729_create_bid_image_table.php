<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bid_image}}`.
 */
class m190705_082729_create_bid_image_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bid_image}}', [
            'id' => $this->primaryKey(),
            'bid_id' => $this->integer()->notNull(),
            'file_name' => $this->string()->notNull(),
            'src_name'  => $this->string()->notNull(),
            'created_at'=> $this->dateTime(),
            'updated_at'=> $this->dateTime(),
        ]);
        $this->createIndex('ind_bid_image_bid_id', 'bid_image', 'bid_id');

        $this->addForeignKey(
            'fk_bid_image_bid_id_bid',
            'bid_image',
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
        $this->dropForeignKey('fk_bid_image_bid_id_bid','bid_image');
        $this->dropTable('{{%bid_image}}');
    }
}
