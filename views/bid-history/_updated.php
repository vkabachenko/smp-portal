<?php
/* @var $model \app\models\BidHistory */
/* @var $obj \yii\db\ActiveRecord */

$obj = \Yii::createObject($model->model_class ?: \app\models\Bid::class)
?>

<div style="margin: 10px 0;">
    <h4>Измененнные атрибуты</h4>

    <table class="table table-striped">
        <tr>
            <th>Атрибут</th>
            <th>Старое значение</th>
            <th>Новое значение</th>
        </tr>
        <?php foreach ($model->updated_attributes as $updatedAttribute): ?>
            <tr>
                <td>
                    <?= $obj->getAttributeLabel($updatedAttribute['name']) ?>
                </td>
                <td>
                    <?= $updatedAttribute['old_value'] ?>
                </td>
                <td>
                    <?= $updatedAttribute['value'] ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
