<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "client_phone".
 *
 * @property int $id
 * @property int $client_id
 * @property string $phone
 *
 * @property Client $client
 */
class ClientPhone extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'client_phone';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['client_id', 'phone'], 'required'],
            [['client_id'], 'integer'],
            [['phone'], 'string', 'max' => 255],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => Client::className(), 'targetAttribute' => ['client_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'client_id' => 'Client ID',
            'phone' => 'Телефон',
        ];
    }

    public function fields()
    {
        return [
            'phone'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::className(), ['id' => 'client_id']);
    }
}
