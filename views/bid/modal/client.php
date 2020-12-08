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
        var url = $("#client_id").length ? $("#client_id").data("select") : $("#client_manufacturer_id").data("select");
        $.ajax({
            url: url,
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
      var clientId = $("#client_id").length ? '#client_id' : '#client_manufacturer_id';
      var clientName = $("#client_name").length ? '#client_name' : '#client_manufacturer_name';
      $.ajax({
        type: 'POST',
        data: form.serialize(),
        url: form.attr('action')
      }).then(function(result) {
          $('#client-modal').modal('hide');
          $(clientName).val(result.name);
          $(clientId).val(result.id);
      });
    });
JS;

$this->registerJs($script);

