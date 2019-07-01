<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "bid".
 *
 * @property int $id
 * @property int $manufacturer_id
 * @property int $brand_id
 * @property string $brand_name
 * @property string $equipment
 * @property int $brand_model_id
 * @property string $brand_model_name
 * @property int $composition_id
 * @property string $composition_table
 * @property string $composition_name
 * @property string $serial_number
 * @property string $vendor_code
 * @property int $client_id
 * @property string $client_name
 * @property string $client_phone
 * @property string $client_address
 * @property string $treatment_type
 * @property string $purchase_date
 * @property string $application_date
 * @property string $created_at
 * @property string $updated_at
 * @property string $warranty_number
 * @property string $bid_number
 * @property string $bid_1C_number
 * @property string $bid_manufacturer_number
 * @property int $condition_id
 *
 * @property Condition $condition
 * @property Brand $brand
 * @property User $client
 * @property Manufacturer $manufacturer
 * @property BrandModel $brandModel
 * @property BidHistory[] $bidHistories
 */
class Bid extends \yii\db\ActiveRecord
{

    const TREATMENT_TYPE_WARRANTY = 'warranty';
    const TREATMENT_TYPE_PRESALE = 'pre-sale';

    const TREATMENT_TYPES = [
        self::TREATMENT_TYPE_WARRANTY => 'Гарантия',
        self::TREATMENT_TYPE_PRESALE => 'Предпродажа',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bid';
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
            [['manufacturer_id', 'brand_name'], 'required'],
            [['manufacturer_id', 'brand_id', 'brand_model_id', 'composition_id', 'client_id', 'condition_id'], 'integer'],
            [['composition_table', 'treatment_type', 'compositionCombined', 'brand_name'], 'string'],
            [['purchase_date', 'application_date', 'created_at', 'updated_at'], 'safe'],
            [['brand_model_name', 'composition_name', 'serial_number', 'vendor_code', 'client_name', 'client_phone', 'client_address', 'warranty_number', 'bid_number', 'bid_1C_number', 'bid_manufacturer_number', 'equipment'], 'string', 'max' => 255],
            [['condition_id'], 'exist', 'skipOnError' => true, 'targetClass' => Condition::className(), 'targetAttribute' => ['condition_id' => 'id']],
            [['brand_id'], 'exist', 'skipOnError' => true, 'targetClass' => Brand::className(), 'targetAttribute' => ['brand_id' => 'id']],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['client_id' => 'id']],
            [['manufacturer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Manufacturer::className(), 'targetAttribute' => ['manufacturer_id' => 'id']],
            ['treatment_type', 'default', 'value' => null],
            ['treatment_type', 'in', 'range' => array_keys(self::TREATMENT_TYPES)],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'manufacturer_id' => 'Производитель',
            'brand_id' => 'Бренд',
            'brand_name' => 'Бренд',
            'brand_model_id' => 'Модель - список',
            'brand_model_name' => 'Модель',
            'composition_id' => 'Комплектность',
            'composition_table' => 'Composition Table',
            'composition_name' => 'Комплектность',
            'serial_number' => 'Серийный номер',
            'vendor_code' => 'Артикул',
            'client_id' => 'Client ID',
            'client_name' => 'ФИО клиента',
            'client_phone' => 'Телефон клиента',
            'client_address' => 'Адрес клиента',
            'treatment_type' => 'Тип обращения',
            'treatmentTypeName' => 'Тип обращения',
            'purchase_date' => 'Дата покупки',
            'application_date' => 'Дата обращения',
            'created_at' => 'Создана',
            'updated_at' => 'Updated At',
            'warranty_number' => 'Номер гарантийного талона',
            'bid_number' => 'Номер заявки',
            'bid_1C_number' => 'Номер заявки в 1С',
            'bid_manufacturer_number' => 'Номер заявки у производителя',
            'condition_id' => 'Состояние',
            'equipment' => 'Оборудование'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCondition()
    {
        return $this->hasOne(Condition::className(), ['id' => 'condition_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrand()
    {
        return $this->hasOne(Brand::className(), ['id' => 'brand_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrandModel()
    {
        return $this->hasOne(BrandModel::className(), ['id' => 'brand_model_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(User::className(), ['id' => 'client_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManufacturer()
    {
        return $this->hasOne(Manufacturer::className(), ['id' => 'manufacturer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBidHistories()
    {
        return $this->hasMany(BidHistory::className(), ['bid_id' => 'id']);
    }

    public function getCompositionCombined()
    {
        return is_null($this->composition_id) ? null : strval($this->composition_table) . '-' . strval($this->composition_id);
    }

    public function setCompositionCombined($value)
    {
        $combined = explode('-', $value);
        if (count($combined) !== 2) {
            $this->composition_id = null;
            $this->composition_table = null;
        } else {
            $this->composition_id = $combined[1];
            $this->composition_table = $combined[0];
        }
    }

    public function getTreatmentTypeName()
    {
        return is_null($this->treatment_type) ? '' : self::TREATMENT_TYPES[$this->treatment_type];
    }

    public function createBid($userId)
    {
        $transaction = \Yii::$app->db->beginTransaction();

        try {
            if ($this->save()) {
                $bidHistory = new BidHistory([
                    'bid_id' => $this->id,
                    'user_id' => $userId
                ]);
                $result = $bidHistory->save();
                if ($result === false) {
                    \Yii::error($bidHistory->getErrors());
                    $transaction->rollBack();
                } else {
                    $transaction->commit();
                }
                return $result;
            } else {
                return false;
            }
        } catch (\Throwable $e) {
            \Yii::error($e->getMessage());
            $transaction->rollBack();
            return false;
        }
    }


}
