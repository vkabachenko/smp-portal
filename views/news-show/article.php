<?php
use app\models\News;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $article \app\models\News */
/* @var \app\services\news\NewsShowService $newsShowService */


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
        <?= $article->getNewsInfo() ?>
    </p>

    <div>
        <div class="col-xs-3 col-sm-2">
            <span class="news-like news-like-up <?= $newsShowService->isUserUp ? 'news-own-like' : '' ?>">
                <span class="news-like-count"><?= $newsShowService->countUp ?></span>
                &nbsp;
                <i class="glyphicon glyphicon-thumbs-up"></i>
            </span>
        </div>

        <div class="col-xs-3 col-sm-2">
            <span class="news-like news-like-down">
                <i class="glyphicon glyphicon-thumbs-down <?= $newsShowService->isUserDown ? 'news-own-like' : '' ?>"></i>
                &nbsp;
                <span class="news-like-count"><?= $newsShowService->countDown ?></span>
            </span>
        </div>
    </div>
</div>

<?php
$script = <<<JS
$('.news-like').click(function() {
   var self = $(this);
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
       
       if (result.isUserUp) {
           $('.news-like-up').addClass('news-own-like');
       } else {
           $('.news-like-up').removeClass('news-own-like');
       }
       
       if (result.isUserDown) {
           $('.news-like-down').addClass('news-own-like');
       } else {
           $('.news-like-down').removeClass('news-own-like');
       }
   })
   .catch(function(error) {
       swal('Ошибка', error.message, 'error');  
   });
   
});

JS;

$this->registerJs($script);

