<?php

use yii\db\Migration;

/**
 * Class m200126_090110_remove_email4_workshop_table
 */
class m200126_090110_remove_email4_workshop_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('workshop', 'email4');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('workshop', 'email4', 'string');
    }

}
