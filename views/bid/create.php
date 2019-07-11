<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Bid */
/* @var $uploadForm \app\models\form\MultipleUploadForm */
/* @var $commentForm \app\models\form\CommentForm */

$this->title = 'Новая заявка';
$this->params['back'] = ['index'];
?>
<div>

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
        'uploadForm' => $uploadForm,
        'commentForm' => $commentForm
    ]) ?>

</div>
