<?php

namespace app\models;

use app\helpers\common\DateHelper;
use app\helpers\constants\Constants;
use app\models\form\MultipleUploadForm;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\Application;

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
 * @property string $composition_name_manufacturer
 * @property string $defect
 * @property string $diagnostic
 * @property string $serial_number
 * @property string $vendor_code
 * @property int $client_id
 * @property int $client_manufacturer_id
 * @property string $treatment_type
 * @property string $purchase_date
 * @property string $application_date
 * @property string $created_at
 * @property string $updated_at
 * @property string $warranty_number
 * @property string $bid_number
 * @property string $bid_1C_number
 * @property string $bid_manufacturer_number
 * @property string $condition_name
 * @property string $condition_manufacturer_name
 * @property int $repair_status_id
 * @property int $warranty_status_id
 * @property int $status_id
 * @property string $guid
 * @property bool $flag_export
 * @property int $master_id
 * @property string $comment
 * @property string $repair_recommendations
 * @property string $saler_name
 * @property string $diagnostic_manufacturer
 * @property string $defect_manufacturer
 * @property string $date_manufacturer
 * @property string $date_completion
 * @property string $date_completion_manufacturer
 * @property bool $is_warranty_defect
 * @property bool $is_repair_possible
 * @property bool $is_for_warranty
 * @property int $workshop_id
 * @property int $agency_id
 * @property int $decision_workshop_status_id
 * @property int $decision_agency_status_id
 * @property string $comment_1
 * @property string $comment_2
 * @property string $manager
 * @property string $manager_contact
 * @property string $manager_presale
 * @property bool $is_reappeal
 * @property string $document_reappeal
 * @property string $subdivision
 * @property string $repair_status_date
 * @property int $repair_status_author_id
 * @property string $author
 * @property float $sum_manufacturer
 * @property bool $is_control
 * @property bool $is_report
 * @property bool $is_warranty
 * @property string $warranty_comment
 * @property string $equipment_manufacturer
 *
 *
 * @property Brand $brand
 * @property BrandCorrespondence $brandCorrespondence
 * @property Client $client
 * @property Manufacturer $manufacturer
 * @property BrandModel $brandModel
 * @property BidHistory[] $bidHistories
 */
class Bid extends \yii\db\ActiveRecord implements TranslatableInterface
{
    const TREATMENT_TYPE_WARRANTY = 'warranty';
    const TREATMENT_TYPE_PRESALE = 'pre-sale';

    const UNDEFINED_EQUIPMENT = 'Оборудование не задано';

    const TREATMENT_TYPES = [
        self::TREATMENT_TYPE_WARRANTY => 'Гарантия',
        self::TREATMENT_TYPE_PRESALE => 'Предпродажа',
    ];

    const EDITABLE_ATTRIBUTES = [
        'brand_model_name' => 'Модель',
        'composition_name' => 'Комплектность',
        'composition_name_manufacturer' => 'Комплектность для представительства',
        'serial_number' => 'Серийный номер',
        'vendor_code' => 'Артикул',
        'treatment_type' => 'Тип обращения',
        'purchase_date' => 'Дата покупки',
        'warranty_number' => 'Номер гарантийного талона',
        'bid_1C_number' => 'Номер заявки в 1С',
        'bid_manufacturer_number' => 'Номер заявки у представительства',
        'condition_name' => 'Состояние',
        'condition_manufacturer_name'  => 'Состояние для представительства',
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
        'date_completion_manufacturer' => 'Дата готовности для представительства',
        'is_warranty_defect' => 'Дефект гарантийный',
        'is_repair_possible' => 'Проведение ремонта возможно',
        'is_for_warranty' => 'Подано на гарантию',
        'comment_1' => 'Комментарий',
        'comment_2' => 'Пояснения',
        'manager' => 'Менеджер',
        'manager_contact' => 'Менеджер контакт',
        'manager_presale' => 'Менеджер предпродажи',
        'is_reappeal' => 'Повторное обращение',
        'document_reappeal' => 'Документ повторного обращения',
        'subdivision' => 'Подразделение',
        'repair_status_date' => 'Дата изменения статуса ремонта',
        'repair_status_author_id' => 'Автор изменения статуса ремонта',
        'author' => 'Автор',
        'sum_manufacturer' => 'Сумма для представительства',
        'is_control' => 'Контроль',
        'is_report' => 'Отчет',
        'is_warranty' => 'Гарантийный ремонт',
        'warranty_comment' => 'Комментарий гарантии',
        'equipment' => 'Оборудование',
        'equipment_manufacturer' => 'Оборудование для представительства',
        'bid_number' => 'Номер заявки',
        'application_date' => 'Дата обращения',
        'created_at' => 'Создана',
        'updated_at' => 'Изменена',
        'decision_workshop_status_id' => 'Решение мастерской',
        'decision_agency_status_id' => 'Решение представительства',
        'client_id' => 'Клиент',
        'client_manufacturer_id' => 'Клиент для представительства',
    ];

