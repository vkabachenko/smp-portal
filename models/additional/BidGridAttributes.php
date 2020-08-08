<?php


namespace app\models\additional;


use app\models\Bid;
use yii\bootstrap\Html;

class BidGridAttributes
{
    public static function convert($gridAttributes) {
        $result = [];
        foreach ($gridAttributes as $attribute => $rowValues) {
            $result[] = ['content' => self::getRow($attribute, $rowValues)];
        }

        return $result;
    }

    private static function getRow($attribute, $rowValues) {
        return sprintf('
            <div class="row">
                <div class="col-xs-6">
                    %s
                </div>
                <div class="col-xs-2">
                    %s
                </div>
                <div class="col-xs-2">
                    %s
                </div>
                <div class="col-xs-2">
                    %s
                </div>                            
            </div>',
            self::getName($attribute),
            self::getCheckbox($attribute,'desktop', $rowValues),
            self::getCheckbox($attribute,'tablet', $rowValues),
            self::getCheckbox($attribute,'phone', $rowValues)
        );
    }

    private static function getName($attribute)
    {
        return Bid::getAllAttributes()[$attribute];
    }

    private static function getCheckbox($attribute,$device, $rowValues)
    {
        $name = sprintf('grid_attributes[%s][%s]', $attribute, $device);
        return Html::hiddenInput($name, '0') . Html::checkbox($name, $rowValues[$device], ['value' => '1']);
    }

}