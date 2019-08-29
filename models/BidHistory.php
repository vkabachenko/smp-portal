<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

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
 *
 * @property Bid $bid
 * @property User $user
 */
class BidHistory extends \yii\db\ActiveRecord
{
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
            [['action'], 'string'],
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

    public static function createUpdated(Bid $bid, $userId, $actionText = 'Изменена на портале')
    {
        try {
            $model = new self([
                'bid_id' => $bid->id,
                'user_id' => $userId,
                'action' => $actionText
            ]);

            $changedAttributes = [];
            foreach ($bid->getDirtyAttributes() as $name => $value) {
                $oldValue = $bid->getOldAttribute($name);
                if ($value != $oldValue) {
                    $changedAttributes[] = [
                        'name' => $name,
                        'value' => $value,
                        'old_value' => $oldValue
                    ];
                }
            }

            if (!empty($changedAttributes)) {
                $model->updated_attributes = $changedAttributes;

                if (!$model->save()) {
                    \Yii::error($model->getErrors());
                }
            }
        } catch (\Throwable $e) {
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
}
