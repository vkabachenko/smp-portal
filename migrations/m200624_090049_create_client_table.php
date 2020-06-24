<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%client}}`.
 */
class m200624_090049_create_client_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%client}}', [
            'id' => $this->primaryKey(),
            'guid' => $this->string(),
            'name' => $this->string(),
            'full_name' => $this->string(),
            'client_type' => $this->string()->notNull(),
            'date_register' => $this->date(),
            'comment' => $this->text(),
            'description' => $this->text(),
            'manager' => $this->string(),
            'inn' => $this->string(),
            'kpp' => $this->string(),
            'email' => $this->string(),
            'address_actual' => $this->string(),
            'address_legal' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%client}}');
    }
}
