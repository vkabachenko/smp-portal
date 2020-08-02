<?php

use yii\db\Migration;

/**
 * Class m200802_061705_add_bid_attributes_sections_agency_table
 */
class m200802_061705_add_bid_attributes_sections_agency_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('agency', 'bid_attributes_section4', $this->json());
        $this->addColumn('agency', 'bid_attributes_section5', $this->json());
     }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('agency', 'bid_attributes_section4');
        $this->dropColumn('agency', 'bid_attributes_section5');
    }
}
