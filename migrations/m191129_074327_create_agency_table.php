<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%agency}}`.
 */
class m191129_074327_create_agency_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%agency}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'description' => $this->text(),
            'manufacturer_id' => $this->integer()->notNull(),

        ]);
        $this->createIndex('ind_agency_manufacturer_id', 'agency', 'manufacturer_id');
        $this->addForeignKey(
            'fk_agency_manufacturer_id_manufacturer',
            'agency',
            'manufacturer_id',
            'manufacturer',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_agency_manufacturer_id_manufacturer','agency');
        $this->dropTable('{{%agency}}');
    }
}
