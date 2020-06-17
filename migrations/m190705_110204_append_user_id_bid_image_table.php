<?php

use yii\db\Migration;

/**
 * Class m190705_110204_append_user_id_bid_image_table
 */
class m190705_110204_append_user_id_bid_image_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bid_image', 'user_id', 'integer');
        $this->createIndex('ind_bid_image_user_id', 'bid_image', 'user_id');
        $this->addForeignKey(
            'fk_bid_image_user_id_user',
            'bid_image',
            'user_id',
            'user',
            'id',
            'SET NULL'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_bid_image_user_id_user','bid_image');
        $this->dropColumn('bid_image', 'user_id');
    }


}
