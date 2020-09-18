<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "decision_workshop_status".
 *
 * @property int $id
 * @property string $name
 * @property string $sub_type_act
 * @property string $email_subject
 * @property string $email_body
 * @property string $email_signature
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
            [['name', 'sub_type_act', 'email_subject', 'email_signature'], 'string', 'max' => 255],
            [['email_body'], 'string'],
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
            'sub_type_act' => 'Вид акта',
            'email_subject' => 'Шаблон письма - тема',
            'email_body' => 'Шаблон письма - текст',
            'email_signature' => 'Шаблон письма - подпись',

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
