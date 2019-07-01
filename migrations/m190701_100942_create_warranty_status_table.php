<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%warranty_status}}`.
 */
class m190701_100942_create_warranty_status_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%warranty_status}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%warranty_status}}');
    }
}
