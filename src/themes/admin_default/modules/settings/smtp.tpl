<!-- BEGIN: smtp -->
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post" id="form_edit_smtp">
	<table class="table table-striped">
		<col class="w150"/>
		<col style="white-space: nowrap" />
		<tr>
			<td>{LANG.mail_config}</td>
			<td><label style="margin-right: 10px"><input type="radio" name="mailer_mode" value="smtp" {DATA.mailer_mode_smtpt} /> {LANG.type_smtp}</label><label style="margin-right: 10px"> <input type="radio" name="mailer_mode" value="sendmail" {DATA.mailer_mode_sendmail} /> {LANG.type_linux}</label><label> <input type="radio" name="mailer_mode" value="" {DATA.mailer_mode_phpmail} /> {LANG.type_phpmail}</label></td>
		</tr>
	</table>
	<div {DATA.mailer_mode_smtpt_show} id="smtp">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover">
				<caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.smtp_server} </caption>
				<col style="width: 40%" />
				<col style="width: 60%" />
				<tbody>
					<tr>
						<td>{LANG.outgoing}</td>
						<td><input class="w250 form-control" type="text" name="smtp_host" value="{DATA.smtp_host}" /></td>
					</tr>
					<tr>
						<td>{LANG.outgoing_port}</td>
						<td><input class="w50 form-control" type="text" name="smtp_port" value="{DATA.smtp_port}" /></td>
					</tr>
					<tr>
						<td>{LANG.incoming_ssl}</td>
						<td>
						<select name="smtp_ssl" class="form-control w100">
							<!-- BEGIN: encrypted_connection -->
							<option value="{EMCRYPTED.id}" {EMCRYPTED.sl}>{EMCRYPTED.value}</option>
							<!-- END: encrypted_connection -->
						</select></td>
					</tr>
                    <tr>
                        <td>{LANG.verify_peer_ssl}</td>
                        <td>
                        <label style="margin-right: 10px"><input type="radio" name="verify_peer_ssl" value="1" {PEER_SSL_YES}> {LANG.verify_peer_ssl_yes}</label>
                        <label style="margin-right: 10px"><input type="radio" name="verify_peer_ssl" value="0" {PEER_SSL_NO}> {LANG.verify_peer_ssl_no}</label>
                    </tr>
                      <tr>
                        <td>{LANG.verify_peer_name_ssl}</td>
                        <td>
                        <label style="margin-right: 10px"><input type="radio" name="verify_peer_name_ssl" value="1" {PEER_NAME_SSL_YES}> {LANG.verify_peer_ssl_yes}</label>
                        <label style="margin-right: 10px"><input type="radio" name="verify_peer_name_ssl" value="0" {PEER_NAME_SSL_NO}> {LANG.verify_peer_ssl_no}</label>
                       </td>
                    </tr>
				</tbody>
			</table>
		</div>
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover">
				<caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.smtp_username} </caption>
				<col style="width: 40%" />
				<col style="width: 60%" />
				<tbody>
					<tr>
						<td>{LANG.smtp_login}</td>
						<td><input class="w250 form-control" type="text" name="smtp_username" value="{DATA.smtp_username}" /></td>
					</tr>
					<tr>
						<td>{LANG.smtp_pass}</td>
						<td><input class="w250 form-control" type="password" autocomplete="off" name="smtp_password" value="{DATA.smtp_password}" /></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div class="text-center">
		<input type="submit" value="{LANG.submit}" class="btn btn-primary" />
	</div>
</form>
<script type="text/javascript">
	//<![CDATA[
	document.getElementById('form_edit_smtp').setAttribute("autocomplete", "off");
	//]]>
</script>
<!-- END: smtp -->