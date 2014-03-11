<!-- BEGIN: smtp -->
<!-- BEGIN: error -->
<div class="quote">
	<blockquote class="error"> {ERROR} </blockquote>
</div>
<!-- END: error -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post" id="form_edit_smtp">
	<table class="tab1">
		<col class="w150"/>
		<col style="white-space: nowrap" />
		<tr>
			<td>{LANG.mail_config}</td>
			<td><label> <input type="radio" name="mailer_mode" value="smtp" {DATA.mailer_mode_smtpt} /> {LANG.type_smtp}</label><label> <input type="radio" name="mailer_mode" value="sendmail" {DATA.mailer_mode_sendmail} /> {LANG.type_linux}</label><label> <input type="radio" name="mailer_mode" value="" {DATA.mailer_mode_phpmail} /> {LANG.type_phpmail}</label></td>
		</tr>
	</table>
	<div {DATA.mailer_mode_smtpt_show} id="smtp">
		<table class="tab1">
			<caption> {LANG.smtp_server} </caption>
			<col style="width: 40%" />
			<col style="width: 60%" />
			<tbody>
				<tr>
					<td>{LANG.outgoing}</td>
					<td><input class="w250" type="text" name="smtp_host" value="{DATA.smtp_host}" /></td>
				</tr>
				<tr>
					<td>{LANG.outgoing_port}</td>
					<td><input class="w50"  type="text" name="smtp_port" value="{DATA.smtp_port}" /></td>
				</tr>
				<tr>
					<td>{LANG.incoming_ssl}</td>
					<td>
					<select name="smtp_ssl">
						<!-- BEGIN: encrypted_connection -->
						<option value="{EMCRYPTED.id}" {EMCRYPTED.sl}>{EMCRYPTED.value}</option>
						<!-- END: encrypted_connection -->
					</select></td>
				</tr>
			</tbody>
		</table>
		<table class="tab1">
			<caption> {LANG.smtp_username} </caption>
			<col style="width: 40%" />
			<col style="width: 60%" />
			<tbody>
				<tr>
					<td>{LANG.smtp_login}</td>
					<td><input class="w250" type="text" name="smtp_username" value="{DATA.smtp_username}" /></td>
				</tr>
				<tr>
					<td>{LANG.smtp_pass}</td>
					<td><input class="w250" type="password" name="smtp_password" value="{DATA.smtp_password}" /></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="center">
		<input type="submit" value="{LANG.submit}" style="padding: 0 10px;" />
	</div>
</form>
<script type="text/javascript">
	//<![CDATA[
	document.getElementById('form_edit_smtp').setAttribute("autocomplete", "off");
	$(function() {
		$("input[name=mailer_mode]").click(function() {
			var type = $(this).val();
			if (type == "smtp") {
				$("#smtp").show();
			} else {
				$("#smtp").hide();
			}
		});
	});
	//]]>
</script>
<!-- END: smtp -->