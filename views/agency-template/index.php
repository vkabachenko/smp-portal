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
        <?php if ($agency->act_template): ?>
            <div class="form-group">
                <?= Html::a($agency->act_template,
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
        <?php if ($agency->report_template): ?>
            <div class="form-group">
                <?= Html::a($agency->report_template,
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
