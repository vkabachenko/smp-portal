<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bid_comments_read}}`.
 */
class m200319_071403_create_bid_comments_read_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bid_comments_read}}', [
            'id' => $this->primaryKey(),
            'bid_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'date_read' => $this->dateTime()->notNull()
        ]);

        $this->addForeignKey('fk_bid_comments_read_bid_id_bid',
            'bid_comments_read',
            'bid_id',
            'bid',
            'id',
            'CASCADE'
            );

        $this->addForeignKey('fk_bid_comments_read_user_id_user',
            'bid_comments_read',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        $this->createIndex('ind_bid_comments_read_user_bid_id_user_id_unique',
            'bid_comments_read',
            ['bid_id', 'user_id'],
            true
            );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('ind_bid_comments_read_user_bid_id_user_id_unique','bid_comments_read');
        $this->dropForeignKey('fk_bid_comments_read_bid_id_bid','bid_comments_read');
        $this->dropForeignKey('fk_bid_comments_read_user_id_user','bid_comments_read');

        $this->dropTable('{{%bid_comments_read}}');
    }
}
