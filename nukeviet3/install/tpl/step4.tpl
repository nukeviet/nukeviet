<!-- BEGIN: step -->
<table id="checkchmod" cellspacing="0"
	summary="{LANG.checkchmod_detail}" style="width: 100%;">
	<caption>{LANG.if_chmod} <span class="highlight_red">{LANG.not_compatible}</span>.
	{LANG.please_chmod}.</caption>
	<tr>
		<th scope="col" abbr="{LANG.listchmod}" class="nobg">{LANG.listchmod}</th>
		<th scope="col">{LANG.result}</th>
	</tr>
	<!-- BEGIN: loopdir -->
	<tr>
		<th scope="col" class="{DATAFILE.class}">{DATAFILE.dir}</th>
		<td><span class="highlight_green">{DATAFILE.check}</span></td>
	</tr>
	<!-- END: loopdir -->

</table>

<!-- BEGIN: ftpconfig -->
<form action="" method="post">
<table id="ftpconfig" cellspacing="0"
	summary="{LANG.checkftpconfig_detail}" style="width: 100%;">
	<caption>{LANG.ftpconfig_note}</caption>
	<tr>
		<th scope="col" class="nobg"></th>
		<th scope="col" abbr="{LANG.ftp}">{LANG.ftp}</th>
		<th scope="col">{LANG.note}</th>
	</tr>
	<tr>
		<th scope="col" abbr="{LANG.ftp_server}" class="spec">{LANG.ftp_server}</th>
		<td>
		<ul>
			<li style="display: inline;" class="fl"><input type="text"
				name="ftp_server" value="{FTPDATA.ftp_server}" style="width: 300px;" /></li>
			<li style="display: inline;" class="fr"><span>{LANG.ftp_port}:</span><input
				type="text" name="ftp_port" value="{FTPDATA.ftp_port}"
				style="width: 20px;" /></li>
		</ul>
		</td>
		<td><span class="highlight_green">{LANG.ftp_server_note}</span></td>
	</tr>
	<tr>
		<th scope="col" abbr="{LANG.ftp_user}" class="specalt">{LANG.ftp_user}</th>
		<td><input type="text" name="ftp_user_name"
			value="{FTPDATA.ftp_user_name}" style="width: 300px;" /></td>
		<td><span class="highlight_green">{LANG.ftp_user_note}</span></td>
	</tr>
	<tr>
		<th scope="col" abbr="{LANG.ftp_pass}" class="spec">{LANG.ftp_pass}</th>
		<td><input type="password" name="ftp_user_pass" autocomplete="off"
			value="{FTPDATA.ftp_user_pass}" /></td>
		<td><span class="highlight_green">{LANG.ftp_pass_note}</span></td>
	</tr>
	<tr>
		<th scope="col" abbr="{LANG.ftp_path}" class="spec">{LANG.ftp_path}</th>
		<td><input type="text" name="ftp_path" value="{FTPDATA.ftp_path}"
			style="width: 300px;" /></td>
		<td><span class="highlight_green">{LANG.ftp_path_note}</span></td>
	</tr>
	<tr>
		<th scope="col" abbr="{LANG.refesh}" class="nobg"><input type="hidden"
			name="modftp" value="1" /></th>
		<td><input type="submit" value="{LANG.refesh}" /></td>
		<td></td>
	</tr>
</table>
</form>
<!-- BEGIN: errorftp -->
<span class="highlight_red">
{FTPDATA.error}
</span>
<!-- END: errorftp -->
<!-- END: ftpconfig -->
<ul class="control_t fr">
	<li><span class="back_step"><a
		href="{BASE_SITEURL}index.php?{LANG_VARIABLE}={CURRENTLANG}&amp;step=3">{LANG.previous}</a></span></li>
	<!-- BEGIN: nextstep -->
	<li><span class="next_step"><a
		href="{BASE_SITEURL}index.php?{LANG_VARIABLE}={CURRENTLANG}&amp;step=5">{LANG.next_step}</a></span></li>
	<!-- END: nextstep -->
</ul>
<!-- END: step -->