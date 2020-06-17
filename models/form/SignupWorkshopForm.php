<?php

namespace app\models\form;

use app\models\LoginForm;
use app\models\Master;
use app\models\Workshop;
use yii\base\Model;
use app\models\User;

class SignupWorkshopForm extends Model
{
    /* @var Master */
    public $master;

    public $userName;
    public $workshopName;
    public $email;
    public $password;
    public $verifyCode;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'workshopName'], 'trim'],
            [['email', 'workshopName','userName'], 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => User::class, 'message' => 'This email address has already been taken.'],
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            [['workshopName', 'userName'], 'string', 'max' => 255],
            ['verifyCode', 'captcha'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'workshopName' => 'Название мастерской',
            'userName' => 'ФИО начальника мастерской',
            'email' => 'E-mail',
            'password' => 'Пароль',
            'verifyCode' => 'Проверочный код с картинки'
        ];
    }
    
    public function signup()
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
        $user->status = User::STATUS_INACTIVE;
        $user->verification_token = \Yii::$app->security->generateRandomString(16);
        $user->setPassword($this->password);
        $user->generateAuthKey();
        if (!$user->save()) {
            \Yii::error($user->getErrors());
            $transaction->rollBack();
            return false;
        }

        $workshop = new Workshop([
            'name' => $this->workshopName,
        ]);
        if (!$workshop->save()) {
            \Yii::error($workshop->getErrors());
            $transaction->rollBack();
            return false;
        }
        
        $this->master = new Master([
            'user_id' => $user->id,
            'workshop_id' => $workshop->id,
            'main' => true
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