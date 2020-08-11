<?php
namespace app\services\mail;

use app\models\Master;

class InviteWorkshop implements SendMail
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
                        'html' => 'invite-workshop/html'
                    ],
                    [
                        'master' => $this->master,
                    ]
                )
                ->setFrom(\Yii::$app->params['adminEmail'])
                ->setTo(
                    \Yii::$app->params['adminEmail']
                )
                ->setSubject('Подтвердите регистрацию мастерской ' . $this->master->workshop->name)
                ->send();
        } catch (\Exception $exc) {
            $result = false;
            \Yii::error($exc->getMessage());
        }
        return $result;
    }
}