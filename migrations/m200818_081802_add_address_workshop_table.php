<?php

use yii\db\Migration;

/**
 * Class m200818_081802_add_address_workshop_table
 */
class m200818_081802_add_address_workshop_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('workshop', 'address', 'string');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('workshop', 'address');
    }

}
