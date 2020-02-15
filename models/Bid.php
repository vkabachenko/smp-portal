<?php

namespace app\models;

use app\models\form\CommentForm;
use app\models\form\MultipleUploadForm;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "bid".
 *
 * @property int $id
 * @property int $user_id
 * @property int $manufacturer_id
 * @property int $brand_id
 * @property int $brand_correspondence_id
 * @property string $brand_name
 * @property string $equipment
 * @property int $brand_model_id
 * @property string $brand_model_name
 * @property int $composition_id
 * @property string $composition_table
 * @property string $composition_name
 * @property string $defect
 * @property string $diagnostic
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
 * @property int $repair_status_id
 * @property int $warranty_status_id
 * @property int $status_id
 * @property string $guid
 * @property bool $flag_export
 * @property int $master_id
 * @property string $client_type
 * @property string $comment
 * @property string $repair_recommendations
 * @property string $saler_name
 * @property string $diagnostic_manufacturer
 * @property string $defect_manufacturer
 * @property string $date_manufacturer
 * @property string $date_completion
 * @property bool $is_warranty_defect
 * @property bool $is_repair_possible
 * @property bool $is_for_warranty
 * @property int $workshop_id
 * @property int $agency_id
 * @property int $decision_workshop_status_id
 * @property int $decision_agency_status_id
 *
 * @property Condition $condition
 * @property Brand $brand
 * @property BrandCorrespondence $brandCorrespondence
 * @property User $client
 * @property Manufacturer $manufacturer
 * @property BrandModel $brandModel
 * @property BidHistory[] $bidHistories
 */
class Bid extends \yii\db\ActiveRecord implements TranslatableInterface
{

    const TREATMENT_TYPE_WARRANTY = 'warranty';
    const TREATMENT_TYPE_PRESALE = 'pre-sale';

    const TREATMENT_TYPES = [
        self::TREATMENT_TYPE_WARRANTY => 'Гарантия',
        self::TREATMENT_TYPE_PRESALE => 'Предпродажа',
    ];

    const CLIENT_TYPE_PERSON = 'person';
    const CLIENT_TYPE_LEGAL_ENTITY = 'legal_entity';

    const CLIENT_TYPES = [
      self::CLIENT_TYPE_PERSON => 'Физическое лицо',
      self::CLIENT_TYPE_LEGAL_ENTITY => 'Юридическое лицо'
    ];

    const EDITABLE_ATTRIBUTES = [
        'brand_model_name' => 'Модель',
        'composition_name' => 'Комплектность',
        'serial_number' => 'Серийный номер',
        'vendor_code' => 'Артикул',
        'client_phone' => 'Телефон клиента',
        'client_address' => 'Адрес клиента',
        'treatment_type' => 'Тип обращения',
        'purchase_date' => 'Дата покупки',
        'warranty_number' => 'Номер гарантийного талона',
        'bid_1C_number' => 'Номер заявки в 1С',
        'bid_manufacturer_number' => 'Номер заявки у представительства',
        'condition_id' => 'Состояние',
        'defect' => 'Заявленная неисправность',
        'diagnostic' => 'Результат диагностики',
        'repair_status_id' => 'Статус ремонта',
        'warranty_status_id' => 'Статус гарантии',
        'user_id' => 'Приемщик',
        'master_id' => 'Мастер',
        'comment' => 'Дополнительные отметки',
        'repair_recommendations' => 'Рекомендации по ремонту',
        'saler_name' => 'Продавец',
        'diagnostic_manufacturer' => 'Результат диагностики для представительства',
        'defect_manufacturer' => 'Заявленная неисправность для представительства',
        'date_manufacturer' => 'Дата принятия в ремонт для представительства',
        'date_completion' => 'Дата готовности',
        'is_warranty_defect' => 'Дефект гарантийный',
        'is_repair_possible' => 'Проведение ремонта возможно',
        'is_for_warranty' => 'Подано на гарантию'
    ];

    const ALWAYS_VISIBLE_ATTRIBUTES = [
        'created_at' => 'Создана',
        'updated_at' => 'Изменена',
        'manufacturer_id' => 'Производитель',
        'brand_name' => 'Бренд',
        'client_type' => 'Тип клиента',
        'client_name' => 'Клиент',
        'equipment' => 'Оборудование',
        'decision_workshop_status_id' => 'Решение мастерской',
        'decision_agency_status_id' => 'Решение представительства',
        'status_id' => 'Статус',
        'bid_number' => 'Номер заявки',
        'application_date' => 'Дата обращения',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bid';
    }

