<?php
namespace app\services\mail;

use app\models\Manager;

class InviteAgency
{
    /**
     * @var \app\models\Manager
     */
    private $manager;

    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
    }

    public function send()
    {
        try {
            $result = \Yii::$app->mailer
                ->compose(
                    [
                        'html' => 'invite-agency/html'
                    ],
                    [
                        'manager' => $this->manager,
                    ]
                )
                ->setFrom(\Yii::$app->params['adminEmail'])
                ->setTo(
                    $this->manager->user->email
                )
                ->setSubject('Подтвердите регистрацию представительства ' . $this->manager->agency->name)
                ->send();
        } catch (\Exception $exc) {
            $result = false;
            \Yii::error($exc->getMessage());
        }
    }
}