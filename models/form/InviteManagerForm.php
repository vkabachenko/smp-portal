<?php

namespace app\models\form;

use app\models\LoginForm;
use app\models\Manager;
use app\models\ManagerSignup;
use yii\base\Model;
use app\models\User;

class InviteManagerForm extends Model
{
    public $email;

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
        
        $manager = new Manager([
            'user_id' => $user->id,
            'agency_id' => $agency->id,
            'main' => false,
            'invite_token' => \Yii::$app->security->generateRandomString(16)
        ]);
        if (!$manager->save()) {
            \Yii::error($manager->getErrors());
            $transaction->rollBack();
            return false;
        }

        $transaction->commit();
        
        return true;
    }

}