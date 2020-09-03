<?php


namespace app\templates\email;


use app\models\Bid;
use app\models\TemplateModel;

class EmailTemplate
{
    /**
     * @var TemplateModel
     */
    private $template;

    public function __construct(TemplateModel $template)
    {
        $this->template = $template;
    }

    public function getSubject()
    {
        return  $this->template ? $this->getText(strval($this->template->email_subject)) : '';
    }

    public function getMailContent()
    {
        if (!$this->template) {
            return '';
        }

        $body = $this->getText(strval($this->template->email_body));
        $signature = $this->getText(strval($this->template->email_signature));

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