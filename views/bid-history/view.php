<?php
/* @var $this yii\web\View */
/* @var $model \app\models\BidHistory */


$this->title = 'Этап заявки';
$this->params['breadcrumbs'][] = ['label' => 'Личный кабинет мастера', 'url' => ['master/index']];
$this->params['breadcrumbs'][] = ['label' => 'История заявки', 'url' => ['bid-history/index', 'bidId' => $model->bid_id]];
$this->params['breadcrumbs'][] = $this->title;

use yii\bootstrap\Html;
use yii\bootstrap\Modal; ?>
<h3><?= $this->title ?></h3>

<?= \yii\widgets\DetailView::widget([
    'model' => $model,
    'attributes' => [
        'created_at',
        [
            'label' => 'Создатель',
            'value' => $model->user->username,
        ],
        'statusName',
        'comment'
    ]
]);
?>

<?php if (!empty($model->images)): ?>
<div>
    <?php foreach ($model->images as $image): ?>

    <div
         class= "view-img-wrap"
         data-url="<?= \Yii::getAlias('@web/uploads/') . $image->src_name ?>"
    >
        <?= \himiklab\thumbnail\EasyThumbnailImage::thumbnailImg($image->getPath(), 50, 50) ?>
    </div>

    <?php endforeach; ?>

</div>

<?php endif; ?>

    <!-- Modal -->
<?php
Modal::begin([
    'id' => 'img-expand',
    'closeButton' => [
        'id'=>'close-button',
        'class'=>'close',
        'data-dismiss' =>'modal',
    ],
    'clientOptions' => [
        'backdrop' => false, 'keyboard' => true
    ],
    'size' => Modal::SIZE_LARGE
]);
?>

    <img src="" alt="" style="width: 100%;"/>


<?php Modal::end(); ?>

<?php

$script = <<<JS
    $(function() {
        $('.view-img-wrap').click(function(evt) {
            evt.preventDefault();
            
            var modal = $('#img-expand');
            var url = $(this).data('url');
            
            modal.find('img').attr('src', url);           
            modal.modal('show');
        });
    });
JS;

$this->registerJs($script);
