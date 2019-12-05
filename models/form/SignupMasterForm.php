<?php

namespace app\models\form;

use app\models\LoginForm;
use app\models\Master;
use app\models\MasterSignup;
use yii\base\Model;
use app\models\User;

class SignupMasterForm extends Model
{
    public $userName;
    public $email;
    public $password;
    public $phone;
    public $verifyCode;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email'], 'trim'],
            [['email', 'userName'], 'required'],
            ['email', 'email'],
            [['email', 'phone'], 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => User::class, 'message' => 'This email address has already been taken.'],
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            [['userName'], 'string', 'max' => 255],
            ['verifyCode', 'captcha'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'userName' => 'ФИО мастера',
            'email' => 'E-mail',
            'password' => 'Пароль',
            'phone' => 'Телефон',
            'verifyCode' => 'Проверочный код с картинки'
        ];
    }
    
    public function signup(MasterSignup $masterSignup)
    {
        if (!$this->validate()) {
            return false;
        }

        $transaction = \Yii::$app->db->beginTransaction();


        $user = new User();
        $user->username = $this->email;
        $user->email = $this->email;
        $user->name = $this->userName;
        $user->role = 'master';
        $user->status = User::STATUS_ACTIVE;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        if (!$user->save()) {
            \Yii::error($user->getErrors());
            $transaction->rollBack();
            return false;
        }
        
        $master = new Master([
            'user_id' => $user->id,
            'workshop_id' => $masterSignup->workshop_id,
            'phone' => $this->phone,
            'main' => false
        ]);
        if (!$master->save()) {
            \Yii::error($master->getErrors());
            $transaction->rollBack();
            return false;
        }

        $masterSignup->active = false;
        if (!$masterSignup->save()) {
            \Yii::error($masterSignup->getErrors());
            $transaction->rollBack();
            return false;
        }

        $transaction->commit();

        \Yii::$app->user->login($user, 0);
        LoginForm::assignRole();
        
        return true;
    }

}