    const ALWAYS_VISIBLE_ATTRIBUTES = [
        'brand_id' => 'brand_id',
        'brand_model_id' => 'brand_model_id',
        'compositionCombined' => 'compositionCombined',
        'workshop_id' => 'workshop_id',
        'manufacturer_id' => 'Производитель',
        'brand_name' => 'Бренд',
        'status_id' => 'Статус',
    ];


    const EXCHANGE_1C_ATTRIBUTES = [
        'id' => 'Идентификатор на портале',
        'guid' => 'GUID',
        'bid_1C_number' => 'Номер заявки в 1С',
        'updated_at' => 'Дата изменения заявки',
        'client_id' => 'Клиент портал id',
        'client_manufacturer_id' => 'Клиент портал id для представительства',
        'client_guid' => 'Клиент GUID',
        'created_at' => 'Дата создания заявки',
        'status_id' => 'Статус',
        'repair_status_id' => 'Статус ремонта',
        'equipment' => 'Оборудование',
        'brand_name' => 'Бренд',
        'brand_model_name' => 'Модель',
        'serial_number'  => 'Серийный номер',
        'condition_name' => 'Состояние',
        'condition_manufacturer_name'  => 'Состояние для представительства',
        'composition_name' => 'Комплектность',
        'composition_name_manufacturer' => 'Комплектность для представительства',
        'defect' => 'Заявленная неисправность',
        'comment' => 'Дополнительные отметки',
        'treatment_type' => 'Товар на гарантии',
        'warranty_number' => 'Номер гарантийного талона',
        'purchase_date'  => 'Дата покупки',
        'application_date' => 'Дата обращения',
        'master_name' => 'Мастер',
        'master_uuid' => 'Мастер Uuid',
        'user_id' => 'Приемщик',
        'diagnostic' => 'Результат диагностики',
        'repair_recommendations'  => 'Рекомендации по ремонту',
        'manufacturer_id' => 'Производитель',
        'vendor_code' => 'Артикул',
        'saler_name' => 'Продавец',
        'diagnostic_manufacturer'  => 'Результат диагностики для представительства',
        'defect_manufacturer'  => 'Заявленная неисправность для представительства',
        'bid_manufacturer_number' => 'Номер заявки у представительства',
        'warranty_status_id' => 'Статус гарантии',
        'date_manufacturer' => 'Дата принятия в ремонт для представительства',
        'date_completion' => 'Дата готовности',
        'date_completion_manufacturer' => 'Дата готовности для представительства',
        'is_warranty_defect' => 'Дефект гарантийный',
        'is_repair_possible' => 'Проведение ремонта возможно',
        'is_for_warranty' => 'Подано на гарантию',
        'decision_workshop_status_id' => 'Решение мастерской',
        'decision_agency_status_id' => 'Решение представительства',
        'comment_1' => 'Комментарий',
        'comment_2' => 'Пояснения',
        'manager' => 'Менеджер',
        'manager_contact' => 'Менеджер контакт',
        'manager_presale' => 'Менеджер предпродажи',
        'is_reappeal' => 'Повторное обращение',
        'document_reappeal' => 'Документ повторного обращения',
        'subdivision' => 'Подразделение',
        'repair_status_date' => 'Дата изменения статуса ремонта',
        'repair_status_author_id' => 'Автор изменения статуса ремонта',
        'author' => 'Автор',
        'sum_manufacturer' => 'Сумма для представительства',
        'is_control' => 'Контроль',
        'is_report' => 'Отчет',
        'is_warranty' => 'Гарантийный ремонт',
        'warranty_comment' => 'Комментарий гарантии',
        'agency_id' => 'Представительство'
    ];

