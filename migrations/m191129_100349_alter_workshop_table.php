<?php

use yii\db\Migration;

/**
 * Class m191129_100349_alter_workshop_table
 */
class m191129_100349_alter_workshop_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('workshop', 'description', 'text');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('workshop', 'description');
    }

}
