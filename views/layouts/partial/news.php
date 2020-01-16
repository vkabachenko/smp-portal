<?php

use yii\bootstrap\Html;

/* @var $news \app\models\News[] */
/* @var $newsShowServices \app\services\news\NewsShowService[] */

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
            <?= $this->render('//news-show/_like',
                ['article' => $article, 'newsShowService' => $newsShowServices[$article->id]])
            ?>
            <div class="clearfix"></div>
        </div>
    <?php endforeach; ?>
    <p class="news-all">
        <?= Html::a('Все новости', ['news-show/all-news']) ?>
    </p>
</div>



