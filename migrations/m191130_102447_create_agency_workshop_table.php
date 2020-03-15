<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%agency_workshop}}`.
 */
class m191130_102447_create_agency_workshop_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%agency_workshop}}', [
            'id' => $this->primaryKey(),
            'agency_id' => $this->integer()->notNull(),
            'workshop_id' => $this->integer()->notNull(),
            'active' => $this->boolean()->notNull()->defaultValue(false)
        ]);
        $this->addForeignKey(
            'fk_agency_workshop_agency_id_agency',
            'agency_workshop',
            'agency_id',
            'agency',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk_agency_workshop_workshop_id_workshop',
            'agency_workshop',
            'workshop_id',
            'workshop',
            'id',
            'CASCADE'
        );
        $this->createIndex('ind_agency_id_workshop_id_agencyworkshop', 'agency_workshop', ['agency_id', 'workshop_id'], true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('ind_agency_id_workshop_id_agencyworkshop', 'agency_workshop');
        $this->dropForeignKey('fk_agency_workshop_agency_id_agency', 'agency_workshop');
        $this->dropForeignKey('fk_agency_workshop_workshop_id_workshop', 'agency_workshop');
        $this->dropTable('{{%agency_workshop}}');
    }
}
