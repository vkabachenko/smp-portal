<?php

/* @var $this yii\web\View */
/* @var $attributes array */
/* @var $section array */

use app\models\Bid;
?>

<table class="table table-striped">

    <?php foreach ($section as $attribute): ?>
        <tr>
            <th>
                <?= Bid::getAllAttributes()[$attribute] ?>
            </th>
            <td>
                <?= $attributes[$attribute] ?>
            </td>
        </tr>
    <?php endforeach; ?>

</table>


