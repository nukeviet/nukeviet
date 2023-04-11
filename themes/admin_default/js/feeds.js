$(function() {
    $('[data-toggle=formSubmit]').on('submit', function(e) {
        e.preventDefault();
        if ( typeof (CKEDITOR) !== 'undefined') {
            for ( instance in CKEDITOR.instances ){
                CKEDITOR.instances[instance].updateElement();
            }
        }

        var data = $(this).serialize(),
            url = $(this).attr("action"),
            that = $(this);
        $.ajax({
            type : "POST",
            cache: false,
            url : url,
            data : data,
            dataType: 'json',
            success : function(a) {
                if (a.status == 'error') {
                    alert(a.mess);
                    $('[name=' + a.input + ']', that).focus()
                } else if (a.status == 'OK') {
                    window.location.href = window.location.href
                }
            }
        })
    })
});