<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "workshop".
 *
 * @property int $id
 * @property string $name
 * @property string $token
 * @property array $rules
 * @property string $description
 * @property array $bid_attributes
 * @property string $phone1
 * @property string $phone2
 * @property string $phone3
 * @property string $phone4
 * @property string $email2
 * @property string $email3
 * @property array $bid_attributes_section1
 * @property array $bid_attributes_section2
 * @property array $bid_attributes_section3
 * @property array $bid_attributes_section4
 * @property array $bid_attributes_section5
 * @property array $bid_attributes_1c
 *
 * @property Master[] $masters
 */
class Workshop extends \yii\db\ActiveRecord
{
    use BidAttributesTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'workshop';
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
            [['name'], 'required'],
            [['rules', 'bid_attributes'], 'safe'],
            [['name', 'token'], 'string', 'max' => 255],
            [['phone1', 'phone2', 'phone3', 'phone4'], 'string'],
            [['email2', 'email3'], 'string'],
            [['email2', 'email3'], 'email'],
            ['description', 'string']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Мастерская',
            'token' => 'Токен',
            'rules' => 'Правила доступа',
            'description' => 'Описание',
            'phone1' => 'Телефон 1',
            'phone2' => 'Телефон 2',
            'phone3' => 'Телефон 3',
            'phone4' => 'Телефон 4',
            'email2' => 'Email 2',
            'email3' => 'Email 3',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMasters()
    {
        return $this->hasMany(Master::className(), ['workshop_id' => 'id']);
    }

    public function getMainMaster()
    {
        return Master::find()->where(['workshop_id' => $this->id, 'main' => true])->one();
    }

    /**
     * return array
     */
    public static function workshopsAsMap()
    {
        $models = self::find()->orderBy('name')->all();
        $list = ArrayHelper::map($models, 'id', 'name');

        return $list;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgencies()
    {
        return $this->hasMany(Agency::class, ['id' => 'agency_id'])
            ->viaTable('agency_workshop', ['workshop_id' => 'id'], function (ActiveQuery $query) {
                $query->where(['active' => true]);
            });
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAllAgencies()
    {
        return $this->hasMany(Agency::class, ['id' => 'agency_id'])
            ->viaTable('agency_workshop', ['workshop_id' => 'id']);
    }

    public function getAgency($agencyId)
    {
        return array_filter($this->agencies, function ($item) use ($agencyId) {
            return $item->id == $agencyId;
        });
    }

    public function addIndependentAgencies()
    {
        $existingAgencies = ArrayHelper::getColumn($this->allAgencies, 'id');
        $independentAgencies = Agency::find()->where(['is_independent' => true])->all();

        foreach ($independentAgencies as $agency) {
            /* @var $agency Agency */
            if (!in_array($agency->id, $existingAgencies)) {
                $model = new AgencyWorkshop(['workshop_id' => $this->id, 'agency_id' => $agency->id]);
                $model->save();
            }
        }
    }

    public function getCommonHiddenAttributeName()
    {
        return 'is_disabled_workshops';
    }

}
