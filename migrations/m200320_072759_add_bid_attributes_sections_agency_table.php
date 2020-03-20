<?php

use yii\db\Migration;

/**
 * Class m200320_072759_add_bid_attributes_sections_agency_table
 */
class m200320_072759_add_bid_attributes_sections_agency_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('agency', 'bid_attributes_section1', $this->json());
        $this->addColumn('agency', 'bid_attributes_section2', $this->json());
        $this->addColumn('agency', 'bid_attributes_section3', $this->json());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('agency', 'bid_attributes_section1');
        $this->dropColumn('agency', 'bid_attributes_section2');
        $this->dropColumn('agency', 'bid_attributes_section3');
    }

}
