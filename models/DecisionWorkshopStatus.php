<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "decision_workshop_status".
 *
 * @property int $id
 * @property string $name
 */
class DecisionWorkshopStatus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'decision_workshop_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Наименование',
        ];
    }

    /**
     * return array
     */
    public static function decisionWorkshopStatusAsMap()
    {
        $models = self::find()->orderBy('name')->all();
        $list = ArrayHelper::map($models, 'id', 'name');

        return $list;
    }

    public static function findByName($name)
    {
        return static::findOne(['name' => $name]);
    }

    public static function findIdByName($name)
    {
        $model = static::findByName($name);
        return $model ? $model->id : null;
    }
}
