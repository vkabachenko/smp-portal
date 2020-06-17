<?php
namespace app\services\mail;

use app\models\Master;

class InviteMaster implements SendMail
{
    /**
     * @var \app\models\Master
     */
    private $master;

    public function __construct(Master $master)
    {
        $this->master = $master;
    }

    public function send()
    {
        try {
            $result = \Yii::$app->mailer
                ->compose(
                    [
                        'html' => 'invite-master/html'
                    ],
                    [
                        'master' => $this->master,
                    ]
                )
                ->setFrom(\Yii::$app->params['adminEmail'])
                ->setTo(
                    $this->master->user->email
                )
                ->setSubject('Приглашение в мастерскую ' . $this->master->workshop->name)
                ->send();
        } catch (\Exception $exc) {
            $result = false;
            \Yii::error($exc->getMessage());
        }
        return $result;
    }
}