function nv_del_row(hr, fid) {
	if (confirm(nv_is_del_confirm[0])) {
		$.post(hr.href + '&nocache=' + new Date().getTime(), 'del=1&id=' + fid, function(res) {
			if (res == 'OK') {
				window.location.href = window.location.href;
			} else {
				alert(nv_is_del_confirm[2]);
			}
		});
	}
	return false;
}

//  ---------------------------------------

function nv_download_file(fr, flnm) {
	var download_hits = document.getElementById('download_hits').innerHTML;
	download_hits = intval(download_hits);
	download_hits = download_hits + 1;
	document.getElementById('download_hits').innerHTML = download_hits;

	window.location.href = nv_siteroot + "index.php?" + nv_lang_variable + "=" + nv_sitelang + "&" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=down&file=" + flnm;
	return false;
}

//  ---------------------------------------

function nv_linkdirect(code) {
	var download_hits = document.getElementById('download_hits').innerHTML;
	download_hits = intval(download_hits);
	download_hits = download_hits + 1;
	document.getElementById('download_hits').innerHTML = download_hits;

	win = window.open(nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=down&code=' + code, 'mydownload');
	win.focus();
	return false;
}

//  ---------------------------------------

function nv_link_report(fid) {
	$.post(nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=report&nocache=' + new Date().getTime(), 'id=' + fid, function(res) {
		alert(report_thanks_mess);
	});
	return false;
}

//  ---------------------------------------

function nv_sendrating(fid, point) {
	if (fid > 0 && point > 0 && point < 6) {
		$.post(nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&nocache=' + new Date().getTime(), 'rating=' + fid + '_' + point, function(res) {
			$("#stringrating").html(res);
		});
	}
	return false;
}