    const GRID_ATTRIBUTES = [
        'equipment' => ['desktop' => true, 'tablet' => true, 'phone' => true],
        'equipment_manufacturer' => ['desktop' => false, 'tablet' => false, 'phone' => false],
        'bid_1C_number'  => ['desktop' => true, 'tablet' => true, 'phone' => true],
        'bid_number'  => ['desktop' => false, 'tablet' => false, 'phone' => false],
        'client_id'  => ['desktop' => true, 'tablet' => true, 'phone' => true],
        'client_manufacturer_id'  => ['desktop' => false, 'tablet' => false, 'phone' => false],
        'master_id'  => ['desktop' => true, 'tablet' => false, 'phone' => false],
        'condition_name'  => ['desktop' => false, 'tablet' => false, 'phone' => false],
        'condition_manufacturer_name'  => ['desktop' => false, 'tablet' => false, 'phone' => false],
        'brand_name'  => ['desktop' => false, 'tablet' => false, 'phone' => false],
        'brand_model_name'  => ['desktop' => false, 'tablet' => false, 'phone' => false],
        'composition_name'  => ['desktop' => false, 'tablet' => false, 'phone' => false],
        'composition_name_manufacturer'  => ['desktop' => false, 'tablet' => false, 'phone' => false],
        'created_at'  => ['desktop' => true, 'tablet' => true, 'phone' => false],
        'status_id'  => ['desktop' => true, 'tablet' => true, 'phone' => true],
        'repair_status_id'  => ['desktop' => true, 'tablet' => false, 'phone' => false],
        'treatment_type'  => ['desktop' => true, 'tablet' => false, 'phone' => false],
        'warranty_status_id'  => ['desktop' => true, 'tablet' => false, 'phone' => false],
        'manufacturer_id'  => ['desktop' => true, 'tablet' => true, 'phone' => false]
    ];

    /**
     * @return array[]
     */
    public static function autoFilledAttributes()
    {
        return [
            'is_warranty_defect' => ['options' => Constants::BOOLEAN],
            'is_repair_possible' => ['options' => Constants::BOOLEAN],
            'is_for_warranty' => ['options' => Constants::BOOLEAN],
            'warranty_status_id' => ['options' => WarrantyStatus::warrantyStatusAsMap()],
            'repair_status_id'  => ['options' => RepairStatus::repairStatusAsMap()],
        ];
    }

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

