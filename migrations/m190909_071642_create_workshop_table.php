<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%workshop}}`.
 */
class m190909_071642_create_workshop_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%workshop}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'token' => $this->string()->notNull(),
            'rules' => $this->json()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%workshop}}');
    }
}
