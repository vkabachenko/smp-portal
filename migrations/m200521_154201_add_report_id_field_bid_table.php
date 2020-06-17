<?php

use yii\db\Migration;

/**
 * Class m200521_154201_add_report_id_field_bid_table
 */
class m200521_154201_add_report_id_field_bid_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bid', 'report_id', $this->integer());
        $this->addForeignKey(
            'fk_bid_report_id_report_id',
            'bid',
            'report_id',
            'report',
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
            'fk_bid_report_id_report_id',
            'bid'
        );
        $this->dropColumn('bid', 'report_id');
    }

}
