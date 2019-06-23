<?php

namespace app\controllers;

use app\helpers\bid\CompositionHelper;
use app\models\Brand;
use app\models\BrandModel;
use yii\web\Controller;
use yii\web\Response;

class BidController extends Controller
{
    public function actionBrands()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $manufacturerId = intval(\Yii::$app->request->post('manufacturerId'));

        $brands = Brand::brandsManufacturer($manufacturerId);
        return $brands;
    }

    public function actionBrandModels()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $brandId = intval(\Yii::$app->request->post('brandId'));

        $brandModels = BrandModel::brandModels($brandId);
        return $brandModels;
    }

    public function actionCompositions()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $brandId = intval(\Yii::$app->request->post('brandId'));

        $compositions = CompositionHelper::unionCompositions($brandId);

        return $compositions;
    }

}