    public static function translateName()
    {
        return 'Заявка';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'preserveNonEmptyValues' => true,
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
            [['brand_name', 'client_type'], 'required'],
            [[
                'manufacturer_id',
                'brand_id',
                'brand_correspondence_id',
                'brand_model_id',
                'composition_id',
                'client_id',
                'condition_id',
                'repair_status_id',
                'warranty_status_id',
                'status_id',
                'user_id',
                'master_id',
                'agency_id',
                'decision_workshop_status_id',
                'decision_agency_status_id',
            ], 'integer'],
            [[
                'composition_table',
                'treatment_type',
                'compositionCombined',
                'brand_name',
                'guid',
                'client_type',
                'comment',
                'repair_recommendations',
                'saler_name',
                'diagnostic_manufacturer',
                'defect_manufacturer'
            ], 'string'],
            [[
                'is_warranty_defect',
                'is_repair_possible',
                'is_for_warranty',
            ], 'boolean'],
            [[
                'purchase_date',
                'application_date',
                'created_at',
                'updated_at',
                'date_manufacturer',
                'date_completion'
            ], 'safe'],
            [[
                'brand_model_name',
                'composition_name',
                'serial_number',
                'vendor_code',
                'client_name',
                'client_phone',
                'client_address',
                'warranty_number',
                'bid_number',
                'bid_1C_number',
                'bid_manufacturer_number',
                'equipment',
                'defect',
                'diagnostic'
            ], 'string', 'max' => 255],
            [['condition_id'], 'exist', 'skipOnError' => true, 'targetClass' => Condition::className(), 'targetAttribute' => ['condition_id' => 'id']],
            [['brand_id'], 'exist', 'skipOnError' => true, 'targetClass' => Brand::className(), 'targetAttribute' => ['brand_id' => 'id']],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['client_id' => 'id']],
            [['manufacturer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Manufacturer::className(), 'targetAttribute' => ['manufacturer_id' => 'id']],
            [['repair_status_id'], 'exist', 'skipOnError' => true, 'targetClass' => RepairStatus::className(), 'targetAttribute' => ['repair_status_id' => 'id']],
            [['warranty_status_id'], 'exist', 'skipOnError' => true, 'targetClass' => WarrantyStatus::className(), 'targetAttribute' => ['warranty_status_id' => 'id']],
            [['decision_workshop_status_id'], 'exist', 'skipOnError' => true, 'targetClass' => DecisionWorkshopStatus::className(), 'targetAttribute' => ['decision_workshop_status_id' => 'id']],
            [['decision_agency_status_id'], 'exist', 'skipOnError' => true, 'targetClass' => DecisionAgencyStatus::className(), 'targetAttribute' => ['decision_agency_status_id' => 'id']],
            ['treatment_type', 'default', 'value' => null],
            ['treatment_type', 'in', 'range' => array_keys(self::TREATMENT_TYPES)],
            ['client_type', 'default', 'value' => null],
            ['client_type', 'in', 'range' => array_keys(self::CLIENT_TYPES)],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        $keyCache = 'bid-custom-labels';
        $customLabels = \Yii::$app->cache->get($keyCache);

        if ($customLabels === false) {
            $customLabels = BidAttribute::find()
                ->active()
                ->select(['short_description', 'attribute'])
                ->andWhere(['>', 'short_description', ''])
                ->indexBy('attribute')
                ->column();
            \Yii::$app->cache->set($keyCache, $customLabels);
        }

        return  array_merge(self::EDITABLE_ATTRIBUTES, self::ALWAYS_VISIBLE_ATTRIBUTES, $customLabels);
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
    public function getBrandCorrespondence()
    {
        return $this->hasOne(BrandCorrespondence::className(), ['id' => 'brand_correspondence_id']);
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
    public function getRepairStatus()
    {
        return $this->hasOne(RepairStatus::className(), ['id' => 'repair_status_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(BidStatus::className(), ['id' => 'status_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMaster()
    {
        return $this->hasOne(Master::className(), ['id' => 'master_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorkshop()
    {
        return $this->hasOne(Workshop::className(), ['id' => 'workshop_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWarrantyStatus()
    {
        return $this->hasOne(WarrantyStatus::className(), ['id' => 'warranty_status_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDecisionWorkshopStatus()
    {
        return $this->hasOne(DecisionWorkshopStatus::className(), ['id' => 'decision_workshop_status_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDecisionAgencyStatus()
    {
        return $this->hasOne(DecisionAgencyStatus::className(), ['id' => 'decision_agency_status_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBidHistories()
    {
        return $this->hasMany(BidHistory::className(), ['bid_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBidComments()
    {
        return $this->hasMany(BidComment::className(), ['bid_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBidImages()
    {
        return $this->hasMany(BidImage::className(), ['bid_id' => 'id']);
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

    public function isWarranty()
    {
        return ($this->treatment_type === self::TREATMENT_TYPE_WARRANTY) || is_null($this->treatment_type);
    }

    public function isPaid()
    {
        return $this->treatment_type === self::TREATMENT_TYPE_PRESALE;
    }

    public function beforeValidate()
    {
        if (empty($this->brand_id)) {
            $this->manufacturer_id = null;
        }
        if (empty($this->equipment)) {
            $this->equipment = 'Оборудование не задано';
        }

        return parent::beforeValidate();
    }

    public function checkBrandCorrespondence()
    {
        if ($this->brand_id) {
            return;
        }
        $brandCorrespondence = BrandCorrespondence::findByName($this->brand_name);
        if (is_null($brandCorrespondence)) {
            $brandCorrespondence = new BrandCorrespondence([
                'name' => $this->brand_name,
                'brand_id' => null
            ]);
            $brandCorrespondence->save();
        }
        $this->brand_correspondence_id = $brandCorrespondence->id;
    }

    public function createBid($userId, MultipleUploadForm $uploadForm, CommentForm $commentForm)
    {
        $transaction = \Yii::$app->db->beginTransaction();

        try {
            $this->setWorkshop($userId);
            $this->checkBrandCorrespondence();
            if ($this->save()) {
                $bidHistory = new BidHistory([
                    'bid_id' => $this->id,
                    'user_id' => $userId,
                    'action' => 'Создана'
                ]);
                $result = $bidHistory->save();
                if ($result === false) {
                    \Yii::error($bidHistory->getErrors());
                    $transaction->rollBack();
                } else {
                    BidComment::createFromForm($commentForm, $this->id, $userId);
                    $uploadForm->upload(['bid_id' => $this->id, 'user_id' => $userId,]);
                    $transaction->commit();
                }
                return $result;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            \Yii::error($e->getMessage());
            $transaction->rollBack();
            return false;
        }
    }

    public static function setFlagExport($id, $flagValue)
    {
        $model = self::findOne($id);
        if ($model) {
            $model->flag_export = $flagValue;
            if (!$model->save(false)) {
                \Yii::error($model->getErrors());
            }
        }
    }

    public function setWorkshop($userId)
    {
        $master = Master::findByUserId($userId);
        if ($master) {
            $this->workshop_id = $master->workshop->id;
        }
    }

    public function isBrandName() {
        if ($this->brand_name === 'Нет бренда' || empty($this->brand_name)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @return Agency|null
     */
    public function getAgency()
    {
        if (!$this->agency_id) {
            $agencies = Agency::find()->where(['manufacturer_id' => $this->manufacturer_id])->all();
            $agenciesWorkshop = $this->workshop->agencies;

            $intersect = array_uintersect($agencies, $agenciesWorkshop, function(Agency $v1, Agency $v2) {
                return $v1->id - $v2->id;
            });
            if (empty($intersect)) {
                return null;
            } else {
                $firstAgency = reset($intersect);
                $this->agency_id = $firstAgency->id;
                if (!$this->save()) {
                    \Yii::error($this->getErrors());
                    throw new \DomainException('Fail to set agency_id to bid');
                }
            }
        }
        $agency = Agency::findOne($this->agency_id);
        if (is_null($agency)) {
            throw new \DomainException('Fail to find agency by agency_id = ' . $this->agency_id);
        }
        return $agency;
    }

    public function setStatus($status)
    {
        $statusId = BidStatus::getId($status);

        if (is_null($statusId)) {
            throw new \DomainException('setting unknown status ' . $status);
        }

        $this->status_id = $statusId;

        if (!$this->save()) {
            \Yii::error($this->getErrors());
            throw new \DomainException('fail to save bid');
        }
    }

    public function isViewed(User $user)
    {
        return $user->role === 'admin'
            || $this->status_id === BidStatus::getId(BidStatus::STATUS_FILLED)
            || $this->status_id === BidStatus::getId(BidStatus::STATUS_DONE)
            || $this->status_id === BidStatus::getId(BidStatus::STATUS_READ_AGENCY)
            || $this->status_id === BidStatus::getId(BidStatus::STATUS_READ_WORKSHOP)
            || ($user->role === 'manager' &&  $this->status_id === BidStatus::getId(BidStatus::STATUS_SENT_AGENCY))
            || ($user->role === 'master' &&  $this->status_id === BidStatus::getId(BidStatus::STATUS_SENT_WORKSHOP));

    }


}
