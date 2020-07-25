$(function() {
    $(document).ready(function () {

        $('.column-hint').each(function () {
            var label = $(this);
            if (label.data('title')) {
                var hint = $('<span>')
                    .addClass('glyphicon glyphicon-question-sign label-hint')
                    .attr('title', label.data('title'))
                    .attr('data-html', label.data('html'))
                    .attr('data-toggle', 'tooltip');
                label.prepend(hint);
            }
        });

        $('[data-toggle="tooltip"]').tooltip({placement: top});
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

    $('.btn-feedback').click(function() {
        $('[data-target="#feedback-modal"]').trigger('click');
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

    $('#page-helper-form').submit(function(evt) {
        evt.preventDefault();
        var form = $(this);
        $.ajax({
            url: form.attr('action'),
            method: form.attr('method'),
            data: form.serialize()
        }).then(function(result) {
            $('#page-helper-modal').modal('hide');
            if (result) {
                swal('Успех', 'Текст успешно сохранен', 'success');
            } else {
                swal('Ошибка', 'Ошибка при сохранении текста', 'error');
            }
        }).catch(function(error) {
            swal('Ошибка', error.message, 'error');
        })
    });

    $('.news-like').click(function() {
        var self = $(this);
        var status = self.hasClass('news-like-up') ? 'like' : 'dislike';
        var wrap = self.closest('.news-article-wrap');

        $.ajax({
            url: self.data("url"),
            method: "POST",
            data: {
                status: status
            }
        })
            .then(function(result) {
                wrap.find('.news-like-up .news-like-count').text(result.countUp);
                wrap.find('.news-like-down .news-like-count').text(result.countDown);

                if (result.isUserUp) {
                    wrap.find('.news-like-up').addClass('news-own-like');
                } else {
                    wrap.find('.news-like-up').removeClass('news-own-like');
                }

                if (result.isUserDown) {
                    wrap.find('.news-like-down').addClass('news-own-like');
                } else {
                    wrap.find('.news-like-down').removeClass('news-own-like');
                }
            })
            .catch(function(error) {
                swal('Ошибка', error.message, 'error');
            });
    });

    $('.save-attribute-sections').click(function(evt) {
        evt.preventDefault();

        section1 = [];
        $('.attribute-section1 li').each(function() {
            section1.push($(this).data('attribute'));
        });

        section2 = [];
        $('.attribute-section2 li').each(function() {
            section2.push($(this).data('attribute'));
        });

        section3 = [];
        $('.attribute-section3 li').each(function() {
            section3.push($(this).data('attribute'));
        });

        $.ajax({
            url: $(this).attr('href'),
            method: 'POST',
            data: {
                'section1': section1,
                'section2': section2,
                'section3': section3,
            }
        })
            .then(function() {
                swal('Успех', 'Успешно сохранено', 'success')
                .then(function() {
                    location = $('.back-section a').attr('href');
                });
            })
            .catch(function(error) {
                swal('Ошибка', error.message, 'error');
            });
    });

    $('input')
        .on('focus', function() {
            if ($(window).width() < 768) {
                $('.navbar-main').hide();
            }
        })
        .on('blur', function() {
            $('.navbar-main').show();
        });
});