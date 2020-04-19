<?php


namespace app\services\xml;

use app\models\Bid;

class ExportResponseService extends ReadService
{
    protected function setBid($bidArray)
    {
        $attributes = $bidArray['@attributes'];
        $id = $this->get1Cattribute($attributes, 'id');
        $exportResult = $attributes['Успешно'];

        if ($exportResult === 'Истина') {
            Bid::setFlagExport($id, true);
        }

        return [
            'GUID' => $this->get1Cattribute($attributes, 'guid'),
            'ПорталID' => $id,
            'Экспортирован' => $exportResult
        ];
    }

}