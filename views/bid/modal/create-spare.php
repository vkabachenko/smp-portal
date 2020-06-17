<?php
/* @var $this \yii\web\View */
/* @var $model \app\models\Spare */

use yii\bootstrap\Modal;
?>

<?php Modal::begin([
    'toggleButton' => [
        'label' => 'Новая запчасть',
        'class' => 'btn btn-success'
    ]
]) ?>

<?= $this->render('//spare/_form', [
    'model' => $model,
    'action' => ['spare/create-modal', 'bidId' => $model->bid_id]
]); ?>


<?php Modal::end(); ?>
