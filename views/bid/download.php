<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \app\models\form\SendActForm */

$this->title = 'Скачать акт и фото';
$this->params['back'] = ['view', 'id' => $model->bidId];

?>

<div>
    <h2><?= Html::encode($this->title) ?></h2>

    <div class="form-group">
        <?php if ($model->act->isGenerated()): ?>
            <?= Html::a('Акт технического состояния', [
                'download/act-excel',
                'filename' => $model->act->getFilename(),
                'directory' => $model->act->getDirectory()
            ]) ?>
        <?php else: ?>
            <h4>Нет шаблона акта технического состояния</h4>
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
