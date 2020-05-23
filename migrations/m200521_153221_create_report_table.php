<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%report}}`.
 */
class m200521_153221_create_report_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%report}}', [
            'id' => $this->primaryKey(),
            'workshop_id' => $this->integer()->notNull(),
            'agency_id' => $this->integer()->notNull(),
            'report_nom' => $this->string(),
            'report_date' => $this->date(),
            'report_filename' => $this->string()->notNull(),
            'is_transferred' => $this->boolean()->notNull()->defaultValue(false)
        ]);

        $this->addForeignKey(
            'fk_report_workshop_id_workshop_id',
            'report',
            'workshop_id',
            'workshop',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_report_agency_id_agency_id',
            'report',
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
            'fk_report_workshop_id_workshop_id',
            'report'
        );

        $this->dropForeignKey(
            'fk_report_agency_id_agency_id',
            'report'
        );
        $this->dropTable('{{%report}}');
    }
}
