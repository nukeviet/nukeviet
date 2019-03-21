/**
 * @Project NUKEVIET 4.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate Wed, 27 Jul 2011 14:55:22 GMT
 */

function nv_delete_law(url, id) {
    if (confirm(nv_is_del_confirm[0])) {
        $.post(url + '&nocache=' + new Date().getTime(), 'del=1&id=' + id, function(res) {
            if (res == 'OK') {
                location.reload();
            } else {
                alert(nv_is_del_confirm[2]);
            }
        });
    }
}

$(function() {
    $('.laws-download-file [data-toggle="tooltip"]').tooltip({
       container: "body"
    });
    $('[data-toggle="collapsepdf"]').each(function() {
        $('#' + $(this).attr('id')).on('shown.bs.collapse', function() {
            $(this).find('iframe').attr('src', $(this).data('src'));
        });
    });
});
