/**
 * @Project NUKEVIET 4.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate Wed, 27 Jul 2011 14:55:22 GMT
 */

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
function nv_delete_law(id, checkss) {
	if (confirm(nv_is_del_confirm[0])) {
		$.post(nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main&nocache=' + new Date().getTime(), 'id=' + id + '&checkss=' + checkss, function(res) {
			var r_split = res.split('_');
			if (r_split[0] == 'OK') {

				window.location.href = window.location.href;
			} else if (r_split[0] == 'NO') {
				alert(r_split[1]);
			} else {
				alert(nv_is_del_confirm[2]);
			}
		});
	}
	return false;
}