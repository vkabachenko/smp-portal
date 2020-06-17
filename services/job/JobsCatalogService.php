<?php

namespace app\services\job;

use yii\db\Query;
use yii\helpers\ArrayHelper;

class JobsCatalogService
{
    private $agencyId;
    private $dateActual;

    public function __construct($agencyId, $dateActual)
    {
        $this->agencyId = $agencyId;
        $this->dateActual = $dateActual;
    }

    /**
     * return array
     */
    public function jobsCatalogAsMap()
    {
        $models = $this->dateActualQuery()->all();
        $list = ArrayHelper::map($models, 'id', 'name');

        return $list;
    }

    /**
     *
     * @return Query
     */
    public function dateActualQuery()
    {
        $subQuery = new Query();
        $subQuery
            ->select(['date_max' => 'MAX(date_actual)', 'uuid'])
            ->from('jobs_catalog')
            ->where(['agency_id' => $this->agencyId])
            ->andWhere(['<=', 'date_actual', $this->dateActual])
            ->groupBy('uuid');

        $query = new Query();
        $query
            ->select(['jobs_catalog.*', 'jobs_section_name' => 'jobs_section.name'])
            ->from('jobs_catalog')
            ->where(['jobs_catalog.agency_id' => $this->agencyId])
            ->leftJoin('jobs_section', 'jobs_catalog.jobs_section_id = jobs_section.id')
            ->innerJoin(['u' => $subQuery], 'u.uuid = jobs_catalog.uuid AND u.date_max = jobs_catalog.date_actual');

        return $query;
    }

}