<?php

use yii\db\Migration;

/**
 * Class m200703_081334_add_flag_export_field
 */
class m200703_081334_add_flag_export_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('client', 'flag_export', $this->boolean()->notNull()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('client', 'flag_export');
    }


}
