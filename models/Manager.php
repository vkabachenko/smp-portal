<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "manager".
 *
 * @property int $id
 * @property int $user_id
 * @property int $agency_id
 * @property bool $main
 * @property string $phone
 *
 * @property Agency $agency
 * @property User $user
 */
class Manager extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'manager';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'agency_id'], 'required'],
            [['user_id', 'agency_id'], 'integer'],
            [['agency_id'], 'exist', 'skipOnError' => true, 'targetClass' => Agency::className(), 'targetAttribute' => ['agency_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            ['main', 'boolean'],
            ['phone', 'string']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Менеджер',
            'main' => 'Основной',
            'phone' => 'Телефон'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgency()
    {
        return $this->hasOne(Agency::className(), ['id' => 'agency_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
