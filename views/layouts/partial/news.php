<?php

use yii\bootstrap\Html;
use app\models\News;

/* @var $news \app\models\News[] */

?>

<div class="news">

    <?php foreach ($news as $article): ?>
        <div class="news-article">
            <p class="news-title">
                <?= Html::a($article->title, ['news-show/article', 'id' => $article->id]) ?>
            </p>
            <p class="news-date">
                <?= $article->getNewsInfo() ?>
            </p>
        </div>
    <?php endforeach; ?>
    <p class="news-all">
        <?= Html::a('Все новости', ['news-show/all-news']) ?>
    </p>
</div>



