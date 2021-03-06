<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `{{%image}}`.
 */
class m190705_080245_drop_image_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk_image_bid_id_bid', 'image');
        $this->dropForeignKey('fk_image_bid_history_id_bid_history', 'image');
        $this->dropTable('{{%image}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->createTable('{{%image}}', [
            'id' => $this->primaryKey(),
            'bid_id' => $this->integer()->notNull(),
            'bid_history_id' => $this->integer()->notNull(),
            'file_name' => $this->string()->notNull(),
            'src_name'  => $this->string()->notNull(),
        ]);
        $this->createIndex('ind_image_bid_id', 'image', 'bid_id');
        $this->createIndex('ind_image_bid_history_id', 'image', 'bid_history_id');

        $this->addForeignKey(
            'fk_image_bid_id_bid',
            'image',
            'bid_id',
            'bid',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk_image_bid_history_id_bid_history',
            'image',
            'bid_history_id',
            'bid_history',
            'id',
            'CASCADE'
        );
    }
}
