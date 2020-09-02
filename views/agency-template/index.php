<?php

use yii\bootstrap\Html;
use app\models\TemplateModel;

/* @var $this \yii\web\View */
/* @var $agency \app\models\Agency */

$this->title = 'Шаблоны актов и отчетов';
$this->params['back'] = ['manager/index'];
?>

<h2><?= $this->title ?></h2>

<div class="center-menu-container">

    <div class="list-group center-menu">
        <?= Html::a('Акт диагностики', [
                'agency-template/manage',
                'agencyId' => $agency->id, 'type' => TemplateModel::TYPE_ACT, 'sub_type' => TemplateModel::SUB_TYPE_ACT_DIAGNOSTIC
        ],
                ['class' => 'list-group-item center-menu-item'])
        ?>
        <?= Html::a('Акт списания', [
            'agency-template/manage',
            'agencyId' => $agency->id, 'type' => TemplateModel::TYPE_ACT, 'sub_type' => TemplateModel::SUB_TYPE_ACT_WRITE_OFF
        ],
            ['class' => 'list-group-item center-menu-item'])
        ?>
        <?= Html::a('Акт не гарантии', [
            'agency-template/manage',
            'agencyId' => $agency->id, 'type' => TemplateModel::TYPE_ACT, 'sub_type' => TemplateModel::SUB_TYPE_ACT_NO_WARRANTY
        ],
            ['class' => 'list-group-item center-menu-item'])
        ?>
        <?= Html::a('Отчет', [
            'agency-template/manage',
            'agencyId' => $agency->id, 'type' => TemplateModel::TYPE_REPORT
        ],
            ['class' => 'list-group-item center-menu-item'])
        ?>
    </div>
</div>




