<?php


namespace app\helpers\bid;


class HintHelper
{
    public static function getLabelOptions($attribute, $hints)
    {
        if (!isset($hints[$attribute])) {
            return [];
        }

        $description = $hints[$attribute]['description'];

        $options = [];

        if (!empty($description)) {
            $options['class'] = 'column-hint';
            $options['data-title'] = $description;
            $options['data-html'] = $hints[$attribute]['is_html_description'] ? 'true' : 'false';
        }

        return $options;
    }

}