<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bid}}`.
 */
class m190621_082732_create_bid_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bid}}', [
            'id' => $this->primaryKey(),
            'manufacturer_id' => $this->integer()->notNull(),
            'brand_id' => $this->integer(),
            'brand_model_id' => $this->integer(),
            'brand_model_name' => $this->string(),
            'composition_id' => $this->integer(),
            'composition_table' => "ENUM('composition', 'brand_composition')",
            'composition_name' => $this->string(),
            'serial_number' => $this->string(),
            'vendor_code' => $this->string(),
            'client_id' => $this->integer(),
            'client_name' => $this->string(),
            'client_phone' => $this->string(),
            'client_address' => $this->string(),
            'treatment_type' => "ENUM('warranty', 'pre-sale')",
            'purchase_date' => $this->date(),
            'application_date' => $this->date(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
            'warranty_number' => $this->string(),
            'bid_number' => $this->string(),
            'bid_1C_number' => $this->string(),
            'bid_manufacturer_number' => $this->string(),
        ]);

        $this->createIndex('ind_bid_manufacturer_id', 'bid', 'manufacturer_id');
        $this->createIndex('ind_bid_brand_id', 'bid', 'brand_id');
        $this->createIndex('ind_bid_client_id', 'bid', 'client_id');

        $this->addForeignKey(
            'fk_bid_manufacturer_id_manufacturer',
            'bid',
            'manufacturer_id',
            'manufacturer',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_bid_brand_id_brand',
            'bid',
            'brand_id',
            'brand',
            'id',
            'SET NULL'
        );

        $this->addForeignKey(
            'fk_bid_client_id_user',
            'bid',
            'client_id',
            'user',
            'id',
            'SET NULL'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_bid_manufacturer_id_manufacturer', 'bid');
        $this->dropForeignKey('fk_bid_brand_id_brand', 'bid');
        $this->dropForeignKey('fk_bid_client_id_user', 'bid');
        $this->dropTable('{{%bid}}');
    }
}
