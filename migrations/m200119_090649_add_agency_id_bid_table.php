<?php

use yii\db\Migration;

/**
 * Class m200119_090649_add_agency_id_bid_table
 */
class m200119_090649_add_agency_id_bid_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bid', 'agency_id', 'integer');
        $this->addForeignKey(
            'fk_bid_agency_id_agency',
            'bid',
            'agency_id',
            'agency',
            'id',
            'SET NULL'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_bid_agency_id_agency','bid');
        $this->dropColumn('bid', 'agency_id');
    }

}
