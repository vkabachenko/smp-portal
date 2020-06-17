<?php

use yii\db\Migration;

/**
 * Class m191130_093534_add_column_workshop_id_bid_table
 */
class m191130_093534_add_column_workshop_id_bid_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bid', 'workshop_id', $this->integer()->notNull()->defaultValue(1));
        $this->addForeignKey(
            'fk_bid_workshop_id_workshop',
            'bid',
            'workshop_id',
            'workshop',
            'id'
            );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_bid_workshop_id_workshop', 'bid');
        $this->dropColumn('bid', 'workshop_id');
    }

}
