<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%template}}`.
 */
class m190721_063410_create_template_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%template}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'fields' => $this->json()->notNull()
        ]);
        $this->createIndex('ind_name_template', 'template', 'name', true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%template}}');
    }
}
