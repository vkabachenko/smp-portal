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

    $('.new-client-modal-btn').click(function(evt) {
        evt.preventDefault();
        $.ajax({
            url: $("#client_id").data("select"),
            method: "GET",
            data: {
                id: null
            },
            success: function(html) {
                $("#client-modal .modal-body").html(html);
                $('#client-modal').modal('show');
            },
            error: function (jqXHR, status) {
                console.log(status);
                response([]);
            }
        });
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
          $('#client_name').val(result.name);
          $('#client_id').val(result.id);
      });
    });
JS;

$this->registerJs($script);

