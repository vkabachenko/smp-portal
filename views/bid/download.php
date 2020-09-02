<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $modelActDiagnostic \app\models\form\SendActForm */
/* @var $modelActWriteoff \app\models\form\SendActForm */
/* @var $modelActNoWarranty \app\models\form\SendActForm */

$this->title = 'Скачать акты и фото';
$this->params['back'] = ['view', 'id' => $modelActDiagnostic->bidId];

?>

<div>
    <h2><?= Html::encode($this->title) ?></h2>

    <div class="form-group">
        <?php if ($modelActDiagnostic->act->isGenerated()): ?>
            <?= Html::a('Акт диагностики', [
                'download/default',
                'filename' => $modelActDiagnostic->act->getFilename(),
                'path' => $modelActDiagnostic->act->getPath()
            ]) ?>
        <?php else: ?>
            <h4>Нет шаблона акта диагностики</h4>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <?php if ($modelActNoWarranty->act->isGenerated()): ?>
            <?= Html::a('Акт не гарантии', [
                'download/default',
                'filename' => $modelActNoWarranty->act->getFilename(),
                'path' => $modelActNoWarranty->act->getPath()
            ]) ?>
        <?php else: ?>
            <h4>Нет шаблона акта не гарантии</h4>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <?php if ($modelActWriteoff->act->isGenerated()): ?>
            <?= Html::a('Акт списания', [
                'download/default',
                'filename' => $modelActWriteoff->act->getFilename(),
                'path' => $modelActWriteoff->act->getPath()
            ]) ?>
        <?php else: ?>
            <h4>Нет шаблона акта списания</h4>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <?php if (!empty($model->images)): ?>
            <h4>Фотографии</h4>
            <?php foreach ($model->images as $id => $preview): ?>
                <?= Html::a($preview, ['download/bid-image', 'id' => $id], ['style' => 'margin-right: 10px;']) ?>
            <?php endforeach; ?>
        <?php else: ?>
            <h4>Нет фотографий</h4>
        <?php endif; ?>
    </div>

</div>
