<?php
/* @var $model \app\models\BidComment */
?>

<div style="margin: 0 10px;">
    <div>
        Дата создания <?= $model->created_at ?>
    </div>
    <div>
        Комментатор <?= $model->user->username ?>
    </div>
    <div style="min-height: 50px; max-height: 300px; overflow: auto; border: solid 1px #aaa; padding: 5px;">
        <?= $model->comment ?>
    </div>
</div>
