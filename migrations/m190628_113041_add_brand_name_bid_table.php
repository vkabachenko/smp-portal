<?php

use yii\db\Migration;

/**
 * Class m190628_113041_add_brand_name_bid_table
 */
class m190628_113041_add_brand_name_bid_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bid','brand_name', $this->string()->notNull());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('bid', 'brand_name');
    }

}
