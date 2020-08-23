<?php

namespace app\models;

use app\helpers\common\DateHelper;
use Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

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
 * @property boolean $flag_export
 * @property int $workshop_id
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
            [['flag_export'], 'boolean'],
            [['workshop_id'], 'integer'],
            [['date_register'], 'safe'],
            [['email'], 'email'],
            [['comment', 'description'], 'string'],
            [['guid', 'name', 'full_name', 'client_type', 'manager', 'inn', 'kpp', 'email', 'address_actual', 'address_legal'], 'string', 'max' => 255],
            ['client_type', 'in', 'range' => array_keys(self::CLIENT_TYPES)],
            [['workshop_id'], 'exist', 'skipOnError' => true, 'targetClass' => Workshop::className(), 'targetAttribute' => ['workshop_id' => 'id']],
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
            'workshop_id' => 'Мастерская',
        ];
    }

    public function fields()
    {
        return [
            'id',
            'name',
            'full_name',
            'client_type' => function(Client $client) {
                return self::CLIENT_TYPES[$client->client_type];
            },
            'date_register' => function(Client $client) {
                return DateHelper::getReadableDate($client->date_register);
            },
            'comment',
            'description',
            'manager',
            'inn',
            'kpp',
            'email',
            'address_actual',
            'address_legal',
            'bids',
            'clientPhones'
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

        return empty($phones) ? '' : reset($phones)->phone;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorkshop()
    {
        return $this->hasOne(Workshop::className(), ['id' => 'workshop_id']);
    }

    public function beforeValidate()
    {
        $this->date_register = DateHelper::convert($this->date_register);
        return parent::beforeValidate();
    }

    public static function setFlagExport($id, $flagValue)
    {
        $model = self::findOne($id);
        if ($model) {
            $model->flag_export = $flagValue;
            if (!$model->save(false)) {
                \Yii::error($model->getErrors());
            }
        }
    }

    /**
     * return array
     */
    public static function clientsAsMap($workshopId)
    {
        $query = self::find()->orderBy('name');
        if ($workshopId) {
            $query->where(['workshop_id' => $workshopId]);
        }
        $models = $query->all();
        $list = ArrayHelper::map($models, 'id', 'name');

        return $list;
    }

    public static function getClientsByTerm($workshopId, $term)
    {
        $query = self::find()
            ->joinWith('clientPhones', false)
            ->select(['name', 'client.id'])
            ->orderBy('name');

        if ($workshopId) {
            $query->where(['workshop_id' => $workshopId]);
        }

        if (preg_match("/^\d+$/", $term)) {
            $query->where(['like', 'client_phone.phone', $term]);
        } else {
            $query->where(['like', 'name', $term]);
        }

        return $query->asArray()->all();
    }

    public static function getClientByPhone($phone, Workshop $workshop)
    {
        $model = self::find()
            ->select([
                'client.*',
                'normalized_phone' => new Expression('substr(regex_replace("[^0-9]", "", client_phone.phone), -10)')
            ])
            ->joinWith('clientPhones', false)
            ->where(['client.workshop_id' => $workshop->id])
            ->having(['normalized_phone' => $phone])
            ->one();

        return $model;
    }
}
