<?php
/* @var $agencies array */
/* @var $this yii\web\View */

use yii\bootstrap\Html;


$this->title = 'Выбор представительства';
$this->params['back'] = ['index'];
?>

<h2><?= Html::encode($this->title) ?></h2>

<?= Html::beginForm() ?>

<div class="form-group">
    <label class="control-label" for="agency-select-id">Представительство</label>
    <?= Html::dropDownList(
        'agencyId',
        array_key_first($agencies),
        $agencies,
        ['id' => 'agency-select-id', 'class' => 'form-control'])
    ?>
</div>

<div class="form-group">
    <?= Html::submitButton('Отправить', ['class' => 'btn btn-success']) ?>
</div>


<?= Html::endForm() ?>

