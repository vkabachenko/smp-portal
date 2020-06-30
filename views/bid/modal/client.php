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

    $('#client-form').submit(function(evt) {
      evt.preventDefault();
      $.ajax({
        type: 'POST',
        data: $(this).serialize(),
        url: $(this).attr('action')
      }).then(function(result) {
          $('#client-modal').modal('hide');
          $('#client_id').val(result.id);
      });
    });
JS;

$this->registerJs($script);

