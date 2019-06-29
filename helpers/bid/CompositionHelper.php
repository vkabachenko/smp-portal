<?php

namespace  app\helpers\bid;

use app\models\Composition;
use app\models\BrandComposition;
use yii\helpers\ArrayHelper;

class CompositionHelper
{
    public static function unionCompositions($brandId, $term = null) {

        $commonCompositions = Composition::compositions($term);
        $brandCompositions = BrandComposition::brandCompositions($brandId, $term);

        $union = [];
        foreach ($brandCompositions as $el) {
            $el['id'] = BrandComposition::tableName() . '-' . $el['id'];
            $union[] = $el;
        }
        foreach ($commonCompositions as $el) {
            $el['id'] = Composition::tableName() . '-' . $el['id'];
            $union[] = $el;
        }
        ArrayHelper::multisort($union, 'name');

        return $union;
    }

}