<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "client".
 *
 * @property int $id
 * @property string $guid
 * @property string $name
 * @property string $full_name
 * @property string $client_type
 * @property string $date_register
 * @property string $comment
 * @property string $description
 * @property string $manager
 * @property string $inn
 * @property string $kpp
 * @property string $email
 * @property string $address_actual
 * @property string $address_legal
 *
 * @property Bid[] $bids
 * @property ClientPhone[] $clientPhones
 */
class Client extends \yii\db\ActiveRecord
{
    const CLIENT_TYPE_PERSON = 'person';
    const CLIENT_TYPE_LEGAL_ENTITY = 'legal_entity';

    const CLIENT_TYPES = [
        self::CLIENT_TYPE_PERSON => 'Физическое лицо',
        self::CLIENT_TYPE_LEGAL_ENTITY => 'Юридическое лицо'
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'client';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['client_type'], 'required'],
            [['date_register'], 'safe'],
            [['comment', 'description'], 'string'],
            [['guid', 'name', 'full_name', 'client_type', 'manager', 'inn', 'kpp', 'email', 'address_actual', 'address_legal'], 'string', 'max' => 255],
            ['client_type', 'in', 'range' => array_keys(self::CLIENT_TYPES)],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'guid' => 'GUID',
            'name' => 'Наименование',
            'full_name' => 'Полное наименование',
            'client_type' => 'Тип',
            'date_register' => 'Дата регистрации',
            'comment' => 'Комментарий',
            'description' => 'Дополнительная информация',
            'manager' => 'Менеджер',
            'inn' => 'ИНН',
            'kpp' => 'КПП',
            'email' => 'Email',
            'address_actual' => 'Фактический адрес',
            'address_legal' => 'Юридический адрес',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBids()
    {
        return $this->hasMany(Bid::className(), ['client_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClientPhones()
    {
        return $this->hasMany(ClientPhone::className(), ['client_id' => 'id']);
    }

    public function getClientPhone()
    {
        $phones = $this->clientPhones;

        return empty($phones) ? '' : reset($phones);
    }
}
