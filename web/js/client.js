$('.btn-submit').click(function(evt) {
    evt.preventDefault();
    var form = $('#client-form');
    $.ajax({
        type: 'POST',
        data: form.serialize(),
        url: form.attr('action')
    }).then(function(result) {
        location.href = $('.back-section a').attr('href');
    });
});