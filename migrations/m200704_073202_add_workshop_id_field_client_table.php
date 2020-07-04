<?php

use yii\db\Migration;

/**
 * Class m200704_073202_add_workshop_id_field_client_table
 */
class m200704_073202_add_workshop_id_field_client_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('client', 'workshop_id', 'integer');
        $this->addForeignKey(
            'fk_client_workshop_id_workshop_id,',
            'client',
            'workshop_id',
            'workshop',
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
            'fk_client_workshop_id_workshop_id,',
            'client'
        );
        $this->dropColumn('client', 'workshop_id');
    }

}
