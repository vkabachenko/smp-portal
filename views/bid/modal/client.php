<?php
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $client \app\models\Client */
?>


<?php Modal::begin([
    'id' => 'client-modal',
]); ?>

<?= $this->render('//client/_form', compact('client')) ?>

<?php Modal::end(); ?>

<?php
$script = <<<JS
    $('.client-modal-btn').click(function(evt) {
        evt.preventDefault();
        $('#client-modal').modal('show');
    });

    $('body').on('click', '.btn-submit', function(evt) {
      evt.preventDefault();
      var form = $('#client-form');
      $.ajax({
        type: 'POST',
        data: form.serialize(),
        url: form.attr('action')
      }).then(function(result) {
          $('#client-modal').modal('hide');
      });
    });
JS;

$this->registerJs($script);

