<?php
/* @var $this yii\web\View */
/* @var $model \app\models\BidHistory */

$this->title = 'Этап заявки';
$this->params['back'] = ['bid-history/index', 'bidId' => $model->bid_id];

?>
<h3><?= $this->title ?></h3>

<?= \yii\widgets\DetailView::widget([
    'model' => $model,
    'attributes' => [
        'created_at',
        [
            'label' => 'Создатель',
            'value' => $model->user_id ? $model->user->username : null,
        ],
        'action'
    ]
]);
?>

<?php if (!empty($model->updated_attributes)): ?>
    <?= $this->render('_updated', ['model' => $model]) ?>
<?php endif; ?>



