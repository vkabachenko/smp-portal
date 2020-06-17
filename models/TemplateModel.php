<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "template".
 *
 * @property int $id
 * @property string $name
 * @property array $fields
 */
class TemplateModel extends \yii\db\ActiveRecord
{
    const EMAIL_ACT = 'email_act';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'template';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'fields'], 'required'],
            [['fields'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'fields' => 'Fields',
        ];
    }

    public static function findByName($name)
    {
        return self::find()->where(['name' => $name])->one();
    }
}
