<?php

use yii\db\Migration;

/**
 * Class m191229_082134_add_bid_attribute_field_workshop_table
 */
class m191229_082134_add_bid_attribute_field_workshop_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('workshop', 'bid_attributes', 'json');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('workshop', 'bid_attributes');
    }

}
