<?php

use yii\db\Migration;

/**
 * Class m200120_084913_add_model_class_bid_history_table
 */
class m200120_084913_add_model_class_bid_history_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bid_history', 'model_class', 'string');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('bid_history', 'model_class');
    }

}
