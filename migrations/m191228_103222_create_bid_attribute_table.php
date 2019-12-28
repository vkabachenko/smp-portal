<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bid_attribute}}`.
 */
class m191228_103222_create_bid_attribute_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bid_attribute}}', [
            'id' => $this->primaryKey(),
            'attribute' => $this->string(),
            'description' => $this->text(),
            'short_description' => $this->string(),
            'is_enabled_agencies' => $this->boolean(),
            'is_enabled_workshops' => $this->boolean()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%bid_attribute}}');
    }
}
