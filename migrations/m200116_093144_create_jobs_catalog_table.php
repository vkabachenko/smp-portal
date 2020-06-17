<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%jobs_catalog}}`.
 */
class m200116_093144_create_jobs_catalog_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%jobs_catalog}}', [
            'id' => $this->primaryKey(),
            'uuid'  => $this->string()->notNull(),
            'agency_id' => $this->integer()->notNull(),
            'jobs_section_id' => $this->integer(),
            'date_actual' => $this->date()->notNull(),
            'vendor_code' => $this->string(),
            'name' => $this->string()->notNull(),
            'description' => $this->text(),
            'hour_tariff' => $this->float(),
            'hours_required' => $this->float(),
            'price' => $this->float()->notNull(),
        ]);

        $this->addForeignKey(
            'fk_jobs_catalog_agency_id_agency',
            'jobs_catalog',
            'agency_id',
            'agency',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk_jobs_catalog_jobs_section_id_jobs_section',
            'jobs_catalog',
            'jobs_section_id',
            'jobs_section',
            'id',
            'SET NULL'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_jobs_catalog_agency_id_agency','jobs_catalog');
        $this->dropForeignKey('fk_jobs_catalog_jobs_section_id_jobs_section','jobs_catalog');
        $this->dropTable('{{%jobs_catalog}}');
    }
}
