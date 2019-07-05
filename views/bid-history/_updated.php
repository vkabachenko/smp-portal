<?php
/* @var $model \app\models\BidHistory */
?>

<div style="margin: 10px 0;">
    <h4>Измененнные атрибуты</h4>

    <table class="table table-striped">
        <tr>
            <th>Атрибут заявки</th>
            <th>Старое значение</th>
            <th>Новое значение</th>
        </tr>
        <?php foreach ($model->updated_attributes as $updatedAttribute): ?>
            <tr>
                <td>
                    <?= $model->bid->getAttributeLabel($updatedAttribute['name']) ?>
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
