<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%news_like}}`.
 */
class m200112_092244_create_news_like_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%news_like}}', [
            'id' => $this->primaryKey(),
            'status' => "ENUM('like', 'dislike') NOT NULL",
            'user_id' => $this->integer()->notNull(),
            'news_id' => $this->integer()->notNull(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addForeignKey(
            'fk_news_like_user_id_user',
            'news_like',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_news_like_news_id_news',
            'news_like',
            'news_id',
            'news',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_news_like_user_id_user', 'news_like');
        $this->dropForeignKey('fk_news_like_news_id_news', 'news_like');
        $this->dropTable('{{%news_like}}');
    }
}
