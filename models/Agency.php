<?php

namespace app\models;

use app\models\form\UploadExcelTemplateForm;
use Yii;
use yii\db\ActiveQuery;
use yii\web\UploadedFile;

/**
 * This is the model class for table "agency".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $phone1
 * @property string $phone2
 * @property string $phone3
 * @property string $phone4
 * @property string $email2
 * @property string $email3
 * @property string $email4
 * @property int $manufacturer_id
 * @property array $bid_attributes
 * @property array $bid_attributes_section1
 * @property array $bid_attributes_section2
 * @property array $bid_attributes_section3
 * @property array $bid_attributes_section4
 * @property array $bid_attributes_section5
 * @property bool $is_independent
 *
 * @property Manufacturer $manufacturer
 * @property Manager[] $managers
 */
class Agency extends \yii\db\ActiveRecord
{
    use BidAttributesTrait;

    const IS_DISABLED_AGENCIES = 'is_disabled_agencies';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'agency';
    }

    protected function getModel() {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'manufacturer_id'], 'required'],
            [['bid_attributes'], 'safe'],
            [['description'], 'string'],
            [['is_independent'], 'boolean'],
            [['phone1', 'phone2', 'phone3', 'phone4'], 'string'],
            [['email2', 'email3', 'email4'], 'string'],
            [['email2', 'email3', 'email4'], 'email'],
            [['manufacturer_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['manufacturer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Manufacturer::className(), 'targetAttribute' => ['manufacturer_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название представительства',
            'description' => 'Описание',
            'manufacturer_id' => 'Производитель',
            'phone1' => 'Телефон 1',
            'phone2' => 'Телефон 2',
            'phone3' => 'Телефон 3',
            'phone4' => 'Телефон 4',
            'email2' => 'Email 2',
            'email3' => 'Email 3',
            'email4' => 'Email 4',
            'is_independent' => 'Работать автономно'
        ];
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
    public function getManagers()
    {
        return $this->hasMany(Manager::className(), ['agency_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorkshops()
    {
        return $this->hasMany(Workshop::class, ['id' => 'workshop_id'])
            ->viaTable('agency_workshop', ['agency_id' => 'id'], function (ActiveQuery $query) {
                $query->where(['active' => true]);
            });
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAllWorkshops()
    {
        return $this->hasMany(Workshop::class, ['id' => 'workshop_id'])
            ->viaTable('agency_workshop', ['agency_id' => 'id']);
    }

    public function getWorkshop($workshopId)
    {
        return array_filter($this->workshops, function ($item) use ($workshopId) {
            return $item->id == $workshopId;
        });
    }

    public function getCommonHiddenAttributeName()
    {
        return self::IS_DISABLED_AGENCIES;
    }

}
