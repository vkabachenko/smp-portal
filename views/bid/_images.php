<?php

use yii\bootstrap\Modal;
use yii\bootstrap\Html;
use app\models\BidImage;

/* @var $model \app\models\Bid */


$items = array_map(function(BidImage  $image) {
    return Html::img($image->getAbsoluteUrl(), ['style' => 'width: 100%;']);
}, $model->bidImages);

?>

    <div>
        <?php foreach ($model->bidImages as $image): ?>

            <?php if(\Yii::$app->user->can('viewImage', ['imageId' => $image->id])): ?>
                <div
                    class= "view-img-wrap"
                    data-url="<?= \Yii::getAlias('@web/uploads/') . $image->src_name ?>"
                >
                    <?= \himiklab\thumbnail\EasyThumbnailImage::thumbnailImg($image->getPath(), 50, 50) ?>
                </div>
            <?php endif; ?>

        <?php endforeach; ?>
    </div>

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

    <?= \yii\bootstrap\Carousel::widget([
            'items' => $items,
            'clientOptions' => [
                'interval' => false
            ]
    ]); ?>


<?php Modal::end(); ?>

<?php

$script = <<<JS
    $(function() {
        $('.view-img-wrap').click(function(evt) {
            evt.preventDefault();
            
            var number = $('.view-img-wrap').index(this);
            
            var modal = $('#img-expand');
            $('.carousel').carousel(number);
            modal.modal('show');
        });
    });
JS;

$this->registerJs($script);