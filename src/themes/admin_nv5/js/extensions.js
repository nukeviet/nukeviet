/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 9:36
 */

var LANG = [];

if (typeof(CFG) == 'undefined') {
    var CFG = [];
    CFG.id = 0;
    CFG.string_data = '';
}

var EXT = {
    tid: CFG.id,
    isDownloaded: false,
    startDownload: function(){
        if (!EXT.isDownloaded) {
            EXT.isDownloaded = true;

            $('#extInstallWarning').hide();
            $('#file-download').show();
            $('#file-download .waiting').show();

            $.ajax({
                type: 'POST',
                url: script_name,
                data: nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=download&data=' + CFG.string_data,
                success: function(e) {
                    $('#file-download .waiting').hide();
                    e = e.split('|');
                    if( e[0] == 'OK' ){
                        EXT.handleOk(e[1]);
                    }else{
                        EXT.handleError(e[1]);
                    }
                }
            });
        }
    },
    cancel: function(){
        window.location = CFG.cancel_link;
    },
    handleOk: function(f){
        $('#file-download').addClass('text-success');
        $('#file-download .status').removeClass('fa-meh').addClass('fa-smile');
        $('#file-download .complete').show();

        $('#file-download-response').html('<div class="alert alert-success"><div class="message pl-3">' + LANG.download_ok + '</div></div>');

        setTimeout("EXT.redirect()", 3000);
    },
    handleError: function(m){
        $('#file-download').addClass('text-danger');
        $('#file-download .status').removeClass('fa-meh').addClass('fa-frown');
        $('#file-download-response').html('<div class="alert alert-danger"><div class="message pl-3">' + m + '</div></div>');
    },
    redirect: function(){
        var url = script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=extensions&' + nv_fc_variable + '=upload&uploaded=1';
        window.location = url;
    }
};

function checkext(myArray, myValue) {
    var type = eval(myArray).join().indexOf(myValue) >= 0;
    return type;
}

function checkform() {
    var file = $("input[name=extfile]");
    var zipfile = file.val();
    if (zipfile == "") {
        alert(file.data("lang-nofile"));
        return false;
    }
    var filezip = zipfile.slice(-3);
    var filegzip = zipfile.slice(-2);
    var allowext = new Array("zip", "gz");
    if (!checkext(allowext, filezip) || !checkext(allowext, filegzip)) {
        alert(file.data("lang-filetype"));
        return false;
    }
    return true;
}

$(document).ready(function(){
    // Đăng nhập Merchant site
    $('#login-form').submit(function(e) {
        e.preventDefault();

        var load = $('[type="submit"]', $(this)).find('.load');
        if (load.is(":visible")) {
            return;
        }

        var username = $('#username').val();
        var password = $('#password').val();

        if (username == '') {
            $.gritter.add({
                title: LANG.error,
                text: LANG.username_empty,
                class_name: "color danger"
            });
        } else if (password == '') {
            $.gritter.add({
                title: LANG.error,
                text: LANG.password_empty,
                class_name: "color danger"
            });
        } else {
            load.removeClass("d-none");
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=login&nocache=' + new Date().getTime(),
                data: {
                    'username': username,
                    'password': password,
                    'redirect': $('[name="redirect"]').val()
                },
                dataType: 'json',
                cache: false,
                success: function(data) {
                    load.addClass("d-none");
                    if (data.status != "success") {
                        $.gritter.add({
                            title: LANG.error,
                            text: data.message,
                            class_name: "color danger"
                        });
                        return;
                    }
                    window.location = data.url;
                },
                error: function(jqXHR, exception) {
                    load.addClass("d-none");
                    $.gritter.add({
                        title: LANG.error,
                        text: "Error request!!!",
                        class_name: "color danger"
                    });
                }
            });
        }
    });

    // Xóa ứng dụng
    $('.delete-ext').click(function(e) {
        e.preventDefault();
        if (confirm($(this).data("lang-confirm"))) {
            $.post($(this).attr('href') + '&nocache=' + new Date().getTime(), '', function(res) {
                res = res.split('_');
                alert(res[1]);
                if (res[0] == 'OK') {
                    location.reload();
                }
            });
        }
    });

    // Xử lý khi xem chi tiết ứng dụng trong modal
    $('#md-ext-detail').on('shown.bs.modal', function(e) {
        $(this).find('.ext-detail-content').load($(this).data("urlext"), function() {
            $('.ext-detail-bodyhtml a').on("click", function() {
                $(this).attr("target", "_blank");
            });
            $('[data-toggle="tooltip"]', $('#md-ext-detail')).tooltip();
        });
    });
});
