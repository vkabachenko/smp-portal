<?php

use yii\db\Migration;

/**
 * Class m190909_080402_add_field_master_id_bid_table
 */
class m190909_080402_add_field_master_id_bid_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bid', 'master_id', $this->integer());
        $this->createIndex('ind_bid_master_id', 'bid', 'master_id');
        $this->addForeignKey(
            'fk_bid_master_id_master',
            'bid',
            'master_id',
            'master',
            'id',
            'SET NULL'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_bid_master_id_master','bid');
        $this->dropColumn('bid', 'master_id');
    }

}
