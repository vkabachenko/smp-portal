<?php

namespace app\models;

use app\helpers\constants\Constants;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "master".
 *
 * @property int $id
 * @property int $user_id
 * @property int $workshop_id
 * @property bool $main
 * @property string $phone
 * @property string $invite_token
 * @property string $uuid
 *
 * @property Bid[] $bids
 * @property User $user
 * @property Workshop $workshop
 * @property string $grid_attributes_warranty
 */
class Master extends \yii\db\ActiveRecord
{
    public $name;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'workshop_id'], 'required'],
            [['user_id', 'workshop_id'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['workshop_id'], 'exist', 'skipOnError' => true, 'targetClass' => Workshop::className(), 'targetAttribute' => ['workshop_id' => 'id']],
            ['main', 'boolean'],
            [['phone', 'invite_token', 'uuid'], 'string'],
            [['uuid'], 'unique']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Мастер',
            'workshop_id' => 'Мастерская',
            'main' => 'Основной',
            'phone' => 'Телефон',
            'invite_token' > 'Invite_token',
            'uuid' => 'Uuid'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBids()
    {
        return $this->hasMany(Bid::className(), ['master_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorkshop()
    {
        return $this->hasOne(Workshop::className(), ['id' => 'workshop_id']);
    }

    /**
     * return array
     */
    public static function mastersAsMap(User $user, $withEmpty = false)
    {
        $query = self::find()
            ->select(['master.id', 'user.name'])
            ->joinWith('user', false)
            ->orderBy('user.name');

        $master = $user->master;
        if ($master) {
            $query->where(['master.workshop_id' => $master->workshop_id]);
        } else {
            $manager = $user->manager;
            if ($manager) {
                $workshops = array_map(function(Workshop $workshop) { return $workshop->id; }, $manager->agency->workshops);
                $query->where(['master.workshop_id' => $workshops]);
            }
        }

        $models = $query->all();

        $list = ArrayHelper::map($models, 'id', 'name');

        return $withEmpty ? Constants::EMPTY_ELEMENT + $list : $list;
    }

    public static function findByUserId($userId)
    {
        return static::find()->where(['user_id' => $userId])->one();
    }

    public static function findByName($name)
    {
        return static::find()->joinWith('user', false)->where(['user.name' => $name])->one();
    }

    public static function findIdByName($name)
    {
        $model = static::findByName($name);
        return $model ? $model->id : null;
    }

    public static function findIdByUuid($uuid)
    {
        $model = self::find()->where(['uuid' => $uuid])->one();
        return $model ? $model->id : null;
    }


    public function saveMasterUser(User $user)
    {
        $transaction = \Yii::$app->db->beginTransaction();

        if (!$user->save()) {
            \Yii::error($user->getErrors());
            $transaction->rollBack();
            return false;
        }

        if (!$this->save()) {
            \Yii::error($this->getErrors());
            $transaction->rollBack();
            return false;
        }
        $transaction->commit();
        return true;
    }

    public function isActive()
    {
        if ($this->user->status == User::STATUS_ACTIVE) {
            return true;
        } else {
            return false;
        }
    }

    public function getBidRole()
    {
        $workshop = $this->workshop;
        if (!$workshop->canManagePaidBid()) {
            return Bid::TREATMENT_TYPE_WARRANTY;
        }
        return isset($_COOKIE['master_role']) ? $_COOKIE['master_role'] : Bid::TREATMENT_TYPE_PRESALE;
    }
}
