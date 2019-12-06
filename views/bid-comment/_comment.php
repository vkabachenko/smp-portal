<?php
/* @var $model \app\models\BidComment */
?>

<div>
    <div class="col-sm-4 col-xs-12">
        <div class="col-sm-12 col-xs-6">
            <?= $model->created_at ?>
        </div>
        <div  class="col-sm-12 col-xs-6">
            <?= $model->user_id ? $model->user->name . ' ' . $model->user->role : ''?>
        </div>
    </div>
    <div class="col-sm-8 col-xs-12">
        <div style="min-height: 50px; max-height: 300px; overflow: auto; border: solid 1px #aaa; padding: 5px;">
            <?= $model->comment ?>
        </div>
    </div>
    <div class="clearfix"></div>
<div>
    <hr style="color: #bbb;">
</div>



</div>
