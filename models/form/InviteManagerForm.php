<?php

namespace app\models\form;

use app\models\Manager;
use yii\base\Model;
use app\models\User;

class InviteManagerForm extends Model
{
    public $email;

    /**
     * @var \app\models\Manager
     */
    public $manager;

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
    
    public function signup($agency)
    {
        if (!$this->validate()) {
            return false;
        }

        $transaction = \Yii::$app->db->beginTransaction();


        $user = new User();
        $user->username = $this->email;
        $user->email = $this->email;
        $user->name = $this->email;
        $user->role = 'manager';
        $user->status = User::STATUS_INACTIVE;
        $user->setPassword(\Yii::$app->security->generateRandomString(6));
        $user->generateAuthKey();
        if (!$user->save()) {
            \Yii::error($user->getErrors());
            $transaction->rollBack();
            return false;
        }
        
        $this->manager = new Manager([
            'user_id' => $user->id,
            'agency_id' => $agency->id,
            'main' => false,
            'invite_token' => \Yii::$app->security->generateRandomString(16)
        ]);
        if (!$this->manager->save()) {
            \Yii::error($this->manager->getErrors());
            $transaction->rollBack();
            return false;
        }

        $transaction->commit();
        
        return true;
    }

}