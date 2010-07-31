<!-- BEGIN: smtp -->
<form action="" method="post">
<table class="tab1" summary="">
	<col style="width: 150px; white-space: nowrap" />
	<tr>
		<td>{LANG.mail_config}</td>
		<td><label><input type="radio" name="mailer_mode" value="smtp" {DATA.mailer_mode_smtpt} />{LANG.type_smtp}</label>
		<label><input type="radio" name="mailer_mode" value="sendmail" {DATA.mailer_mode_sendmail} />{LANG.type_linux}</label>
		<label><input type="radio" name="mailer_mode" value="" {DATA.mailer_mode_phpmail} />{LANG.type_phpmail}</label></td>
	</tr>
</table>
<div {DATA.mailer_mode_smtpt_show} id='smtp'>
<table class="tab1">
	<caption>{LANG.smtp_server}</caption>
	<tbody class="second">
		<tr>
			<td>{LANG.outgoing}</td>
			<td><input type="text" name="smtp_host" value="{DATA.smtp_host}"
				style="width: 250px;" /></td>
		</tr>
	</tbody>
	<tr>
		<td>{LANG.outgoing_port}</td>
		<td><input type="text" name="smtp_port" value="{DATA.smtp_port}"
			style="width: 40px;" /></td>
	</tr>
	<tr>
		<td>{LANG.incoming_ssl}</td>
		<td><input type="checkbox" name="smtp_ssl" value="1" {DATA.smtp_ssl_checked} /></td>
	</tr>
</table>
<table class="tab1">
	<caption>{LANG.smtp_username}</caption>
	<tr>
		<td>{LANG.smtp_login}</td>
		<td><input type="text" name="smtp_username"
			value="{DATA.smtp_username}" style="width: 250px;" /></td>
	</tr>
	<tbody class="second">
		<tr>
			<td>{LANG.smtp_pass}</td>
			<td><input type="password" name="smtp_password"
				value="{DATA.smtp_password}" style="width: 250px;" /></td>
		</tr>
	</tbody>
</table>
</div>
</table>
<table align="center" width="100%">
	<tr>
		<td><input type="submit" value="{LANG.submit}"
			style="padding: 0 10px;" /></td>
	</tr>
</table>
<script type="text/javascript">
    $(function(){
        $("input[name=mailer_mode]").click(function(){
        var type = $(this).val();
        if (type=="smtp"){
			$("#smtp").show();
        } else {
			$("#smtp").hide();
        }
        });
    });
</script> <!-- END: smtp -->