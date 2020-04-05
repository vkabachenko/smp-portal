<?php


namespace app\helpers\bid;


use app\models\Bid;
use yii\web\User;

class ViewHelper
{
    public static function getAttributesView(Bid $bid, User $user)
    {
        $attributes = [];

        $attributes['created_at'] = \Yii::$app->formatter->asDate($bid->created_at);
        $attributes['updated_at'] = \Yii::$app->formatter->asDate($bid->updated_at);
        $attributes['brand_name'] = $bid->brand_name;
        $attributes['manufacturer_id'] = $bid->manufacturer_id ? $bid->manufacturer->name : '';
        $attributes['equipment'] = $bid->equipment;
        $attributes['brand_model_name'] = $bid->brand_model_name;
        $attributes['serial_number'] = $bid->serial_number;
        $attributes['vendor_code'] = $bid->vendor_code;
        $attributes['composition_name'] = $bid->composition_name;
        $attributes['condition_id'] = $bid->condition_id ? $bid->condition->name : null;
        $attributes['defect'] = $bid->defect;
        $attributes['defect_manufacturer'] = $bid->defect_manufacturer;
        $attributes['diagnostic'] = $bid->diagnostic;
        $attributes['diagnostic_manufacturer'] = $bid->diagnostic_manufacturer;
        $attributes['repair_recommendations'] = $bid->repair_recommendations;
        $attributes['client_type'] = $bid->client_type ? Bid::CLIENT_TYPES[$bid->client_type] : '';
        $attributes['client_name'] = $bid->client_name;
        $attributes['client_phone'] = $bid->client_phone;
        $attributes['client_address'] = $bid->client_address;
        $attributes['is_warranty_defect'] = $bid->is_warranty_defect ? 'Истина' : 'Ложь';
        $attributes['is_repair_possible'] = $bid->is_repair_possible ? 'Истина' : 'Ложь';
        $attributes['is_for_warranty'] = $bid->is_for_warranty ? 'Истина' : 'Ложь';
        $attributes['treatment_type'] = $bid->treatmentTypeName;
        $attributes['saler_name'] = $bid->saler_name;
        $attributes['purchase_date'] = \Yii::$app->formatter->asDate($bid->purchase_date);
        $attributes['application_date'] = \Yii::$app->formatter->asDate($bid->application_date);
        $attributes['date_manufacturer'] = \Yii::$app->formatter->asDate($bid->date_manufacturer);
        $attributes['date_completion'] = \Yii::$app->formatter->asDate($bid->date_completion);
        $attributes['bid_number'] = $bid->bid_number;
        $attributes['warranty_number'] = $bid->warranty_number;
        $attributes['bid_manufacturer_number'] = $bid->bid_manufacturer_number;
        $attributes['bid_1C_number'] = $bid->bid_1C_number;
        $attributes['repair_status_id'] = $bid->repair_status_id ? $bid->repairStatus->name : null;
        $attributes['warranty_status_id'] = $bid->warranty_status_id ? $bid->warrantyStatus->name : null;
        $attributes['status_id'] = $bid->status_id ? $bid->status->name : null;

        if ($user->can('updateDecisionMaster', ['bidId' => $bid->id])) {
            $decisionMaster = \Yii::$app->view->render('//bid/modal/decision-master', ['model' => $bid]);
        } else {
            $decisionMaster = '';
        }

        $attributes['decision_workshop_status_id'] = '<span>'
            . ($bid->decision_workshop_status_id ? $bid->decisionWorkshopStatus->name : '')
            . '</span>'
            . '  '
            . $decisionMaster;

        if ($user->can('updateDecisionManager', ['bidId' => $bid->id])) {
            $decisionManager = \Yii::$app->view->render('//bid/modal/decision-manager', ['model' => $bid]);
        } else {
            $decisionManager = '';
        }

        $attributes['decision_agency_status_id'] = '<span>'
            . ($bid->decision_agency_status_id ? $bid->decisionAgencyStatus->name : '')
            . '</span>'
            . '  '
            . $decisionManager;

        $attributes['user_id'] = $bid->user_id ? $bid->user->name : '';
        $attributes['master_id'] = $bid->master_id ? $bid->master->user->name : '';
        $attributes['comment'] = $bid->comment;

        return $attributes;
    }

    public static function getViewSection1(Bid $bid, User $user)
    {
        if ($user->can('manager')) {
            $agency = $bid->getAgency();
            return $agency ? $agency->getSectionsAttributes()->section1 : [];
        } elseif ($user->can('master')) {
            return $bid->workshop->getSectionsAttributes()->section1;
        } else {
            return array_keys($bid->attributeLabels());
        }
    }

    public static function getViewSection2(Bid $bid, User $user)
    {
        if ($user->can('manager')) {
            $agency = $bid->getAgency();
            return $agency ? $agency->getSectionsAttributes()->section2 : [];
        } elseif ($user->can('master')) {
            return $bid->workshop->getSectionsAttributes()->section2;
        } else {
            return [];
        }
    }

    public static function getViewSection3(Bid $bid, User $user)
    {
        if ($user->can('manager')) {
            $agency = $bid->getAgency();
            return $agency ? $agency->getSectionsAttributes()->section3 : [];
        } elseif ($user->can('master')) {
            return $bid->workshop->getSectionsAttributes()->section3;
        } else {
            return [];
        }
    }

}