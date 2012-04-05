<!-- BEGIN: no_support-->
<div class="infoerror">
	{LANG.ftp_error_support}
</div>
<!-- END: no_support-->
<!-- BEGIN: main-->
<!-- BEGIN: error-->
<div class="quote" style="width:98%">
	<blockquote class="error"><span>{ERROR}</span></blockquote>
</div>
<div class="clear"></div>
<!-- END: error -->
<form action="" method="post" id="form_edit_ftp">
    <table class="tab1">
    	<tbody>
        <tr>
            <td>
                <strong>{LANG.server}</strong>
            </td>
            <td>
                <input type="text" name="ftp_server" value="{VALUE.ftp_server}" style="width: 250px"/><span>{LANG.port}</span>
                <input type="text" value="{VALUE.ftp_port}" name="ftp_port" style="width: 30px;"/>
            </td>
        </tr>
        </tbody>
        <tbody class="second">
            <tr>
                <td>
                    <strong>{LANG.username}</strong>
                </td>
                <td>
                    <input type="text" name="ftp_user_name" id="ftp_user_name_iavim" value="{VALUE.ftp_user_name}" style="width: 250px;"/>
                </td>
            </tr>
        </tbody>
        <tbody>
        <tr>
            <td>
                <strong>{LANG.password}</strong>
            </td>
            <td>
                <input type="password" name="ftp_user_pass" id="ftp_user_pass_iavim" value="{VALUE.ftp_user_pass}" style="width: 250px"/>
            </td>
        </tr>
        </tbody>
        <tbody class="second">
            <tr>
                <td>
                    <strong>{LANG.ftp_path}</strong>
                </td>
                <td>
                    <input type="text" name="ftp_path" id="ftp_path_iavim" value="{VALUE.ftp_path}" style="width: 250px;"/>
					<input type="button" id="autodetectftp" value="{LANG.ftp_auto_detect_root}"/>
                </td>
            </tr>
        </tbody>
        <tbody>
        <tr>
            <td colspan="2" style="text-align: center;">
                <input type="submit" value="{LANG.submit}" style="padding: 2px 10px;"/>
            </td>
        </tr>
        </tbody>
    </table>
</form>
<script type="text/javascript">
document.getElementById('form_edit_ftp').setAttribute("autocomplete", "off");
$(document).ready(function(){
	$('#autodetectftp').click(function(){
		var ftp_server = $('input[name="ftp_server"]').val();
		var ftp_user_name = $('input[name="ftp_user_name"]').val();
		var ftp_user_pass = $('input[name="ftp_user_pass"]').val();
		var ftp_port = $('input[name="ftp_port"]').val();
		
		if( ftp_server == '' || ftp_user_name == '' || ftp_user_pass == '' )
		{
			alert('{LANG.ftp_error_full}');
			return;
		}
		
		$(this).attr('disabled', 'disabled');
		
		var data = 'ftp_server=' + ftp_server + '&ftp_port=' + ftp_port + '&ftp_user_name=' + ftp_user_name + '&ftp_user_pass=' + ftp_user_pass + '&tetectftp=1';
		var url = '{DETECT_FTP}';
		
		$.ajax({type:"POST", url:url, data:data, success:function(c){
			c = c.split('|');
			if( c[0] == 'OK' ){
				$('#ftp_path_iavim').val(c[1]);
			}else{
				alert(c[1]);
			}
			$('#autodetectftp').removeAttr('disabled');
		}});
	});
});
</script>

<!-- END: main -->
