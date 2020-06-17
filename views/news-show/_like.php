<?php

use yii\helpers\Url;

/* @var $article \app\models\News */
/* @var \app\services\news\NewsShowService $newsShowService */
?>

<div class="news-article-wrap">
    <div class="col-xs-3 col-sm-2">
            <span
                    class="news-like news-like-up <?= $newsShowService->isUserUp ? 'news-own-like' : '' ?>"
                    data-url="<?= Url::to(['news-show/add-like', 'id' => $article->id]) ?>"
            >
                <span class="news-like-count"><?= $newsShowService->countUp ?></span>
                &nbsp;
                <i class="glyphicon glyphicon-thumbs-up"></i>
            </span>
    </div>

    <div class="col-xs-3 col-sm-2">
            <span
                class="news-like news-like-down <?= $newsShowService->isUserDown ? 'news-own-like' : '' ?>"
                data-url="<?= Url::to(['news-show/add-like', 'id' => $article->id]) ?>"
            >
                <i class="glyphicon glyphicon-thumbs-down"></i> &nbsp;
                <span class="news-like-count"><?= $newsShowService->countDown ?></span>
            </span>
    </div>
</div>
