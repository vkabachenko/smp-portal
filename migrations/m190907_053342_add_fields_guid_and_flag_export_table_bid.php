<?php

use yii\db\Migration;

/**
 * Class m190907_053342_add_fields_guid_and_flag_export_table_bid
 */
class m190907_053342_add_fields_guid_and_flag_export_table_bid extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bid','guid', 'string');
        $this->addColumn('bid', 'flag_export', $this->boolean()->notNull()->defaultValue(false));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('bid', 'guid');
        $this->dropColumn('bid', 'flag_export');
    }

}
