<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bid_update_history".
 *
 * @property int $id
 * @property int $bid_history_id
 * @property array $updated_attributes
 *
 * @property BidHistory $bidHistory
 */
class BidUpdateHistory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bid_update_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bid_history_id'], 'required'],
            [['bid_history_id'], 'integer'],
            [['updated_attributes'], 'safe'],
            [['bid_history_id'], 'exist', 'skipOnError' => true, 'targetClass' => BidHistory::className(), 'targetAttribute' => ['bid_history_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bid_history_id' => 'Bid History ID',
            'updated_attributes' => 'Updated Attributes',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBidHistory()
    {
        return $this->hasOne(BidHistory::className(), ['id' => 'bid_history_id']);
    }

    public static function createUpdated(Bid $bid, BidHistory $bidHistory)
    {
        $changedAttributes = [];

        try {
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
            $model = new self([
                'bid_history_id' => $bidHistory->id,
                'updated_attributes' => $changedAttributes
            ]);
            if (!$model->save()) {
                \Yii::error($model->getErrors());
            }
        } catch (\Throwable $e) {
            \Yii::error($e->getMessage());
        }
    }
}
