$(function(){
    $('[data-toggle=pubdate]').each(function(e) {
        var pubdate = $(this).data('pubdate');
        $(this).text(moment(pubdate).format('DD/MM/YYYY HH:mm Z'));
    })
});