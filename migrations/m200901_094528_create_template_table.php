<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%template}}`.
 */
class m200901_094528_create_template_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%template}}', [
            'id' => $this->primaryKey(),
            'agency_id' => $this->integer()->notNull(),
            'type' => $this->string()->notNull(),
            'sub_type' => $this->string(),
            'file_name' => $this->string(),
            'email_subject' => $this->string(),
            'email_body' => $this->text(),
            'email_signature' => $this->string()
        ]);
        $this->addForeignKey(
            'fk_template_agency_id_agency_id',
            'template',
            'agency_id',
            'agency',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk_template_agency_id_agency_id',
            'template');
        $this->dropTable('{{%template}}');
    }
}
