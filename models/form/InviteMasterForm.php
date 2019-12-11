<?php

namespace app\models\form;

use app\models\Master;
use app\models\Workshop;
use yii\base\Model;
use app\models\User;

class InviteMasterForm extends Model
{
    public $email;

    /* @var Master */
    public $master;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email'], 'trim'],
            [['email'], 'required'],
            ['email', 'email'],
            [['email'], 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => User::class, 'message' => 'This email address has already been taken.'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'email' => 'E-mail',
        ];
    }
    
    public function signup(Workshop $workshop)
    {
        if (!$this->validate()) {
            return false;
        }

        $transaction = \Yii::$app->db->beginTransaction();

        $user = new User();
        $user->username = $this->email;
        $user->email = $this->email;
        $user->name = $this->email;
        $user->role = 'master';
        $user->status = User::STATUS_INACTIVE;
        $user->setPassword(\Yii::$app->security->generateRandomString(6));
        $user->generateAuthKey();
        if (!$user->save()) {
            \Yii::error($user->getErrors());
            $transaction->rollBack();
            return false;
        }
        
        $this->master = new Master([
            'user_id' => $user->id,
            'workshop_id' => $workshop->id,
            'main' => false,
            'invite_token' => \Yii::$app->security->generateRandomString(16)
        ]);
        if (!$this->master->save()) {
            \Yii::error($this->master->getErrors());
            $transaction->rollBack();
            return false;
        }

        $transaction->commit();
        
        return true;
    }

}