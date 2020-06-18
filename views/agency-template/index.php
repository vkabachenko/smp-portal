<?php

use yii\bootstrap\Html;

/* @var $this \yii\web\View */
/* @var $agency \app\models\Agency */

$this->title = 'Шаблоны Excel';
$this->params['back'] = ['manager/index'];
?>

<div>
    <div class="form-group">
        <h3>Шаблон акта</h3>
        <div class="form-group">
            <?= Html::a('Образец шаблона',
                [
                    'download/default',
                    'filename' => 'template_act_sample.xlsx',
                    'path' => \Yii::getAlias('@app/templates/excel/act/sample.xlsx')
                ]);
            ?>
        </div>
        <?php if ($agency->act_template): ?>
            <div class="form-group">
                <?= Html::a('Загруженный шаблон',
                     ['download/agency-template', 'agencyId' => $agency->id, 'type' => 'act']);
                ?>
            </div>
        <?php endif; ?>
        <div>
           <?= Html::a('Загрузить шаблон акта',
                ['agency-template/upload', 'agencyId' => $agency->id, 'type' => 'act'],
                ['class' => 'btn btn-success'])
           ?>
        </div>
    </div>
    <div class="form-group">
        <h3>Шаблон отчета</h3>
        <div class="form-group">
            <?= Html::a('Образец шаблона',
                [
                    'download/default',
                    'filename' => 'template_report_sample.xlsx',
                    'path' => \Yii::getAlias('@app/templates/excel/report/sample.xlsx')
                ]);
            ?>
        </div>
        <?php if ($agency->report_template): ?>
            <div class="form-group">
                <?= Html::a('Загруженный шаблон',
                    ['download/agency-template', 'agencyId' => $agency->id, 'type' => 'report']);
                ?>
            </div>
        <?php endif; ?>
        <div>
            <?= Html::a('Загрузить шаблон отчета',
                ['agency-template/upload', 'agencyId' => $agency->id, 'type' => 'report'],
                ['class' => 'btn btn-success'])
            ?>
        </div>
    </div>
</div>
