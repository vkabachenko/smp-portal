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
});