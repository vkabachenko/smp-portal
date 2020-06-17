<?php

/* @var $filename string */

use yii\bootstrap\Html;

?>

<div>
    <?= Html::a('Скачать сформированный xml-файл', ['download', 'filename' => $filename]) ?>
</div>
