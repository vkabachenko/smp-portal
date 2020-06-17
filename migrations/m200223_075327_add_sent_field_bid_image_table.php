<?php

use yii\db\Migration;

/**
 * Class m200223_075327_add_sent_field_bid_image_table
 */
class m200223_075327_add_sent_field_bid_image_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bid_image', 'sent', $this->boolean()->notNull()->defaultValue(false));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('bid_image', 'sent');
    }


}
