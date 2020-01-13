<?php
use yii\bootstrap\Html;
use yii\helpers\Url;

/* @var $model \app\models\News */
/* @var $usersUp \app\models\User[] */
/* @var $usersDown \app\models\User[] */

$this->title = 'Оценки новости ' . $model->title;
$this->params['back'] = ['index'];
?>

<h1><?= Html::encode($this->title) ?></h1>

<div>
    <div class="col-xs-6">
        <h3>
            Положительных оценок: <?= count($usersUp) ?>
        </h3>
        <div>
            <?php foreach ($usersUp as $user): ?>
                <p>
                    <?= Html::a($user->name, ['user/profile', 'id' => $user->id, 'returnUrl' => Url::current()]) ?>
                </p>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="col-xs-6">
        <h3>
            Отрицательных оценок: <?= count($usersDown) ?>
        </h3>
        <div>
            <?php foreach ($usersDown as $user): ?>
                <p>
                    <?= Html::a($user->name, ['user/profile', 'id' => $user->id, 'returnUrl' => Url::current()]) ?>
                </p>
            <?php endforeach; ?>
        </div>
    </div>
</div>
