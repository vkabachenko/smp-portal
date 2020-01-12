<?php
use app\models\News;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $article \app\models\News */
/* @var int $countUp */
/* @var int $countDown */

$this->title = $article->title;
$this->params['back'] = Url::previous();

?>

<div class="news-article-wrap" data-url="<?= Url::to(['news-show/add-like', 'id' => $article->id]) ?>">
    <h2>
        <?= $article->title ?>
    </h2>
    <div>
        <?= $article->content ?>
    </div>

    <p class="news-date">
        <?= News::TARGETS[$article->target] . ' ' . $article->updated_at ?>
    </p>

    <div>
        <div class="col-xs-3 col-sm-2">
            <span class="news-like news-like-up">
                <span class="news-like-count"><?= $countUp ?></span>
                &nbsp;
                <i class="glyphicon glyphicon-thumbs-up"></i>
            </span>
        </div>

        <div class="col-xs-3 col-sm-2">
            <span class="news-like news-like-down">
                <i class="glyphicon glyphicon-thumbs-down"></i>
                &nbsp;
                <span class="news-like-count"><?= $countDown ?></span>
            </span>
        </div>
    </div>
</div>

<?php
$script = <<<JS
$('.news-like').click(function() {
   var self = $(this);
   self.off('click');
   var status = self.hasClass('news-like-up') ? 'like' : 'dislike';
   var wrap = $(".news-article-wrap");
   
   $.ajax({
        url: wrap.data("url"),
        method: "POST",
        data: {
            status: status
        }
   })
   .then(function(result) {
       $('.news-like-up .news-like-count').text(result.countUp);
       $('.news-like-down .news-like-count').text(result.countDown);
   })
   .catch(function(error) {
       swal('Ошибка', error.message, 'error');  
   });
   
});

JS;

$this->registerJs($script);

