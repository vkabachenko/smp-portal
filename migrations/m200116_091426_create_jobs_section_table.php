<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%jobs_section}}`.
 */
class m200116_091426_create_jobs_section_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%jobs_section}}', [
            'id' => $this->primaryKey(),
            'agency_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull()
        ]);
        $this->addForeignKey(
            'fk_jobs_section_agency_id_agency',
            'jobs_section',
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
        $this->dropForeignKey('fk_jobs_section_agency_id_agency', 'jobs_section');
        $this->dropTable('{{%jobs_section}}');
    }
}
