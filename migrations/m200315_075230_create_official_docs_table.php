<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%official_docs}}`.
 */
class m200315_075230_create_official_docs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%official_docs}}', [
            'id' => $this->primaryKey(),
            'model' => $this->string()->notNull(),
            'model_id' => $this->integer()->notNull(),
            'file_name' => $this->string()->notNull(),
            'src_name' => $this->string()->notNull(),
            'created_at' => $this->dateTime(),
            'updated_at' =>$this->dateTime(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%official_docs}}');
    }
}
