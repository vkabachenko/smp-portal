<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\helpers\common\DateHelper;

/* @var $this yii\web\View */
/* @var $report app\models\Report */

$this->title = 'Просмотр отчета';
$this->params['back'] = ['index'];

?>
<div>

    <h2><?= Html::encode($this->title) ?></h2>

    <?= DetailView::widget([
        'model' => $report,
        'attributes' => [
            'report_nom',
            [
                'label' => 'Дата отчета',
                'value' => DateHelper::getReadableDate($report->report_date)
            ],
            [
                'label' => 'Отчет',
                'format' => 'raw',
                'value' => Html::a('Скачать', ['download/default', 'filename' => 'report.xlsx', 'path' => $report->report_filename])
            ],
            [
                'label' => 'Отправлен в представительство',
                'value' => $report->is_transferred ? 'Да' : 'Нет'
            ],
            [
                'label' => 'Заявки, попавшие в отчет',
                'format' => 'raw',
                'value' => Html::a('Просмотреть', ['workshop-report/bids', 'reportId' => $report->id])
            ],
        ],
    ]) ?>

    <div class="form-group">
        <?= Html::a('Отправить в представительство', ['send-report', 'id' => $report->id], ['class' => 'btn btn-primary']) ?>
    </div>

</div>
