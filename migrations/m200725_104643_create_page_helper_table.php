<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%page_helper}}`.
 */
class m200725_104643_create_page_helper_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%page_helper}}', [
            'id' => $this->primaryKey(),
            'controller' => $this->string()->notNull(),
            'action' => $this->string()->notNull(),
            'help_text' => $this->text()
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%page_helper}}');
    }
}
