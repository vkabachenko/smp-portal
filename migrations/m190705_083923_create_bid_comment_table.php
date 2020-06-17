<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bid_comment}}`.
 */
class m190705_083923_create_bid_comment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bid_comment}}', [
            'id' => $this->primaryKey(),
            'bid_id' => $this->integer()->notNull(),
            'private' => $this->boolean()->notNull()->defaultValue(true),
            'comment'  => $this->text()->notNull(),
            'created_at'=> $this->dateTime(),
            'updated_at'=> $this->dateTime(),
        ]);
        $this->createIndex('ind_bid_comment_bid_id', 'bid_comment', 'bid_id');

        $this->addForeignKey(
            'fk_bid_comment_bid_id_bid',
            'bid_comment',
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
        $this->dropForeignKey('fk_bid_comment_bid_id_bid','bid_comment');
        $this->dropTable('{{%bid_comment}}');
    }
}
