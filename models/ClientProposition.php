<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "client_proposition".
 *
 * @property int $id
 * @property int $bid_id
 * @property string $name
 * @property double $price
 * @property int $quantity
 * @property double $total_price
 * @property int $num_order
 *
 * @property Bid $bid
 */
class ClientProposition extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'client_proposition';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bid_id'], 'required'],
            [['bid_id', 'quantity', 'num_order'], 'integer'],
            [['price', 'total_price'], 'number'],
            [['name'], 'string', 'max' => 255],
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
            'name' => 'Name',
            'price' => 'Price',
            'quantity' => 'Quantity',
            'total_price' => 'Total Price',
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
