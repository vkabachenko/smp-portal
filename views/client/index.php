<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Client;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Клиенты';
$this->params['back'] = ['admin/index'];
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Новый клиент', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'name',
            [
                'attribute' => 'client_type',
                'value' => function(Client $client) {
                    return Client::CLIENT_TYPES[$client->client_type];
                }
            ],
           [
                'header' => 'Телефон',
                'content' => function(Client $client) {
                    return $client->clientPhone;
                }
            ],
            [
                'format' => 'raw',
                'header' => 'Заявки',
                'content' => function(Client $client) {
                    return Html::a('Перейти', ['bid/index','BidSearch[client_id]' => $client->id]);
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{delete}',
            ],
        ],
    ]); ?>


</div>
