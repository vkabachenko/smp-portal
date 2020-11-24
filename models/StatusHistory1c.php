<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "status_history_1c".
 *
 * @property int $id
 * @property int $bid_id
 * @property string $status
 * @property string $date
 * @property double $sum_doc
 * @property string $author
 *
 * @property Bid $bid
 */
class StatusHistory1c extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'status_history_1c';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bid_id', 'author'], 'required'],
            [['bid_id'], 'integer'],
            [['date'], 'safe'],
            [['sum_doc'], 'number'],
            [['status', 'author'], 'string', 'max' => 255],
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
            'status' => 'Status',
            'date' => 'Date',
            'sum_doc' => 'Sum Doc',
            'author' => 'Author',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBid()
    {
        return $this->hasOne(Bid::className(), ['id' => 'bid_id']);
    }
}
