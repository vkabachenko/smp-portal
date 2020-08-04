<?php

/* @var $this yii\web\View */
/* @var $attributes array */
/* @var $section array */

?>

<?php foreach ($section as $attribute): ?>
    <?php if (isset($attributes[$attribute])): ?>
        <?= $attributes[$attribute] ?>
    <?php endif; ?>
<?php endforeach; ?>



