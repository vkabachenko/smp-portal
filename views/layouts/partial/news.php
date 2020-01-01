<?php

/* @var $news \app\models\News[] */

?>

<div class="news">

    <?php foreach ($news as $article): ?>

        <h3 class="news-title">
            <?= $article->title ?>
        </h3>
        <p class="news-date">
            Изменено <?= $article->updated_at ?>
        </p>
        <div class="news-content">
            <?= $article->content ?>
        </div>

    <?php endforeach; ?>

</div>
