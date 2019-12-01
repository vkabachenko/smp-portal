<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "agency".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $manufacturer_id
 *
 * @property Manufacturer $manufacturer
 * @property Manager[] $managers
 */
class Agency extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'agency';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'manufacturer_id'], 'required'],
            [['description'], 'string'],
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
            'name' => 'Название представителя',
            'description' => 'Описание',
            'manufacturer_id' => 'Производитель',
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
}
