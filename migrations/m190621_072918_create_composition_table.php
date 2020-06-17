<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%composition}}`.
 */
class m190621_072918_create_composition_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%composition}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%composition}}');
    }
}
