<!-- BEGIN: smtp -->
<!-- BEGIN: error -->
<div class="quote" style="width:98%">
	<blockquote class="error">{ERROR}</blockquote>
</div>
<div class="clear"></div>
<!-- END: error -->
<form action="" method="post" id="form_edit_smtp">
<table class="tab1">
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
    <tbody>
	<tr>
		<td>{LANG.outgoing_port}</td>
		<td><input type="text" name="smtp_port" value="{DATA.smtp_port}"
			style="width: 40px;" /></td>
	</tr>
	<tr>
		<td>{LANG.incoming_ssl}</td>
		<td>
           <select name="smtp_ssl">
              <!-- BEGIN: encrypted_connection -->
              <option value="{EMCRYPTED.id}" {EMCRYPTED.sl}>{EMCRYPTED.value}</option>
              <!-- END: encrypted_connection -->
           </select>
        </td>
	</tr>
    </tbody>
</table>
<div style="width:98%" class="quote">
    <blockquote><span>{LANG.smtp_pass_note}</span></blockquote>
</div>
<div class="clear"></div>
<table class="tab1">
	<caption>{LANG.smtp_username}</caption>
    <tbody>
	<tr>
		<td>{LANG.smtp_login}</td>
		<td><input type="text" name="smtp_username" value="{DATA.smtp_username}" style="width: 250px;" /></td>
	</tr>
    </tbody>
	<tbody class="second">
		<tr>
			<td>{LANG.smtp_pass}</td>
			<td><input type="password" name="smtp_password" value="{DATA.smtp_password}" style="width: 250px;" /></td>
		</tr>
	</tbody>
</table>
</div>
<table align="center" width="100%">
	<tr>
		<td><input type="submit" value="{LANG.submit}"
			style="padding: 0 10px;" /></td>
	</tr>
</table>
</form>
<script type="text/javascript">
//<![CDATA[
	document.getElementById('form_edit_smtp').setAttribute("autocomplete", "off");
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
//]]>
</script>
<!-- END: smtp -->