<?php

use yii\bootstrap\Html;
use yii\grid\GridView;
use app\models\BidJob;
use app\services\job\JobsCatalogService;
use app\models\JobsCatalog;
use app\models\Spare;
use app\models\TemplateModel;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Bid */
/* @var $bidJobProvider \yii\data\ActiveDataProvider */
/* @var $bidJob1cProvider \yii\data\ActiveDataProvider */
/* @var $spareProvider \yii\data\ActiveDataProvider */
/* @var $replacementPartProvider \yii\data\ActiveDataProvider */
/* @var $clientPropositionProvider \yii\data\ActiveDataProvider */
/* @var $attributes array */
/* @var $section1 array */
/* @var $section2 array */
/* @var $section3 array */
/* @var $section4 array */
/* @var $section5 array */
/* @var $returnUrl string|null */

$this->title = 'Просмотр заявки';
$this->params['back'] = $returnUrl ?: Url::previous('bid/index');

?>
<div>
    <h3><?= sprintf('%s (%s %s)', Html::encode($this->title), $model->bid_number, $model->bid_1C_number) ?></h3>

    <div class="row" style="margin-bottom: 10px;">
        <div class="col-md-2 col-sm-3 col-xs-6">
            <?= Html::a('История', ['bid-history/index', 'bidId' => $model->id], ['class' => 'btn btn-success']) ?>
        </div>

        <?php if (\Yii::$app->user->can('updateBid', ['bidId' => $model->id])): ?>
            <div class="col-md-2 col-sm-3  col-xs-6">
                <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            </div>
        <?php endif; ?>

        <?php if (\Yii::$app->user->can('viewAct', ['bidId' => $model->id])): ?>
            <div class="col-md-2 col-sm-3  col-xs-6">
                <?= Html::a('Скачать акты и фото', ['download', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            </div>
        <?php endif; ?>

        <?php if (\Yii::$app->user->can('manageJobs', ['bidId' => $model->id])): ?>
            <div class="col-md-2 col-sm-3  col-xs-6">
                <?= Html::a('Работы', ['bid-job/index', 'bidId' => $model->id], ['class' => 'btn btn-primary']) ?>
            </div>
        <?php endif; ?>

        <?php if (\Yii::$app->user->can('viewSpare', ['bidId' => $model->id])): ?>
            <div class="col-md-2 col-sm-3  col-xs-6">
                <?= Html::a('Запчасти', ['spare/index', 'bidId' => $model->id], ['class' => 'btn btn-primary']) ?>
            </div>
        <?php endif; ?>

        <?php if (\Yii::$app->user->can('sendAct', ['bidId' => $model->id])): ?>
            <div class="col-md-2 col-sm-3  col-xs-6 bid-view-send">
                <?= Html::a('Отправить', ['send-act/index', 'bidId' => $model->id], ['class' => 'btn btn-primary']) ?>
            </div>
        <?php endif; ?>

        <?php if (\Yii::$app->user->can('setBidDone', ['bidId' => $model->id])): ?>
            <div class="col-md-2 col-sm-3  col-xs-6 bid-view-send">
                <?= Html::a('Выполнено', ['bid/set-status-done', 'bidId' => $model->id], ['class' => 'btn btn-danger']) ?>
            </div>
        <?php endif; ?>


    </div>

</div>

<div class="form-group">
    <?= $this->render('_view-sections',
        compact('attributes', 'section1', 'section2', 'section3', 'section4', 'section5'));
    ?>
</div>

<div class="form-group">
    <?php if (\Yii::$app->user->can('managePrivateComments', ['bidId' => $model->id])): ?>
        <?= Html::a('Служебные комментарии', ['bid-comment/index', 'bidId' => $model->id, 'private' => true], ['class' => 'btn btn-danger']) ?>
    <?php else: ?>
        <?= Html::a('Комментарии', ['bid-comment/index', 'bidId' => $model->id, 'private' => false], ['class' => 'btn btn-success']) ?>
    <?php endif; ?>
</div>

<?php if (!empty($model->bidImages)): ?>
    <?= $this->render('_images', ['model' => $model]) ?>
<?php endif; ?>

<div class="form-group clearfix"></div>
<div>
    <?= Html::a('Добавить фото', ['bid-image/create', 'bidId' => $model->id], ['class' => 'btn btn-primary']) ?>
</div>

<?php if (\Yii::$app->user->can('viewJobs', ['bidId' => $model->id])): ?>
<div class="form-group clearfix"></div>
<div>
    <div>
        <div class="col-xs-4">
            <?php if (\Yii::$app->user->can('manageJobs', ['bidId' => $model->id])): ?>
                <?= Html::a('<h3>Работы</h3>', ['bid-job/index', 'bidId' => $model->id]) ?>
            <?php else: ?>
                <h3>Работы</h3>
            <?php endif; ?>
        </div>
        <div class="col-xs-8">
            <?php if (\Yii::$app->user->can('manageJobs', ['bidId' => $model->id])): ?>
                <div style="margin: 20px 0 10px 0;">
                    <?php $jobModel = new BidJob(['bid_id' => $model->id]); ?>
                    <?php $agency = $model->getAgency(); ?>
                    <?php $jobsCatalogService = new JobsCatalogService($agency ? $agency->id : null, $model->created_at); ?>
                    <?php $jobsCatalog = JobsCatalog::findOne(array_key_first($jobsCatalogService->jobsCatalogAsMap())); ?>
                    <?= $this->render('modal/create-job', [
                        'model' => $jobModel,
                        'bid' => $model,
                        'jobsCatalog' => $jobsCatalog,
                        'jobsCatalogService' => $jobsCatalogService
                    ]); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $bidJobProvider,
        'summary' => '',
        'columns' => [
            [
                'attribute' => 'jobs_catalog_id',
                'value' => 'jobsCatalog.name',
            ],
            [
                'attribute' => 'price',
                'value' => function(\app\models\BidJob $model) {
                    return $model->price ?: $model->jobsCatalog->price;
                },
            ],
        ],
    ]); ?>
