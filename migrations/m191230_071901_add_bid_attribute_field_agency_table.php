<?php

use yii\db\Migration;

/**
 * Class m191230_071901_add_bid_attribute_field_agency_table
 */
class m191230_071901_add_bid_attribute_field_agency_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('agency', 'bid_attributes', 'json');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('agency', 'bid_attributes');
    }
    
}
