/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 9:36
 */

function nv_is_del_cron(cronid) {
	if (confirm(nv_is_del_confirm[0])) {
		$.get(script_name + "?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=cronjobs_del&id=" + cronid + "&nocache=" + new Date().getTime(), function(res) {
			if (res == 1) {
				alert(nv_is_del_confirm[1]);
				window.location.href = window.location.href;
			} else {
				alert(nv_is_del_confirm[2]);
			}
		});
	}
	return false;
}

function show_rewrite_op() {
	if ($("input[name=rewrite_optional]").is(":checked")) {
		$('#tr_rewrite_op_mod').show();
	} else {
		$('#tr_rewrite_op_mod').hide();
	}
}

function nv_chang_weight(pid) {
	window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=plugin&pid=' + pid + '&weight=' + $('#weight_' + pid).val();
}

$(document).ready(function(){
	// System
	$('#cdn_download').click(function() {
		window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=cdn&cdndl=' + CFG.cdndl;
	});
	
	// Smtp
	$("input[name=mailer_mode]").click(function() {
		var type = $(this).val();
		if (type == "smtp") {
			$("#smtp").show();
		} else {
			$("#smtp").hide();
		}
	});

	// Security
	if( $.fn.datepicker ){
		$(".datepicker, #start_date").datepicker({
			showOn : "both",
			dateFormat : "dd/mm/yy",
			changeMonth : true,
			changeYear : true,
			showOtherMonths : true,
			buttonImage : nv_base_siteurl + "assets/images/calendar.gif",
			buttonImageOnly : true
		});
	}
	$('.submit-security').click(function() {
		var ip = $('input[name=ip]').val();
		$('input[name=ip]').focus();
		if (ip == '') {
			alert(LANG.banip_error_ip);
			return false;
		}
		var area = $('select[name=area]').val();
		$('select[name=area]').focus();
		if (area == '0') {
			alert(LANG.banip_error_area);
			return false;
		}
	});
	$('a.deleteone-ip').click(function() {
		if (confirm(LANG.banip_delete_confirm)) {
			var url = $(this).attr('href');
			$.ajax({
				type : 'POST',
				url : url,
				data : '',
				success : function(data) {
					alert(LANG.banip_del_success);
					window.location = script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=security";
				}
			});
		}
		return false;
	});
	
	// Site setting
	$("#select-site-logo").click(function() {
		var area = "site_logo";
		var path = "";
		var currentpath = "images";
		var type = "image";
		nv_open_browse(script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=upload&popup=1&area=" + area + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
		return false;
	});
	
	// FTP setting
	$('#autodetectftp').click(function() {
		var ftp_server = $('input[name="ftp_server"]').val();
		var ftp_user_name = $('input[name="ftp_user_name"]').val();
		var ftp_user_pass = $('input[name="ftp_user_pass"]').val();
		var ftp_port = $('input[name="ftp_port"]').val();

		if (ftp_server == '' || ftp_user_name == '' || ftp_user_pass == '') {
			alert(LANG.ftp_error_full);
			return;
		}

		$(this).attr('disabled', 'disabled');

		var data = 'ftp_server=' + ftp_server + '&ftp_port=' + ftp_port + '&ftp_user_name=' + ftp_user_name + '&ftp_user_pass=' + ftp_user_pass + '&tetectftp=1';
		var url = CFG.detect_ftp;

		$.ajax({
			type : "POST",
			url : url,
			data : data,
			success : function(c) {
				c = c.split('|');
				if (c[0] == 'OK') {
					$('#ftp_path_iavim').val(c[1]);
				} else {
					alert(c[1]);
				}
				$('#autodetectftp').removeAttr('disabled');
			}
		});
	});
	
	// 
	$('#ssl_https').change(function(){
		var val = $(this).data('val');
		var mode = $(this).val();
		
		if( mode != 0 && val == 0 && ! confirm(LANG.note_ssl) ){
			$(this).val('0');
			return;
		}
		
		if( mode == '3' ){
			$('#ssl_https_modules').removeClass('hidden');
		}else{
			$('#ssl_https_modules').addClass('hidden');
		}
	});
});