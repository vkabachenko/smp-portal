<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "image".
 *
 * @property int $id
 * @property int $bid_id
 * @property int $bid_history_id
 * @property string $file_name
 * @property string $src_name
 *
 * @property BidHistory $bidHistory
 * @property Bid $bid
 */
class Image extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'image';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bid_id', 'bid_history_id', 'file_name', 'src_name'], 'required'],
            [['bid_id', 'bid_history_id'], 'integer'],
            [['file_name', 'src_name'], 'string', 'max' => 255],
            [['bid_history_id'], 'exist', 'skipOnError' => true, 'targetClass' => BidHistory::className(), 'targetAttribute' => ['bid_history_id' => 'id']],
            [['bid_id'], 'exist', 'skipOnError' => true, 'targetClass' => Bid::className(), 'targetAttribute' => ['bid_id' => 'id']],
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
            'bid_history_id' => 'Bid History ID',
            'file_name' => 'File Name',
            'src_name' => 'Src Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBidHistory()
    {
        return $this->hasOne(BidHistory::className(), ['id' => 'bid_history_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBid()
    {
        return $this->hasOne(Bid::className(), ['id' => 'bid_id']);
    }

    public function getPath()
    {
        return \Yii::getAlias('@webroot/uploads/') . $this->src_name;
    }
}
