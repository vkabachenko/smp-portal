<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $modelAct \app\models\form\SendActForm */


$this->title = 'Скачать акты и фото';
$this->params['back'] = ['view', 'id' => $modelAct->bidId];

?>

<div>
    <h2><?= Html::encode($this->title) ?></h2>

    <div class="form-group">
        <?php if ($modelAct->act->isGenerated()): ?>
            <?= Html::a('Акт', [
                'download/default',
                'filename' => $modelAct->act->getFilename(),
                'path' => $modelAct->act->getPath()
            ]) ?>
        <?php else: ?>
            <h4>Нет шаблона акта</h4>
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
