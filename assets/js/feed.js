$(function(){
    var banboxHeight = $('.banbox').outerHeight(true) + 15;
    $('.mainbox').css("padding-top", banboxHeight + 'px');

    $('[data-toggle=pubdate]').each(function(e) {
        var pubdate = $(this).data('pubdate');
        $(this).text(moment(pubdate).format('DD/MM/YYYY HH:mm Z'));
    });

    $(window).on("resize orientationchange", function() {
        banboxHeight = $('.banbox').outerHeight(true) + 15;
        $('.mainbox').css("padding-top", banboxHeight + 'px');
    });
});