<?php

use yii\db\Migration;

/**
 * Class m200509_055304_add_ext_fields_bid_table
 */
class m200509_055304_add_ext_fields_bid_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bid', 'comment_1', 'text');
        $this->addColumn('bid', 'comment_2', 'text');
        $this->addColumn('bid', 'manager', 'string');
        $this->addColumn('bid', 'manager_contact', 'string');
        $this->addColumn('bid', 'manager_presale', 'string');
        $this->addColumn('bid', 'is_reappeal', 'boolean');
        $this->addColumn('bid', 'document_reappeal', 'string');
        $this->addColumn('bid', 'subdivision', 'string');
        $this->addColumn('bid', 'repair_status_date', 'date');
        $this->addColumn('bid', 'repair_status_author_id', 'integer');
        $this->addColumn('bid', 'author', 'string');
        $this->addColumn('bid', 'sum_manufacturer', 'float');
        $this->addColumn('bid', 'is_control', 'boolean');
        $this->addColumn('bid', 'is_report', 'boolean');
        $this->addColumn('bid', 'is_warranty', 'boolean');
        $this->addColumn('bid', 'warranty_comment', 'string');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('bid', 'comment_1');
        $this->dropColumn('bid', 'comment_2');
        $this->dropColumn('bid', 'manager');
        $this->dropColumn('bid', 'manager_contact');
        $this->dropColumn('bid', 'manager_presale');
        $this->dropColumn('bid', 'is_reappeal');
        $this->dropColumn('bid', 'document_reappeal');
        $this->dropColumn('bid', 'subdivision');
        $this->dropColumn('bid', 'repair_status_date');
        $this->dropColumn('bid', 'repair_status_author_id');
        $this->dropColumn('bid', 'author');
        $this->dropColumn('bid', 'sum_manufacturer');
        $this->dropColumn('bid', 'is_control');
        $this->dropColumn('bid', 'is_report');
        $this->dropColumn('bid', 'is_warranty');
        $this->dropColumn('bid', 'warranty_comment');
    }

}
