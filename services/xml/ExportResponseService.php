<?php


namespace app\services\xml;

use app\models\Bid;
use app\models\Client;

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

    protected function setClient($clientArray)
    {
        $attributes = $clientArray['@attributes'];
        $id = $this->getCommentAttribute($attributes, 'ПорталID');
        $exportResult = $attributes['Успешно'];

        if ($exportResult === 'Истина') {
            Client::setFlagExport($id, true);
        }

        return [
            'GUID' => $this->getCommentAttribute($attributes, 'GUID'),
            'ПорталID' => $id,
            'Экспортирован' => $exportResult
        ];
    }

}