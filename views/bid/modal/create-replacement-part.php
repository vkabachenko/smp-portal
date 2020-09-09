<?php
/* @var $this \yii\web\View */
/* @var $model \app\models\ReplacementPart */

use yii\bootstrap\Modal;
?>

<?php Modal::begin([
    'toggleButton' => [
        'label' => 'Новый артикул',
        'class' => 'btn btn-success'
    ]
]) ?>

<?= $this->render('//replacement-part/_form', [
    'model' => $model,
    'action' => ['replacement-part/create-modal', 'bidId' => $model->bid_id]
]); ?>


<?php Modal::end(); ?>
