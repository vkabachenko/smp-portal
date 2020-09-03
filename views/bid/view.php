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

        <?php if (\Yii::$app->user->can('viewBid', ['bidId' => $model->id])): ?>
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
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        Отправить
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                        <li>
                            <?= Html::a('Акт диагностики и фото',
                                ['send-act/index', 'bidId' => $model->id, 'subType' => TemplateModel::SUB_TYPE_ACT_DIAGNOSTIC],
                                ['class' => 'btn btn-default'])
                            ?>
                        </li>
                        <?php if (\Yii::$app->user->can('manager')): ?>
                            <li>
                                <?= Html::a('Акт списания и фото',
                                    ['send-act/index', 'bidId' => $model->id, 'subType' => TemplateModel::SUB_TYPE_ACT_WRITE_OFF],
                                    ['class' => 'btn btn-default'])
                                ?>
                            </li>
                            <li>
                                <?= Html::a('Акт не гарантии и фото',
                                    ['send-act/index', 'bidId' => $model->id, 'subType' => TemplateModel::SUB_TYPE_ACT_NO_WARRANTY],
                                    ['class' => 'btn btn-default'])
                                ?>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
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
    <?= Html::a('Комментарии', ['bid-comment/index', 'bidId' => $model->id], ['class' => 'btn btn-success']) ?>
</div>

<?php if (!empty($model->bidImages)): ?>
    <?= $this->render('_images', ['model' => $model]) ?>
<?php endif; ?>

<div class="form-group clearfix"></div>
<div>
    <?= Html::a('Добавить фото', ['bid-image/create', 'bidId' => $model->id], ['class' => 'btn btn-primary']) ?>
</div>

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
        'rowOptions'=>function (\app\models\Spare $spare)
        {if ($spare->is_paid) {return ['class'=>'paid-spare'];} },
        'columns' => [
            'name',
            'quantity',
            'price',
            'total_price',
        ],
    ]); ?>
</div>

<?php if (\Yii::$app->user->can('manageReplacementParts') && $replacementPartProvider->totalCount): ?>

<div class="form-group clearfix"></div>
<div>
    <h3>Артикулы для сервиса (импорт из 1С)</h3>
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
                        return Html::a('Подробнее...',['replacement-part/view', 'id' => $model->id]);
                    }
                ],
            ],
        ],
    ]); ?>
</div>

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
