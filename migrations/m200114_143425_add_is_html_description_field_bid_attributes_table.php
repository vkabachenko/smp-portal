<?php

use yii\db\Migration;

/**
 * Class m200114_143425_add_is_html_description_field_bid_attributes_table
 */
class m200114_143425_add_is_html_description_field_bid_attributes_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bid_attribute', 'is_html_description', $this->boolean()->notNull()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('bid_attribute', 'is_html_description');
    }

}
