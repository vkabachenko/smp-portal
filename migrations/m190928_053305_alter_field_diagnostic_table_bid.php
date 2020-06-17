<?php

use yii\db\Migration;

/**
 * Class m190928_053305_alter_field_diagnostic_table_bid
 */
class m190928_053305_alter_field_diagnostic_table_bid extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('bid', 'diagnostic', 'text');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('bid', 'diagnostic', 'string');
    }

}
