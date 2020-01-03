$(function() {
    $(document).ready(function () {

        $('.column-hint').each(function () {
            var label = $(this);
            var hint = $('<span>')
                .addClass('glyphicon glyphicon-question-sign label-hint')
                .attr('title', label.data('title'))
                .attr('data-toggle', 'tooltip');
            label.prepend(hint);
        });

        $('[data-toggle="tooltip"]').tooltip();
    });


    $('.bid-attributes-list li').click(function() {
        $('.bid-attributes-list li').removeClass('active');
        $(this).addClass('active');
        var params = {'attribute': $(this).data('attribute'), 'action': $(this).data('action')};
        $('.bid-attributes-move a').attr('data-params', JSON.stringify(params));
    });

    $('.bid-attributes-move a').click(function() {
        if ($('.bid-attributes-list li.active').length === 0) {
            swal('Ошибка', 'Для переноса выделите поле', 'error');
            return false;
        }
    });

    $('#feedback-form').submit(function(evt) {
        evt.preventDefault();
        var form = $(this);
        $.ajax({
            url: form.attr('action'),
            method: form.attr('method'),
            data: form.serialize()
        }).then(function(result) {
            $('#feedback-modal').modal('hide');
            if (result) {
                swal('Успех', 'Сообщение успешно отправлено', 'success');
            } else {
                swal('Ошибка', 'Ошибка при отправке сообщения', 'error');
            }
        }).catch(function(error) {
            swal('Ошибка', error.message, 'error');
        })
    });
});