<?php


namespace app\templates\excel\act;


class ExcelActs
{
    public static function findFilesByBidId($bidId)
    {
        $allFiles = scandir(\Yii::getAlias(ExcelAct::ACT_PATH));


        $foundFiles = array_filter($allFiles, function($filename) use ($bidId) {

           $s = strpos('_(' . $bidId . ')', $filename);

           return strpos($filename, '_(' . $bidId . ')') !== false;
        });

        return $foundFiles;
    }

}