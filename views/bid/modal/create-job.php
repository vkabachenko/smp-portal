<?php
/* @var $this \yii\web\View */
/* @var $bid \app\models\Bid */
/* @var $model \app\models\BidJob */
/* @var $jobsCatalog \app\models\JobsCatalog */
/* @var $jobsCatalogService \app\services\job\JobsCatalogService */

use yii\bootstrap\Modal;
?>

<?php Modal::begin([
    'toggleButton' => [
        'label' => 'Новая работа',
        'class' => 'btn btn-success'
    ]
]) ?>

<?= $this->render('//bid-job/_form', [
    'model' => $model,
    'bid' => $bid,
    'jobsCatalog' => $jobsCatalog,
    'jobsCatalogService' => $jobsCatalogService,
    'action' => ['bid-job/create-modal', 'bidId' => $bid->id]
]); ?>


<?php Modal::end(); ?>
