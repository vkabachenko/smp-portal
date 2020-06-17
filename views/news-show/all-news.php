<?php

use yii\helpers\Url;
use yii\data\ActiveDataProvider;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'Все новости';
$this->params['back'] = Url::previous('main-page');
?>

<div class="news-wrap">
    <h2>
        <?= $this->title ?>
    </h2>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_item',
    ]); ?>
</div>

