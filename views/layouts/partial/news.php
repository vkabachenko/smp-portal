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
                <?= News::TARGETS[$article->target] . ' ' . $article->updated_at ?>
            </p>
        </div>
    <?php endforeach; ?>
    <p class="news-all">
        <?= Html::a('Все новости', ['news-show/all-news']) ?>
    </p>
</div>

<div>
    <?= Html::a('Написать разработчику', '#', ['class' => 'btn btn-danger btn-feedback']) ?>
</div>

<?php
    $script = <<<JS
    $('.btn-feedback').click(function() {
        $('[data-target="#feedback-modal"]').trigger('click');  
    });
JS;
    $this->registerJs($script);

