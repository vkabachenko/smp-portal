<?php


namespace app\helpers\bid;


use app\models\Bid;
use yii\web\User;
use app\models\User as UserModel;

class ViewHelper
{
    public static function getAttributesView(Bid $bid, User $user)
    {
        $attributes = [];

        $attributes['brand_id'] = false;
        $attributes['brand_model_id'] = false;
        $attributes['compositionCombined'] = false;
        $attributes['workshop_id'] = false;
        $attributes['created_at'] = \Yii::$app->formatter->asDate($bid->created_at);
        $attributes['updated_at'] = \Yii::$app->formatter->asDate($bid->updated_at);
        $attributes['brand_name'] = $bid->brand_name;
        $attributes['manufacturer_id'] = $bid->manufacturer_id ? $bid->manufacturer->name : '';
        $attributes['equipment'] = $bid->equipment;
        $attributes['equipment_manufacturer'] = $bid->equipment_manufacturer;
        $attributes['brand_model_name'] = $bid->brand_model_name;
        $attributes['serial_number'] = $bid->serial_number;
        $attributes['vendor_code'] = $bid->vendor_code;
        $attributes['composition_name'] = $bid->composition_name;
        $attributes['composition_name_manufacturer'] = $bid->composition_name_manufacturer;
        $attributes['condition_name'] = $bid->condition_name;
        $attributes['condition_manufacturer_name'] = $bid->condition_manufacturer_name;
        $attributes['defect'] = $bid->defect;
        $attributes['defect_manufacturer'] = $bid->defect_manufacturer;
        $attributes['diagnostic'] = $bid->diagnostic;
        $attributes['diagnostic_manufacturer'] = $bid->diagnostic_manufacturer;
        $attributes['repair_recommendations'] = $bid->repair_recommendations;
        $attributes['client_id'] = $bid->client_id
            ? \Yii::$app->view->render('//client/view', ['model' => $bid->client])
            : '';
        $attributes['client_manufacturer_id'] = $bid->client_manufacturer_id
            ? \Yii::$app->view->render('//client/view', ['model' => $bid->clientManufacturer])
            : '';
        $attributes['is_warranty_defect'] = $bid->is_warranty_defect ? 'Истина' : 'Ложь';
        $attributes['is_repair_possible'] = $bid->is_repair_possible ? 'Истина' : 'Ложь';
        $attributes['is_for_warranty'] = $bid->is_for_warranty ? 'Истина' : 'Ложь';
        $attributes['treatment_type'] = $bid->treatmentTypeName;
        $attributes['saler_name'] = $bid->saler_name;
        $attributes['purchase_date'] = \Yii::$app->formatter->asDate($bid->purchase_date);
        $attributes['application_date'] = \Yii::$app->formatter->asDate($bid->application_date);
        $attributes['date_manufacturer'] = \Yii::$app->formatter->asDate($bid->date_manufacturer);
        $attributes['date_completion'] = \Yii::$app->formatter->asDate($bid->date_completion);
        $attributes['date_completion_manufacturer'] = \Yii::$app->formatter->asDate($bid->date_completion_manufacturer);
        $attributes['bid_number'] = $bid->bid_number;
        $attributes['warranty_number'] = $bid->warranty_number;
        $attributes['bid_manufacturer_number'] = $bid->bid_manufacturer_number;
        $attributes['bid_1C_number'] = $bid->bid_1C_number;
        $attributes['repair_status_id'] = $bid->repair_status_id ? $bid->repairStatus->name : null;
        $attributes['warranty_status_id'] = $bid->warranty_status_id ? $bid->warrantyStatus->name : null;
        $attributes['status_id'] = $bid->status_id ? $bid->status->name : null;
        $attributes['comment_1'] = $bid->comment_1;
        $attributes['comment_2'] = $bid->comment_2;
        $attributes['manager'] = $bid->manager;
        $attributes['manager_contact'] = $bid->manager_contact;
        $attributes['manager_presale'] = $bid->manager_presale;
        $attributes['is_reappeal'] = $bid->is_reappeal ? 'Истина' : 'Ложь';
        $attributes['document_reappeal'] = $bid->document_reappeal;
        $attributes['subdivision'] = $bid->subdivision;
        $attributes['repair_status_date'] = \Yii::$app->formatter->asDate($bid->repair_status_date);
        $attributes['repair_status_author_id'] = $bid->repair_status_author_id ? UserModel::findOne($bid->repair_status_author_id)->name : null;
        $attributes['author'] = $bid->author;
        $attributes['sum_manufacturer'] = $bid->sum_manufacturer;
        $attributes['is_control'] = $bid->is_control ? 'Истина' : 'Ложь';;
        $attributes['is_report'] = $bid->is_report ? 'Истина' : 'Ложь';;
        $attributes['is_warranty'] = $bid->is_warranty ? 'Истина' : 'Ложь';;
        $attributes['warranty_comment'] = $bid->warranty_comment;

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

    public static function getViewSection(Bid $bid, User $user, $sectionName, $isFilledByDefault = true)
    {
        $agency = $bid->getAgency();
        if ($user->can('manager')) {
            return $agency ? $agency->getSectionsAttributes()->$sectionName : [];
        } elseif ($user->can('master')) {
            if ($user->identity->master->getBidRole() === Bid::TREATMENT_TYPE_PRESALE) {
                return $bid->workshop->getSectionsAttributes()->$sectionName;
            } else {
                return $agency
                    ? $agency->getSectionsAttributes()->$sectionName
                    : $bid->workshop->getSectionsAttributes()->$sectionName;
            }
        } else {
            return $isFilledByDefault ? array_keys($bid->attributeLabels()) : [];
        }
    }

}