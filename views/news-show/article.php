<?php
use app\models\News;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $article \app\models\News */

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
        <?= News::TARGETS[$article->target] . ' ' . $article->updated_at ?>
    </p>
</div>
