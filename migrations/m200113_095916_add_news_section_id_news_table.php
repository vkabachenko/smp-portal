<?php

use yii\db\Migration;

/**
 * Class m200113_095916_add_news_section_id_news_table
 */
class m200113_095916_add_news_section_id_news_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('news', 'news_section_id', 'integer');
        $this->addForeignKey(
            'fk_news_news_section_id_news_section',
            'news',
            'news_section_id',
            'news_section',
            'id',
            'SET NULL'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_news_news_section_id_news_section', 'news');
        $this->dropColumn('news', 'news_section_id');
    }

}
