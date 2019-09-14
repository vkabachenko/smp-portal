<?php


namespace app\services\xml;

use app\models\Bid;

class ExportResponseService extends ReadService
{
    protected function setBid($bidArray)
    {
        $attributes = $bidArray['@attributes'];
        $id = $attributes['ПорталID'];
        $exportResult = $attributes['Успешно'];

        if ($exportResult === 'Истина') {
            Bid::setFlagExport($id, true);
        }

        return [
            'GUID' => $attributes['GUID'],
            'ПорталID' => $id,
            'Экспортирован' => $exportResult
        ];
    }

}