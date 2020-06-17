<?php

namespace app\helpers\common;

class XmlHelper
{
    public static function getXmlFromFile($filename)
    {
        return simplexml_load_file($filename);
    }

    public static function getArrayFromXml($xml)
    {
        $json = json_encode($xml);

        return json_decode($json, true);
    }

}