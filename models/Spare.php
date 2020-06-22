<?php

namespace app\models;

use app\helpers\common\DateHelper;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "spare".
 *
 * @property int $id
 * @property int $bid_id
 * @property string $vendor_code
 * @property string $name
 * @property int $quantity
 * @property double $price
 * @property double $total_price
 * @property string $invoice_number
 * @property string $invoice_date
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 * @property int $num_order
 *
 * @property Bid $bid
 */
class Spare extends \yii\db\ActiveRecord implements TranslatableInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'spare';
    }

    public static function translateName()
    {
        return 'Запчасти';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'value' => function () {
                    return date('Y-m-d H:i:s');
                }
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bid_id', 'name'], 'required'],
            [['bid_id', 'quantity', 'num_order'], 'integer'],
            [['price', 'total_price'], 'number'],
            [['invoice_date'], 'safe'],
            [['description'], 'string'],
            [['vendor_code', 'name', 'invoice_number'], 'string', 'max' => 255],
            [['bid_id'], 'exist', 'skipOnError' => true, 'targetClass' => Bid::className(), 'targetAttribute' => ['bid_id' => 'id']],
            [['created_at', 'updated_at'], 'safe'],
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
            'name' => 'Наименование',
            'quantity' => 'Количество',
            'price' => 'Цена',
            'total_price' => 'Стоимость',
            'invoice_number' => 'Номер накладной',
            'invoice_date' => 'Дата накладной',
            'description' => 'Описание',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBid()
    {
        return $this->hasOne(Bid::className(), ['id' => 'bid_id']);
    }

    public function beforeValidate()
    {
        $this->invoice_date = DateHelper::convert($this->invoice_date);
        $maxNumOrder = self::find()->where(['bid_id' => $this->bid_id])->max('num_order');
        $this->num_order = $maxNumOrder + 1;
        return parent::beforeValidate();
    }
}
