<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `{{%template}}`.
 */
class m200901_094325_drop_template_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable('{{%template}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->createTable('{{%template}}', [
            'id' => $this->primaryKey(),
            'name', $this->string()->notNull(),
            'fields', $this->json()->notNull()
        ]);
        $this->createIndex('ind_name_template', 'template', 'name', true);
    }
}
