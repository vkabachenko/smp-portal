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
 * @property bool $is_agree
 * @property string $name
 * @property double $price
 * @property int $quantity
 * @property double $total_price
 * @property string $manufacturer
 * @property string $link1C
 * @property string $comment
 * @property string $status
 * @property bool $is_to_buy
 * @property int $num_order
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
            [['is_to_buy', 'is_agree'], 'boolean'],
            [['quantity', 'bid_id', 'num_order'], 'integer'],
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
            'vendor_code' => 'Артикул',
            'vendor_code_replacement' => 'Артикул замены',
            'is_agree' => 'Согласовано',
            'name' => 'Название',
            'price' => 'Цена',
            'quantity' => 'Количество',
            'total_price' => 'Всего',
            'manufacturer' => 'Производитель',
            'link1C' => 'Ссылка в 1С',
            'comment' => 'Комментарий',
            'status' => 'Статус',
            'is_to_buy' => 'Надо покупать',
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
