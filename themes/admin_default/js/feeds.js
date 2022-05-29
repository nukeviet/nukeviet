$(function() {
    $("input[name=selectimg]").click(function() {
        var obj = $(this).parent(),
            area = $('input[type=text]', obj).attr('id'),
            path = $(this).data('path'),
            type = "image";
        nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + area + "&path=" + path + "&type=" + type, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
        return false;
    });

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