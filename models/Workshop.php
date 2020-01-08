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
 * @property string $email1
 * @property string $email2
 * @property string $email3
 * @property string $email4
 *
 * @property Master[] $masters
 */
class Workshop extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'workshop';
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
            [['email1', 'email2', 'email3', 'email4'], 'string'],
            [['email1', 'email2', 'email3', 'email4'], 'email'],
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
            'email1' => 'Email 1',
            'email2' => 'Email 2',
            'email3' => 'Email 3',
            'email4' => 'Email 4',
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

    public function getBidAttributes()
    {
        return is_null($this->bid_attributes) ? [] : $this->bid_attributes;
    }
}
