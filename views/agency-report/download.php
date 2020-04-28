<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $excelReport \app\templates\excel\report\ExcelReport */

$this->title = 'Скачать отчет по заявкам';
$this->params['back'] = ['bid/index',];

?>

<div>
    <h2><?= Html::encode($this->title) ?></h2>

    <div class="form-group">
        <?php if ($excelReport->isGenerated()): ?>
            <?= Html::a('Скачать отчет', [
                'download/default',
                'filename' => $excelReport->getFilename(),
                'path' => $excelReport->getPath()
            ]) ?>
        <?php else: ?>
            <h4>Нет шаблона отчета</h4>
        <?php endif; ?>
    </div>

</div>
