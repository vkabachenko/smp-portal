<?php

use yii\bootstrap\Html;
use app\models\Bid;


/* @var $this yii\web\View */
/* @var $workshop \app\models\Workshop */
/* @var $ownAttributes array */
/* @var $availableAttributes array */

$this->title = 'Скрыть поля заявки для мастерской ' . $workshop->name;
$this->params['back'] = ['workshop/update', 'id' => $workshop->id];

?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <div>
        <div class="bid-attributes-title">
            <div class="col-xs-4">
                Скрытые поля
            </div>
            <div class="col-xs-2">

            </div>
            <div class="col-xs-4">
                Видимые поля
            </div>
        </div>
        <div>
            <div class="col-xs-4 bid-attributes-list">

                <ul>
                    <?php foreach ($ownAttributes as $attribute): ?>
                        <li data-action="remove"  data-attribute="<?= $attribute ?>">
                            <?= Bid::EDITABLE_ATTRIBUTES[$attribute] ?>
                        </li>
                    <?php endforeach; ?>
                </ul>

            </div>
            <div class="col-xs-2 bid-attributes-move">
                <?= Html::a('Перенести',
                    ['bid-attribute-move', 'workshopId' => $workshop->id],
                    ['class' => 'btn btn-primary', 'data' => ['method' => 'post']]);
                ?>
            </div>
            <div class="col-xs-4 bid-attributes-list">

                <ul>
                    <?php foreach ($availableAttributes as $attribute): ?>
                        <li data-action="add" data-attribute="<?= $attribute ?>">
                            <?= Bid::EDITABLE_ATTRIBUTES[$attribute] ?>
                        </li>
                    <?php endforeach; ?>
                </ul>

            </div>
        </div>

    </div>

</div>

<?php
$script = <<<JS
        $(function(){
            $('.bid-attributes-list li').click(function() {
                $('.bid-attributes-list li').removeClass('active');
                $(this).addClass('active');
                var params = {'attribute': $(this).data('attribute'), 'action': $(this).data('action')};
                $('.bid-attributes-move a').attr('data-params', JSON.stringify(params));
            });
            
            $('.bid-attributes-move a').click(function() {
                if ($('.bid-attributes-list li.active').length === 0) {
                    alert('Для переноса выделите поле');
                    return false;
                }
            });
        });
JS;
$this->registerJs($script);
