<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bid_job_1c".
 *
 * @property int $id
 * @property int $bid_id
 * @property int $quantity
 * @property string $name
 * @property double $price
 * @property double $total_price
 * @property int $num_order
 *
 * @property Bid $bid
 */
class BidJob1c extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bid_job_1c';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bid_id'], 'required'],
            [['bid_id', 'quantity', 'num_order'], 'integer'],
            [['name'], 'string'],
            [['price', 'total_price'], 'number'],
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
            'quantity' => 'Количество',
            'price' => 'Цена',
            'total_price' => 'Сумма',
            'name' => 'Наименование',
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
