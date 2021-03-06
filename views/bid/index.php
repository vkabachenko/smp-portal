<?php


use app\models\Bid;
use yii\grid\GridView;
use yii\bootstrap\Html;
use app\helpers\bid\RowOptionsHelper;
use app\helpers\bid\GridHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\BidSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $gridHelper GridHelper */

$this->title = 'Заявки';
$this->params['back'] = \yii\helpers\Url::previous();

$this->registerCss($gridHelper->getCss());

$columns = $gridHelper->getColumns();

?>

<div>
    <h2> <?= $this->title ?> <?= Html::a('Сброс', ['bid/index'], ['class' => 'btn btn-default']) ?></h2>

    <div class="bids-grid">
        <div class="row">
            <?php if (\Yii::$app->user->can('createBid')): ?>
                <div class="col-xs-4 col-sm-3">
                    <?= Html::a('Новая заявка', ['create'], ['class' => 'btn btn-success btn-one-line']) ?>
                </div>
            <?php endif; ?>

            <div class="bid-search-btn-wrap col-xs-4 col-sm-3">
                <?= Html::button('Расширенный поиск', [
                    'class' => 'btn btn-primary btn-one-line',
                    'onclick' => '$(".bid-search").show();$(".bid-search-text").hide();'
                ])
                ?>
                <?= $this->render('_search-text', ['model' => $searchModel]); ?>
            </div>

            <?php if (\Yii::$app->user->can('manager')): ?>
                <div class="col-xs-4 col-sm-3">
                    <?= Html::a('Отчет',
                        ['agency-report/index', 'agencyId' => \Yii::$app->user->identity->manager->agency_id],
                        ['class' => 'btn btn-success btn-one-line'])
                    ?>
                </div>
            <?php endif; ?>

            <div class="col-xs-4 col-sm-3">
                <?= Html::a('Настроить поля',
                    ['bid/set-grid-attributes'],
                    ['class' => 'btn btn-primary btn-one-line',]
                )
                ?>
            </div>
        </div>

        <?= $this->render('_search', ['model' => $searchModel]); ?>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => $columns,
            'rowOptions' => function(Bid $bid) {
                return ['class' => RowOptionsHelper::getClass($bid, \Yii::$app->user->identity)];
            }
        ]); ?>
    </div>
</div>