</div>
<?php endif; ?>

<?php if (\Yii::$app->user->can('manageBidJob1c') && $bidJob1cProvider->totalCount): ?>

<div class="form-group clearfix"></div>
<div>
    <h3>Работы (импорт из 1С)</h3>
    <?= GridView::widget([
        'dataProvider' => $bidJob1cProvider,
        'summary' => '',
        'columns' => [
            'name',
            'quantity',
            'price',
            'total_price'
        ],
    ]); ?>
</div>

<?php endif; ?>

<?php if (\Yii::$app->user->can('viewSpareList', ['bidId' => $model->id])): ?>
<div class="form-group clearfix"></div>
<div>
    <div>
        <div class="col-xs-4">
            <?php if (\Yii::$app->user->can('viewSpare', ['bidId' => $model->id])): ?>
                <?= Html::a('<h3>Запчасти</h3>', ['spare/index', 'bidId' => $model->id]) ?>
            <?php else: ?>
                <h3>Запчасти</h3>
            <?php endif; ?>
        </div>
        <div class="col-xs-8">
            <?php if (\Yii::$app->user->can('manageSpare', ['bidId' => $model->id])): ?>
                <div style="margin: 20px 0 10px 0;">
                    <?php $spareModel = new Spare(['bid_id' => $model->id]); ?>
                    <?= $this->render('modal/create-spare', [
                        'model' => $spareModel,
                    ]); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>


    <?= GridView::widget([
        'dataProvider' => $spareProvider,
        'summary' => '',
        'columns' => [
            'name',
            'quantity',
            'price',
            'total_price',
        ],
    ]); ?>
</div>
<?php endif; ?>

<?php if (\Yii::$app->user->can('manageReplacementParts', ['bidId' => $model->id])): ?>

<div class="form-group clearfix"></div>
    <div>
        <div class="col-xs-4">
            <?= Html::a('<h3>Артикулы для сервиса</h3>', ['replacement-part/index', 'bidId' => $model->id]) ?>
        </div>
        <div class="col-xs-8">
            <div style="margin: 20px 0 10px 0;">
                <?php $replacementPartModel = new \app\models\ReplacementPart(['bid_id' => $model->id]); ?>
                <?= $this->render('modal/create-replacement-part', [
                    'model' => $replacementPartModel,
                ]); ?>
            </div>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $replacementPartProvider,
        'summary' => '',
        'columns' => [
            'name',
            'price',
            'quantity',
            'total_price',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{more}',
                'buttons' => [
                    'more' => function ($url, $model, $key) {
                        return Html::a('Подробнее...',
                            [
                                'replacement-part/view',
                                'id' => $model->id,
                                'returnUrl' => Url::to(['bid/view', 'id' => $model->bid_id])
                            ]
                        );
                    }
                ],
            ],
        ],
    ]); ?>

<?php endif; ?>

<?php if (\Yii::$app->user->can('manageClientPropositions') && $clientPropositionProvider->totalCount): ?>

    <div class="form-group clearfix"></div>
    <div>
        <h3>Предложения для клиента (импорт из 1С)</h3>
        <?= GridView::widget([
            'dataProvider' => $clientPropositionProvider,
            'summary' => '',
            'columns' => [
                'name',
                'price',
                'quantity',
                'total_price'
            ],
        ]); ?>
    </div>

<?php endif; ?>
