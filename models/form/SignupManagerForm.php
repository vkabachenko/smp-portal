<?php

namespace app\models\form;

use app\models\LoginForm;
use app\models\Manager;
use app\models\ManagerSignup;
use yii\base\Model;
use app\models\User;

class SignupManagerForm extends Model
{
    /* @var Manager */
    public $manager;

    public $userName;
    public $email;
    public $password;
    public $phone;
    public $verifyCode;


    public function __construct(Manager $manager,$config = [] )
    {
        $this->manager = $manager;
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userName'], 'required'],
            [['phone'], 'string', 'max' => 255],
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            [['userName'], 'string', 'max' => 255],
            ['verifyCode', 'captcha'],
        ];
    }

    public function init()
    {
        $this->email = $this->manager->user->email;
        parent::init();
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'userName' => 'ФИО менеджера',
            'email' => 'E-mail',
            'password' => 'Пароль',
            'phone' => 'Телефон',
            'verifyCode' => 'Проверочный код с картинки'
        ];
    }
    
    public function signup()
    {
        if (!$this->validate()) {
            return false;
        }

        $transaction = \Yii::$app->db->beginTransaction();


        $user = $this->manager->user;
        $user->name = $this->userName;
        $user->status = User::STATUS_ACTIVE;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        if (!$user->save()) {
            \Yii::error($user->getErrors());
            $transaction->rollBack();
            return false;
        }

        $this->manager->phone = $this->phone;
        $this->manager->invite_token = null;
        if (!$this->manager->save()) {
            \Yii::error($this->manager->getErrors());
            $transaction->rollBack();
            return false;
        }

        $transaction->commit();

        \Yii::$app->user->login($user, 0);
        LoginForm::assignRole();
        
        return true;
    }

}