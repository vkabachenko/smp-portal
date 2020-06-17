<?php
namespace app\services\mail;

use app\models\User;

class ResetPassword implements SendMail
{
    /**
     * @var \app\models\User
     */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function send()
    {
        try {
            $result = \Yii::$app->mailer
                ->compose(
                    [
                        'html' => 'reset-password/html'
                    ],
                    [
                        'user' => $this->user,
                    ]
                )
                ->setFrom(\Yii::$app->params['adminEmail'])
                ->setTo(
                    $this->user->email
                )
                ->setSubject('Восстановление пароля на сайте garantportal.ru')
                ->send();
        } catch (\Exception $exc) {
            $result = false;
            \Yii::error($exc->getMessage());
        }
        return $result;
    }
}