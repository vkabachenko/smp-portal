<?php

use yii\db\Migration;

/**
 * Class m191129_095716_alter_master_table
 */
class m191129_095716_alter_master_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('master', 'phone', 'string');
        $this->addColumn('master','main', $this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('master', 'phone');
        $this->dropColumn('master', 'main');
    }

}
