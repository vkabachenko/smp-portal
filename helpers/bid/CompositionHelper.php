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
            $el['source'] = 'brand';
            $union[] = $el;
        }
        foreach ($commonCompositions as $el) {
            $el['source'] = 'common';
            $union[] = $el;
        }
        ArrayHelper::multisort($union, 'name');

        return $union;
    }

}