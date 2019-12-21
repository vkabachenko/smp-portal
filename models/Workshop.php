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
            [['rules'], 'safe'],
            [['name', 'token'], 'string', 'max' => 255],
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
            'description' => 'Описание'
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
}
