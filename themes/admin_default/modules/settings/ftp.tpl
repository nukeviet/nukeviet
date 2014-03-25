<!-- BEGIN: no_support-->
<div class="infoerror">
	{LANG.ftp_error_support}
</div>
<!-- END: no_support-->
<!-- BEGIN: main-->
<!-- BEGIN: error-->
<div class="quote">
	<blockquote class="error"><span>{ERROR}</span></blockquote>
</div>
<!-- END: error -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post" id="form_edit_ftp">
	<table class="tab1">
		<tfoot>
			<tr>
				<td colspan="2" style="padding-left: 200px;"><input type="submit" value="{LANG.submit}"/></td>
			</tr>
		</tfoot>
		<tbody>
			<tr>
				<td><strong>{LANG.server}</strong></td>
				<td><input class="w250" type="text" name="ftp_server" value="{VALUE.ftp_server}" /> <span> {LANG.port} </span> <input type="text" value="{VALUE.ftp_port}" name="ftp_port" style="width: 30px;"/></td>
			</tr>
			<tr>
				<td><strong>{LANG.username}</strong></td>
				<td><input class="w250" type="text" name="ftp_user_name" id="ftp_user_name_iavim" value="{VALUE.ftp_user_name}" /></td>
			</tr>
			<tr>
				<td><strong>{LANG.password}</strong></td>
				<td><input class="w250" type="password" name="ftp_user_pass" id="ftp_user_pass_iavim" value="{VALUE.ftp_user_pass}" /></td>
			</tr>
			<tr>
				<td><strong>{LANG.ftp_path}</strong></td>
				<td><input class="w250" type="text" name="ftp_path" id="ftp_path_iavim" value="{VALUE.ftp_path}" /> <input type="button" id="autodetectftp" value="{LANG.ftp_auto_detect_root}"/></td>
			</tr>
		</tbody>
	</table>
</form>
<script type="text/javascript">
	document.getElementById('form_edit_ftp').setAttribute("autocomplete", "off");
	$(document).ready(function() {
		$('#autodetectftp').click(function() {
			var ftp_server = $('input[name="ftp_server"]').val();
			var ftp_user_name = $('input[name="ftp_user_name"]').val();
			var ftp_user_pass = $('input[name="ftp_user_pass"]').val();
			var ftp_port = $('input[name="ftp_port"]').val();

			if (ftp_server == '' || ftp_user_name == '' || ftp_user_pass == '') {
				alert('{LANG.ftp_error_full}');
				return;
			}

			$(this).attr('disabled', 'disabled');

			var data = 'ftp_server=' + ftp_server + '&ftp_port=' + ftp_port + '&ftp_user_name=' + ftp_user_name + '&ftp_user_pass=' + ftp_user_pass + '&tetectftp=1';
			var url = '{DETECT_FTP}';

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
	});
</script>
<!-- END: main -->