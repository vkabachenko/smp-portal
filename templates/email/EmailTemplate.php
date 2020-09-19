<?php


namespace app\templates\email;


use app\models\Bid;
use app\models\DecisionStatusInterface;
use app\models\TemplateModel;

class EmailTemplate
{
    /**
     * @var TemplateModel
     */
    private $template;
    /**
     * @var DecisionStatusInterface
     */
    private $decision;

    public function __construct($template, DecisionStatusInterface $decision)
    {
        $this->template = $template;
        $this->decision = $decision;
    }

    public function getSubject()
    {
        if ($this->decision->email_subject) {
            return $this->getText(strval($this->decision->email_subject));
        } else {
            return  $this->template ? $this->getText(strval($this->template->email_subject)) : '';
        }
    }

    public function getMailContent()
    {
        if ($this->decision->email_body) {
            $body = $this->getText(strval($this->decision->email_body));
            $signature = $this->getText(strval($this->decision->email_signature));
        } else {
            $body = $this->getText(strval($this->template->email_body));
            $signature = $this->getText(strval($this->template->email_signature));
        }
        return sprintf("%s\n\n-------\n%s", $body, $signature);
    }


    protected function getText($text)
    {
        return str_replace(array_keys($this->getParams()), array_values($this->getParams()), $text);
    }

    protected function getParams()
    {
        return [];
    }

}