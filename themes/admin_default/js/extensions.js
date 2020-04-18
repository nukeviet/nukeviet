/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 9:36
 */

var LANG = [];

if( typeof( CFG ) == 'undefined' ){
	var CFG = [];
	CFG.id = 0;
	CFG.string_data = '';
}

var player_width = $(window).width();
var player_height = $(window).height();
if( player_width > 1060 ){
	player_width = 1000;
}else{
	player_width = player_width - 60;
}
if( player_height > 660 ){
	player_height = 600;
}else{
	player_height = player_height - 60;
}

var nv_loading = '<div class="text-center"><em class="fa fa-spin fa-spinner fa-2x m-bottom wt-icon-loading"></em></div>';

var EXT = {
	tid: CFG.id,
	isDownloaded: false,
	startDownload: function(){
		if( ! EXT.isDownloaded ){
			EXT.isDownloaded = true;

			$('#warnning').hide();
			$('#file-download').show();
			$('#file-download .waiting').show();

			$.ajax({
				type: 'POST',
				url: script_name,
				data: nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=download&data=' + CFG.string_data,
				success: function(e){
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
		$('#file-download .status').removeClass('fa-meh-o').addClass('fa-smile-o');
		$('#file-download .complete').show();

		$('#file-download-response').html('<div class="alert alert-success">' + LANG.download_ok + '</div>');

		setTimeout( "EXT.redirect()", 3000 );
	},
	handleError: function(m){
		$('#file-download').addClass('text-danger');
		$('#file-download .status').removeClass('fa-meh-o').addClass('fa-frown-o');
		$('#file-download-response').html('<div class="alert alert-danger">' + m + '</div>');
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

function checkform(){
	var zipfile = $("input[name=extfile]").val();
	if( zipfile == "" ){
		alert(LANG.install_error_nofile);
		return false;
	}
	var filezip = zipfile.slice(-3);
	var filegzip = zipfile.slice(-2);
	var allowext = new Array("zip", "gz");
	if (! checkext(allowext, filezip) || ! checkext(allowext, filegzip)) {
		alert(LANG.install_error_filetype);
		return false;
	}
	return true;
}

$(document).ready(function(){
	// Login
	$('#login-form').submit(function(e){
		e.preventDefault();
		var username = $('#username').val();
		var password = $('#password').val();
		$('#login-result').html('');

		if( username == '' ){
			$('#login-result').html('<div class="alert alert-danger">' + LANG.username_empty + '</div>');
		}else if( password == '' ){
			$('#login-result').html('<div class="alert alert-danger">' + LANG.password_empty + '</div>');
		}else{
			$('#login-form input, #login-form button').attr('disabled', 'disabled');
			$('#login-result').html('<div class="text-center"><em class="fa fa-spin fa-spinner fa-2x m-bottom wt-icon-loading"></em></div>');

			$.post(
				script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=login&nocache=' + new Date().getTime(),
				'username=' + username + '&password=' + password + '&redirect=' + $('[name="redirect"]').val(),
				function(res) {
					$('#login-form input, #login-form button').removeAttr('disabled');
					$('#login-result').html( res );
				}
			);
		}
	});

	// Manage
	$('[data-toggle="tooltip"]').tooltip();
	$('.package-ext').click(function(e){
		window.location = $(this).data('href');
	});
	$('.delete-ext').click(function(){
		if( confirm(LANG.delele_ext_confirm) ){
			$.post($(this).data('href') + '&nocache=' + new Date().getTime(), '', function(res) {
				res = res.split('_');
				alert(res[1]);

				if( res[0] == 'OK' ){
					window.location.href = window.location.href;
				}
			});
		}
	});
});