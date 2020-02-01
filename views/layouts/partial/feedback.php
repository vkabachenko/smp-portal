<?php
    use yii\helpers\Html;
    use vkabachenko\filepond\widget\FilepondWidget;
?>

<?= Html::beginForm(['feedback/send'],
    'post',
    [
        'id' => 'feedback-form',
    ]) ?>
    <div class="form-group">
        <?= Html::label('Тема', 'feedback-subject', ['class' => 'control-label']) ?>
        <?= Html::textInput('subject', null, [
            'id' => 'feedback-subject',
            'class' => 'form-control'
        ]) ?>
    </div>

    <div class="form-group">
        <?= Html::label('Сообщение', 'feedback-message', ['class' => 'control-label']) ?>
        <?= Html::textarea('message', null, [
            'id' => 'feedback-message',
            'class' => 'form-control',
            'rows' => 5
        ]) ?>
    </div>

    <div class="form-group">
        <?= Html::label('Загрузить файлы', null, ['class' => 'control-label']) ?>
        <?= FilepondWidget::widget([
            'name' => 'files[]',
            'multiple' => true
        ]);
        ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-success']) ?>
    </div>

<?= Html::endForm() ?>