    public function fields()
    {
        return [
            'id',
            'bid_1C_number',
            'equipment',
            'created_at' => function(Bid $bid) {
                return DateHelper::getReadableDate($bid->created_at);
            },
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['brand_name'], 'required'],
            [[
                'manufacturer_id',
                'brand_id',
                'brand_correspondence_id',
                'brand_model_id',
                'composition_id',
                'client_id',
                'client_manufacturer_id',
                'repair_status_id',
                'warranty_status_id',
                'status_id',
                'user_id',
                'master_id',
                'agency_id',
                'decision_workshop_status_id',
                'decision_agency_status_id',
                'repair_status_author_id',
            ], 'integer'],
            [['sum_manufacturer'], 'number'],
            [[
                'composition_table',
                'treatment_type',
                'compositionCombined',
                'brand_name',
                'guid',
                'comment',
                'repair_recommendations',
                'saler_name',
                'diagnostic_manufacturer',
                'defect_manufacturer',
                'comment_1',
                'comment_2',
            ], 'string'],
            [[
                'is_warranty_defect',
                'is_repair_possible',
                'is_for_warranty',
                'is_reappeal',
                'is_control',
                'is_report',
                'is_warranty'
            ], 'boolean'],
            [[
                'application_date',
                'created_at',
                'updated_at',
                'date_manufacturer',
                'date_completion',
                'date_completion_manufacturer',
                'repair_status_date',
            ], 'safe'],
            [[
                'brand_model_name',
                'composition_name',
                'composition_name_manufacturer',
                'serial_number',
                'vendor_code',
                'warranty_number',
                'bid_number',
                'bid_1C_number',
                'bid_manufacturer_number',
                'equipment',
                'equipment_manufacturer',
                'condition_name',
                'condition_manufacturer_name',
                'defect',
                'diagnostic',
                'manager',
                'manager_contact',
                'manager_presale',
                'document_reappeal',
                'subdivision',
                'author',
                'warranty_comment',
            ], 'string', 'max' => 255],
            ['purchase_date', 'BeforeApplicationDateValidate'],
            [['brand_id'], 'exist', 'skipOnError' => true, 'targetClass' => Brand::className(), 'targetAttribute' => ['brand_id' => 'id']],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => Client::className(), 'targetAttribute' => ['client_id' => 'id']],
            [['client_manufacturer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Client::className(), 'targetAttribute' => ['client_manufacturer_id' => 'id']],
            [['manufacturer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Manufacturer::className(), 'targetAttribute' => ['manufacturer_id' => 'id']],
            [['repair_status_id'], 'exist', 'skipOnError' => true, 'targetClass' => RepairStatus::className(), 'targetAttribute' => ['repair_status_id' => 'id']],
            [['warranty_status_id'], 'exist', 'skipOnError' => true, 'targetClass' => WarrantyStatus::className(), 'targetAttribute' => ['warranty_status_id' => 'id']],
            [['decision_workshop_status_id'], 'exist', 'skipOnError' => true, 'targetClass' => DecisionWorkshopStatus::className(), 'targetAttribute' => ['decision_workshop_status_id' => 'id']],
            [['decision_agency_status_id'], 'exist', 'skipOnError' => true, 'targetClass' => DecisionAgencyStatus::className(), 'targetAttribute' => ['decision_agency_status_id' => 'id']],
            ['treatment_type', 'default', 'value' => null],
            ['treatment_type', 'in', 'range' => array_keys(self::TREATMENT_TYPES)],
        ];
    }

    public static function getAllAttributes()
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
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return $this->getAllAttributes();
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
        return $this->hasOne(Client::className(), ['id' => 'client_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClientManufacturer()
    {
        return $this->hasOne(Client::className(), ['id' => 'client_manufacturer_id']);
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
    public function getSpares()
    {
        return $this->hasMany(Spare::className(), ['bid_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReplacementParts()
    {
        return $this->hasMany(ReplacementPart::className(), ['bid_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClientPropositions()
    {
        return $this->hasMany(ClientProposition::className(), ['bid_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJobs()
    {
        return $this->hasMany(BidJob::className(), ['bid_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJobs1c()
    {
        return $this->hasMany(BidJob1c::className(), ['bid_id' => 'id']);
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
    public function getBidPrivateComments()
    {
        return $this->hasMany(BidComment::className(), ['bid_id' => 'id'])->where(['bid_comment.private' => true]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBidAgencyComments()
    {
        return $this->hasMany(BidComment::className(), ['bid_id' => 'id'])->where(['bid_comment.private' => false]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBidPublicComments()
    {
        return $this->hasMany(BidComment::className(), ['bid_id' => 'id'])->where(['bid_comment.private' => false]);
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

    /**
     * @return ActiveRecord|AgencyWorkshop
     */
    public function getAgencyWorkshop()
    {
        return AgencyWorkshop::find()->where(['agency_id' => $this->agency_id, 'workshop_id' => $this->workshop_id])->one();
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
        $this->date_completion = DateHelper::convert($this->date_completion);
        $this->date_completion_manufacturer = DateHelper::convert($this->date_completion_manufacturer);
        $this->purchase_date = DateHelper::convert($this->purchase_date);
        $this->date_manufacturer = DateHelper::convert($this->date_manufacturer);

        return parent::beforeValidate();
    }

    public function beforeSave($insert)
    {
        $this->fillFieldsForAgency();

        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $oldRepairStatusId = isset($changedAttributes['repair_status_id']) ? $changedAttributes['repair_status_id'] : null;
        if ($this->repair_status_id != $oldRepairStatusId) {
            $repairStatusAuthorId = \Yii::$app instanceof \yii\console\Application ? null : \Yii::$app->user->id;
            $repairStatusDate = date('Y-m-d');
            if ($repairStatusAuthorId && (
                $this->repair_status_author_id != $repairStatusAuthorId || $this->repair_status_date != $repairStatusDate)) {
                $this->repair_status_author_id = $repairStatusAuthorId;
                $this->repair_status_date = $repairStatusDate;
                $this->save(false);
            }
        }

        $this->performAutoFill($changedAttributes);
    }

    private function performAutoFill($changedAttributes)
    {
        $oldDecisionWorkshop = isset($changedAttributes['decision_workshop_status_id']) ? $changedAttributes['decision_workshop_status_id'] : null;
        $oldDecisionAgency = isset($changedAttributes['decision_agency_status_id']) ? $changedAttributes['decision_agency_status_id'] : null;
        $oldStatus = isset($changedAttributes['status_id']) ? $changedAttributes['status_id'] : null;

        if ($oldDecisionWorkshop != $this->decision_workshop_status_id
            || $oldDecisionAgency != $this->decision_agency_status_id
            || $oldStatus != $this->status_id
        ) {
            $autoFillAttributesModels = AutoFillAttributes::find()->all();

            $initialAttributes = [];

            foreach ($autoFillAttributesModels as $autoFillAttributesModel) {
                /* @var $autoFillAttributesModel AutoFillAttributes */
                if (
                    (is_null($autoFillAttributesModel->decision_agency_status_id)
                    || $autoFillAttributesModel->decision_agency_status_id == $this->decision_workshop_status_id)
                    &&
                    (is_null($autoFillAttributesModel->decision_workshop_status_id)
                        || $autoFillAttributesModel->decision_workshop_status_id == $this->decision_workshop_status_id)
                    &&
                    (is_null($autoFillAttributesModel->status_id)
                        || $autoFillAttributesModel->status_id == $this->status_id)
                    ) {
                    foreach ($autoFillAttributesModel->auto_fill as $attribute => $value) {
                        if ($value !== null && $value !== '' && $this->$attribute != $value) {
                            if (!isset($initialAttributes[$attribute])) {
                                $initialAttributes[$attribute] = $this->$attribute;
                            }
                            $this->$attribute = $value;
                        }
                    }
                }
            }

            foreach ($initialAttributes as $attribute => $value) {
                if ($this->$attribute != $value) {
                    $this->save(false);
                    break;
                }
            }
        }
    }

    public function BeforeApplicationDateValidate($attribute, $params, $validator)
    {
        if (!empty($this->$attribute)) {
            $checkDate = $this->application_date ?: date('Y-m-d');
            if ($this->$attribute > $checkDate) {
                $this->addError($attribute, 'Дата не может быть позже даты обращения');
            }
        }
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

    public function createBid($userId, MultipleUploadForm $uploadForm)
    {
        $transaction = \Yii::$app->db->beginTransaction();

        try {
            $this->setWorkshop($userId);
            $this->checkBrandCorrespondence();
            if ($this->save()) {
                $bidHistory = new BidHistory([
                    'bid_id' => $this->id,
                    'user_id' => $userId,
                    'action' => BidHistory::CREATED
                ]);
                $result = $bidHistory->save();
                if ($result === false) {
                    \Yii::error($bidHistory->getErrors());
                    $transaction->rollBack();
                } else {
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

    public function fillFieldsForAgency()
    {
        $this->copySimilarFields('diagnostic', 'diagnostic_manufacturer');
        $this->copySimilarFields('defect', 'defect_manufacturer');
        $this->copySimilarFields('equipment', 'equipment_manufacturer', Bid::UNDEFINED_EQUIPMENT);
        $this->copySimilarFields('date_completion', 'date_completion_manufacturer');
        $this->copySimilarFields('composition_name', 'composition_name_manufacturer');
        $this->copySimilarFields('condition_name', 'condition_manufacturer_name');
        $this->copySimilarFields('client_id', 'client_manufacturer_id');

        $this->date_manufacturer = $this->date_manufacturer ?: date('Y-m-d H:i:s');
    }

    public function copySimilarFields($sample, $item, $emptyField = null)
    {
        if (!empty($this->$sample) && (empty($this->$item) || $this->$item === $emptyField)) {
            $this->$item = $this->$sample;
        } elseif (!empty($this->$item) && (empty($this->$item) || $this->$item === $emptyField)) {
            $this->$sample = $this->$item;
        }
    }

    public static function nextTreatmentType($type)
    {
        return $type === self::TREATMENT_TYPE_WARRANTY
            ? self::TREATMENT_TYPE_PRESALE
            : self::TREATMENT_TYPE_WARRANTY;
    }
}
