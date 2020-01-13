<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%news_section}}`.
 */
class m200113_092438_create_news_section_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%news_section}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%news_section}}');
    }
}
