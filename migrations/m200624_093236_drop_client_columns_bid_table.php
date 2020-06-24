<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `{{%client_columns_bid}}`.
 */
class m200624_093236_drop_client_columns_bid_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('bid', 'client_name');
        $this->dropColumn('bid', 'client_phone');
        $this->dropColumn('bid', 'client_address');
        $this->dropColumn('bid', 'client_type');
        $this->addForeignKey(
            'fk_bid_client_id_client_id,',
            'bid',
            'client_id',
            'client',
            'id',
            'SET NULL'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk_bid_client_id_client_id,',
            'bid'
        );
        $this->addColumn('bid', 'client_name', 'string');
        $this->addColumn('bid', 'client_phone', 'string');
        $this->addColumn('bid', 'client_address', 'string');
        $this->addColumn('bid',
            'client_type',
            "ENUM('person', 'legal_entity') NOT NULL DEFAULT 'person'"
        );
    }
}
