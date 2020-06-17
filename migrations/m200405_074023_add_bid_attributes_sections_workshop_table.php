<?php

use yii\db\Migration;

/**
 * Class m200405_074023_add_bid_attributes_sections_workshop_table
 */
class m200405_074023_add_bid_attributes_sections_workshop_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('workshop', 'bid_attributes_section1', $this->json());
        $this->addColumn('workshop', 'bid_attributes_section2', $this->json());
        $this->addColumn('workshop', 'bid_attributes_section3', $this->json());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('workshop', 'bid_attributes_section1');
        $this->dropColumn('workshop', 'bid_attributes_section2');
        $this->dropColumn('workshop', 'bid_attributes_section3');
    }
}
