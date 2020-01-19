<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bid_jobs}}`.
 */
class m200118_124129_create_bid_job_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bid_job}}', [
            'id' => $this->primaryKey(),
            'bid_id' => $this->integer()->notNull(),
            'jobs_catalog_id' => $this->integer()->notNull(),
            'price' => $this->float(),
            'description' => $this->text(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);
        $this->addForeignKey(
            'fk_bid_job_bid_id_bid',
            'bid_job',
            'bid_id',
            'bid',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk_bid_job_jobs_catalog_id_jobs_catalog',
            'bid_job',
            'jobs_catalog_id',
            'jobs_catalog',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_bid_job_bid_id_bid','bid_job');
        $this->dropForeignKey('fk_bid_job_jobs_catalog_id_jobs_catalog','bid_job');
        $this->dropTable('{{%bid_job}}');
    }
}
