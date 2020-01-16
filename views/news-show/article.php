<?php

use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $article \app\models\News */
/* @var \app\services\news\NewsShowService $newsShowService */


$this->title = $article->title;
$this->params['back'] = Url::previous();

?>

<div>
    <h2>
        <?= $article->title ?>
    </h2>
    <div>
        <?= $article->content ?>
    </div>

    <p class="news-date">
        <?= $article->getNewsInfo() ?>
    </p>

    <?= $this->render('_like', compact('article', 'newsShowService')) ?>
</div>


