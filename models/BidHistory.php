<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "bid_history".
 *
 * @property int $id
 * @property int $bid_id
 * @property int $user_id
 * @property string $action
 * @property string $created_at
 * @property string $updated_at
 * @property array $updated_attributes
 * @property string $model_class
 *
 * @property Bid $bid
 * @property User $user
 */
class BidHistory extends \yii\db\ActiveRecord
{
    const BID_SENT_WORKSHOP = 'Отправка заявки мастерской';
    const BID_SENT_AGENCY = 'Отправка заявки представительством';
    const BID_VIEWED = 'Заявка просмотрена';
    const BID_STATUS_DONE = 'Заявка выполнена';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bid_history';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'value' => function () {
                    return date('Y-m-d H:i:s');
                }
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bid_id'], 'required'],
            [['bid_id', 'user_id'], 'integer'],
            [['action', 'model_class'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['bid_id'], 'exist', 'skipOnError' => true, 'targetClass' => Bid::className(), 'targetAttribute' => ['bid_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['updated_attributes'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bid_id' => 'Bid ID',
            'user_id' => 'Создатель',
            'action' => 'Действие',
            'created_at' => 'Дата создания',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBid()
    {
        return $this->hasOne(Bid::className(), ['id' => 'bid_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public static function createUpdated($bidId,
                                         TranslatableInterface $model,
                                         $userId,
                                         $actionText = 'Изменена на портале',
                                         $updated = true
                            )
    {
        /* @var $model ActiveRecord */
        try {
            $bidHistory = new self([
                'bid_id' => $bidId,
                'user_id' => $userId,
                'action' => $actionText,
                'model_class' => get_class($model)
            ]);

            $changedAttributes = [];
            if ($updated) {
                foreach ($model->getDirtyAttributes() as $name => $value) {
                    $oldValue = $model->getOldAttribute($name);
                    if ($value != $oldValue) {
                        $changedAttributes[] = [
                            'name' => $name,
                            'value' => $value,
                            'old_value' => $oldValue
                        ];
                    }
                }
            } else {
                foreach ($model->attributes as $name => $value) {
                    $changedAttributes[] = [
                        'name' => $name,
                        'value' => '',
                        'old_value' => $value
                    ];
                }
            }

            if (!empty($changedAttributes)) {
                $bidHistory->updated_attributes = $changedAttributes;

                if (!$bidHistory->save()) {
                    \Yii::error($bidHistory->getErrors());
                }
            }
        } catch (\Exception $e) {
            \Yii::error($e->getMessage());
        }
    }

    public static function createRecord($attributes)
    {
        $model = new self($attributes);
        if (!$model->save()) {
            \Yii::error($attributes);
        }
    }

    public static function removeRecords($attributes) {
        try {
            self::deleteAll($attributes);
        } catch (\Exception $e) {
            \Yii::error($e->getMessage());
        }
    }

    public static function sendBid($bidId, $userId)
    {
        $attributes = ['bid_id' => $bidId, 'user_id' => $userId];
        $master = Master::findByUserId($userId);
        if ($master) {
            $attributes['action'] = self::BID_SENT_WORKSHOP;
        } else {
            /* @var $manager Manager */
            $manager = Manager::findByUserId($userId);
            if ($manager) {
                $attributes['action'] = self::BID_SENT_AGENCY;
            } else {
                return;
            }
        }

        self::createRecord($attributes);
    }


    private static function sentBid($bidId)
    {
        return self::find()
            ->where(['bid_id' => $bidId])
            ->andWhere(['or', ['action' => BidHistory::BID_SENT_WORKSHOP], ['action' => BidHistory::BID_SENT_AGENCY]])
            ->orderBy('created_at DESC')
            ->one();
    }


    public static function sentBidStatus($bidId)
    {
        /* @var $bidHistory BidHistory */
        $bidHistory = self::sentBid($bidId);

        return $bidHistory ? $bidHistory->action : null;
    }

    /**
     * @param $bidId int
     * @param $user User
     */
    public static function setViewStatus($bidId, $user)
    {
        $attributes = ['bid_id' => $bidId, 'user_id' => $user->id, 'action' => self::BID_VIEWED];

        switch ($user->role) {
            case 'manager':
                if (self::sentBidStatus($bidId) == self::BID_SENT_WORKSHOP) {
                    self::createRecord($attributes);
                }
                break;
            case 'master':
                if (self::sentBidStatus($bidId) == self::BID_SENT_AGENCY) {
                    self::createRecord($attributes);
                }
                break;
            default:
        }
    }

    public static function isBidViewed($bidId, $userRole)
    {
        if (!in_array($userRole, ['manager', 'master'])) {
            return null;
        }

        /* @var $sentBid \app\models\BidHistory */
        $sentBid = self::sentBid($bidId);

        if (is_null($sentBid)) {
            return null;
        }

        $isViewed = self::find()
            ->where(['>', 'created_at', $sentBid->created_at])
            ->andWhere(['bid_id' => $bidId, 'action' => self::BID_VIEWED])
            ->exists();

        if ($userRole == 'manager') {
            if ($sentBid->action == self::BID_SENT_WORKSHOP) {
                return $isViewed;
            }
        }

        if ($userRole == 'master') {
            if ($sentBid->action == self::BID_SENT_AGENCY) {
                return $isViewed;
            }
        }

        return null;
    }

    public static function isBidDone($bidId) {

        return self::find()->where(['bid_id' => $bidId, 'action' => self::BID_STATUS_DONE])->exists();

    }

}
