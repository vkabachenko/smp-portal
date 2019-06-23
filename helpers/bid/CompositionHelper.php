<?php

namespace  app\helpers\bid;

use app\models\Composition;
use app\models\BrandComposition;
use yii\helpers\ArrayHelper;

class CompositionHelper
{
    public static function unionCompositions($brandId) {
        $commonCompositions = Composition::compositions();
        $brandCompositions = BrandComposition::brandCompositions($brandId);

        $union = [];
        foreach ($brandCompositions as $el) {
            $el['source'] = BrandComposition::tableName();
            $union[] = $el;
        }
        foreach ($commonCompositions as $el) {
            $el['source'] = Composition::tableName();
            $union[] = $el;
        }
        ArrayHelper::multisort($union, 'name');

        return $union;
    }

}