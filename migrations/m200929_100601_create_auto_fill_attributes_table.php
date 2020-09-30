<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%auto_fill_attributes}}`.
 */
class m200929_100601_create_auto_fill_attributes_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%auto_fill_attributes}}', [
            'id' => $this->primaryKey(),
            'decision_workshop_status_id' => $this->integer(),
            'decision_agency_status_id' => $this->integer(),
            'status_id' => $this->integer(),
            'auto_fill' => $this->json()
        ]);
        
        $this->createIndex('ind_auto_fill_attributes_unique',
        'auto_fill_attributes',
            ['decision_workshop_status_id', 'decision_agency_status_id', 'status_id'],
            true
        );
        
        $this->addForeignKey('fk_auto_fill_attributes_decision_workshop_status_id',
        'auto_fill_attributes',
            'decision_workshop_status_id',
            'decision_workshop_status',
            'id',
            'CASCADE'
        );

        $this->addForeignKey('fk_auto_fill_attributes_decision_agency_status_id',
            'auto_fill_attributes',
            'decision_agency_status_id',
            'decision_agency_status',
            'id',
            'CASCADE'
        );

        $this->addForeignKey('fk_auto_fill_attributes_status_id_status_id',
            'auto_fill_attributes',
            'status_id',
            'bid_status',
            'id',
            'CASCADE'
        );
    
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_auto_fill_attributes_decision_workshop_status_id',
            'auto_fill_attributes'
        );

        $this->dropForeignKey('fk_auto_fill_attributes_decision_agency_status_id',
            'auto_fill_attributes'
        );

        $this->dropForeignKey('fk_auto_fill_attributes_status_id_status_id',
            'auto_fill_attributes'
        );
        
        $this->dropTable('{{%auto_fill_attributes}}');
    }
}
