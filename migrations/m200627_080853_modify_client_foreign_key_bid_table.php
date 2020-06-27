<?php

use yii\db\Migration;

/**
 * Class m200627_080853_modify_client_foreign_key_bid_table
 */
class m200627_080853_modify_client_foreign_key_bid_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey(
            'fk_bid_client_id_user',
            'bid'
        );
        $this->addForeignKey(
            'fk_bid_client_id_client',
            'bid',
            'client_id',
            'client',
            'id',
            'SET NULL'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk_bid_client_id_client',
            'bid'
        );

        $this->addForeignKey(
            'fk_bid_client_id_user',
            'bid',
            'client_id',
            'user',
            'id',
            'SET NULL'
        );
    }

}
