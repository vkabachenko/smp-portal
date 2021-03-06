<?php

use yii\bootstrap\Html;
use app\models\Bid;


/* @var $this yii\web\View */
/* @var $agency \app\models\Agency */
/* @var $ownAttributes array */
/* @var $availableAttributes array */

$this->title = 'Скрыть поля заявки для представительства ' . $agency->name;
$this->params['back'] = ['agency/update', 'id' => $agency->id];

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
                    ['bid-attribute-move', 'agencyId' => $agency->id],
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


