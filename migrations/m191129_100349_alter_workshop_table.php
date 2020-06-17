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
        $this->alterColumn('workshop', 'token', 'string');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('workshop', 'description');
        $this->alterColumn('workshop', 'token', $this->string()->notNull());
    }

}
