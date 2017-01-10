/**
 * @Project NUKEVIET 3.0
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
