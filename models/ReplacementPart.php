<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "replacement_part".
 *
 * @property int $id
 * @property int $bid_id
 * @property string $vendor_code
 * @property string $vendor_code_replacement
 * @property int $is_agree
 * @property string $name
 * @property double $price
 * @property int $quantity
 * @property double $total_price
 * @property string $manufacturer
 * @property string $link1C
 * @property string $comment
 * @property string $status
 * @property int $is_to_buy
 *
 * @property Bid $bid
 */
class ReplacementPart extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'replacement_part';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bid_id'], 'required'],
            [['bid_id', 'is_agree', 'quantity', 'is_to_buy'], 'integer'],
            [['price', 'total_price'], 'number'],
            [['vendor_code', 'vendor_code_replacement', 'name', 'manufacturer', 'link1C', 'comment', 'status'], 'string', 'max' => 255],
            [['bid_id'], 'exist', 'skipOnError' => true, 'targetClass' => Bid::className(), 'targetAttribute' => ['bid_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bid_id' => 'Bid ID',
            'vendor_code' => 'Vendor Code',
            'vendor_code_replacement' => 'Vendor Code Replacement',
            'is_agree' => 'Is Agree',
            'name' => 'Name',
            'price' => 'Price',
            'quantity' => 'Quantity',
            'total_price' => 'Total Price',
            'manufacturer' => 'Manufacturer',
            'link1C' => 'Link1 C',
            'comment' => 'Comment',
            'status' => 'Status',
            'is_to_buy' => 'Is To Buy',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBid()
    {
        return $this->hasOne(Bid::className(), ['id' => 'bid_id']);
    }
}
