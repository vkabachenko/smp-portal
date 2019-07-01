<?php
/* @var $this yii\web\View */
/* @var $model \app\models\BidHistory */

use yii\bootstrap\Modal;

$this->title = 'Этап заявки';
$this->params['breadcrumbs'][] = ['label' => 'Личный кабинет мастера', 'url' => ['master/index']];
$this->params['breadcrumbs'][] = ['label' => 'История заявки', 'url' => ['bid-history/index', 'bidId' => $model->bid_id]];
$this->params['breadcrumbs'][] = $this->title;

?>
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

<?php if (!empty($model->bidUpdateHistory)): ?>
    <?= $this->render('_updated', ['model' => $model]) ?>
<?php endif; ?>

<?php if (!empty($model->images)): ?>
    <?= $this->render('_images', ['model' => $model]) ?>
<?php endif; ?>


