$(function(){
    $('.bid-attributes-list li').click(function() {
        $('.bid-attributes-list li').removeClass('active');
        $(this).addClass('active');
        var params = {'attribute': $(this).data('attribute'), 'action': $(this).data('action')};
        $('.bid-attributes-move a').attr('data-params', JSON.stringify(params));
    });

    $('.bid-attributes-move a').click(function() {
        if ($('.bid-attributes-list li.active').length === 0) {
            alert('Для переноса выделите поле');
            return false;
        }
    });

    $('#feedback-form').submit(function(evt) {
        evt.preventDefault();
        var form = $(this);
        //var formdata = new FormData(form[0]);
        $.ajax({
            url: form.attr('action'),
            method: form.attr('method'),
            //contentType: false, // обязательно
            //processData: false, // для FormData
            //data: formdata
            data: form.serialize()
        }).then(function(result) {
            $('#feedback-modal').modal('hide');
            var msg = result ? 'Сообщение успешно отправлено' : 'Ошибка при отправке сообщения';
            alert(msg);
        }).catch(function(error) {
            alert(error.message);
        })
    });
});