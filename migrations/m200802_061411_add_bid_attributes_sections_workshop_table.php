<?php

use yii\db\Migration;

/**
 * Class m200802_061411_add_bid_attributes_sections_workshop_table
 */
class m200802_061411_add_bid_attributes_sections_workshop_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('workshop', 'bid_attributes_section4', $this->json());
        $this->addColumn('workshop', 'bid_attributes_section5', $this->json());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('workshop', 'bid_attributes_section4');
        $this->dropColumn('workshop', 'bid_attributes_section5');
    }
}
