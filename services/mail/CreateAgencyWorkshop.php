<?php
namespace app\services\mail;

use app\models\Agency;
use app\models\Workshop;

class CreateAgencyWorkshop implements SendMail
{


    /**
     * @var Agency
     */
    private $agency;
    /**
     * @var Workshop
     */
    private $workshop;

    public function __construct(Agency $agency, Workshop $workshop)
    {

        $this->agency = $agency;
        $this->workshop = $workshop;
    }

    public function send()
    {
        try {
            $result = \Yii::$app->mailer
                ->compose(
                    [
                        'html' => 'create-agency-workshop/html'
                    ],
                    [
                        'agency' => $this->agency,
                        'workshop' => $this->workshop
                    ]
                )
                ->setFrom(\Yii::$app->params['adminEmail'])
                ->setTo(
                    $this->workshop->mainMaster->user->email
                )
                ->setSubject('Запрос на подключение мастерской ' . $this->workshop->name . ' к представительству ' . $this->agency->name)
                ->send();
        } catch (\Exception $exc) {
            $result = false;
            \Yii::error($exc->getMessage());
        }
        return $result;
    }
}