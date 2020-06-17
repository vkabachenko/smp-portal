<?php

use yii\db\Migration;

/**
 * Class m190701_074101_add_defect_diagnostic_fields_bid_table
 */
class m190701_074101_add_defect_diagnostic_fields_bid_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bid', 'defect', 'string');
        $this->addColumn('bid', 'diagnostic', 'string');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('bid', 'defect');
        $this->dropColumn('bid', 'diagnostic');
    }

}
