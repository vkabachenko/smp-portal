<?php

/* @var $model \app\models\ReplacementPart */
/* @var $returnUrl string */

$this->title = 'Артикулы для сервиса ';
$this->params['back'] = $returnUrl;

?>

<div>
    <?= \yii\widgets\DetailView::widget([
        'model' => $model,
        'attributes' => [
            'vendor_code',
            'vendor_code_replacement',
            [
                'label' => 'Согласовано',
                'value' => $model->is_agree ? 'Да' : 'Нет',
            ],
            'name',
            'price',
            'quantity',
            'total_price',
            'manufacturer',
            'link1C',
            'comment:ntext',
            'status',
            [
                'label' => 'Надо покупать',
                'value' => $model->is_to_buy ? 'Да' : 'Нет',
            ],
        ]

    ]); ?>

</div>
