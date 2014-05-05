<!-- BEGIN: main -->
<form action="{FORM_ACTION}" method="post">
	<table class="tab1">
		<caption> {LANG.access_caption} </caption>
		<thead>
			<tr class="center">
				<td>{LANG.access_admin}</td>
				<td>{LANG.access_addus}</td>
				<td>{LANG.access_waiting}</td>
				<td>{LANG.access_editus}</td>
				<td>{LANG.access_delus}</td>
				<td>{LANG.access_passus}</td>
				<td>{LANG.access_groups}</td>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: access -->
			<tr>
				<td><strong>{ACCESS.title}</strong></td>
				<td class="center"><input type="checkbox" {ACCESS.checked_addus} value="1" name="access_addus[{ACCESS.id}]"></td>
				<td class="center"><input type="checkbox" {ACCESS.checked_waiting} value="1" name="access_waiting[{ACCESS.id}]"></td>
				<td class="center"><input type="checkbox" {ACCESS.checked_editus} value="1" name="access_editus[{ACCESS.id}]"></td>
				<td class="center"><input type="checkbox" {ACCESS.checked_delus} value="1" name="access_delus[{ACCESS.id}]"></td>
				<td class="center"><input type="checkbox" {ACCESS.checked_passus} value="1" name="access_passus[{ACCESS.id}]"></td>
				<td class="center"><input type="checkbox" {ACCESS.checked_groups} value="1" name="access_groups[{ACCESS.id}]"></td>
			</tr>
			<!-- END: access -->
		</tbody>
	</table>
	<table class="tab1">
		<caption> {LANG.access_register} </caption>
		<colgroup>
			<col style="width: 320px;" />
			<col />
		</colgroup>
		<tbody>
			<tr>
				<td>{LANG.type_reg}</td>
				<td>
				<select name="allowuserreg">
					<!-- BEGIN: registertype -->
					<option value="{REGISTERTYPE.id}"{REGISTERTYPE.select}> {REGISTERTYPE.value}</option>
					<!-- END: registertype -->
				</select></td>
			</tr>
			<tr>
				<td>{LANG.nv_unick}</td>
				<td>
				<select name="nv_unickmin" class="right">
					<!-- BEGIN: nv_unickmin -->
					<option value="{OPTION.id}"{OPTION.select}> {OPTION.value}</option>
					<!-- END: nv_unickmin -->
				</select> ->
				<select name="nv_unickmax" class="right">
					<!-- BEGIN: nv_unickmax -->
					<option value="{OPTION.id}"{OPTION.select}> {OPTION.value}</option>
					<!-- END: nv_unickmax -->
				</select></td>
			</tr>
			<tr>
				<td>{LANG.nv_unick_type}</td>
				<td>
				<select name="nv_unick_type">
					<!-- BEGIN: nv_unick_type -->
					<option value="{OPTION.id}"{OPTION.select}> {OPTION.value}</option>
					<!-- END: nv_unick_type -->
				</select></td>
			</tr>
			<tr>
				<td>{LANG.nv_upass}</td>
				<td>
				<select name="nv_upassmin" class="right">
					<!-- BEGIN: nv_upassmin -->
					<option value="{OPTION.id}"{OPTION.select}> {OPTION.value}</option>
					<!-- END: nv_upassmin -->
				</select> ->
				<select name="nv_upassmax" class="right">
					<!-- BEGIN: nv_upassmax -->
					<option value="{OPTION.id}"{OPTION.select}> {OPTION.value}</option>
					<!-- END: nv_upassmax -->
				</select></td>
			</tr>
			<tr>
				<td>{LANG.nv_upass_type}</td>
				<td>
				<select name="nv_upass_type">
					<!-- BEGIN: nv_upass_type -->
					<option value="{OPTION.id}"{OPTION.select}> {OPTION.value}</option>
					<!-- END: nv_upass_type -->
				</select></td>
			</tr>
		</tbody>
	</table>
	<table class="tab1">
		<caption>{LANG.facebook_config}</caption>
		<colgroup>
			<col style="width: 320px;" />
			<col />
		</colgroup>
		<tbody>
			<tr>
				<td><strong>{LANG.facebook_client_id}</strong></td>
				<td><input type="text" class="txt-half" name="facebook_client_id" value="{DATA.facebook_client_id}"/></td>
			</tr>
			<tr>
				<td><strong>{LANG.facebook_client_secret}</strong></td>
				<td><input type="text" class="txt-half" name="facebook_client_secret" value="{DATA.facebook_client_secret}"/></td>
			</tr>
		<tbody>
	</table>
	<table class="tab1">
		<caption> {LANG.access_other} </caption>
		<colgroup>
			<col style="width: 320px;" />
			<col />
		</colgroup>
		<tfoot>
			<tr>
				<td class="center" colspan="2"><input type="submit" value="{LANG.save}" name="submit"></td>
			</tr>
		</tfoot>
		<tbody>
			<tr>
				<td>{LANG.avatar_size}</td>
				<td>
					<input type="text" class="txt-half" name="avatar_width" value="{DATA.avatar_width}" style="width: 50px"/> x 
					<input type="text" class="txt-half" name="avatar_height" value="{DATA.avatar_height}" style="width: 50px"/>
				</td>
			</tr>
			<!-- BEGIN: user_forum -->
			<tr>
				<td>{LANG.is_user_forum}</td>
				<td><input name="is_user_forum" value="1" type="checkbox"{DATA.is_user_forum} /></td>
			</tr>
			<!-- END: user_forum -->
			<tr>
				<td>{LANG.dir_forum}</td>
				<td>
				<select name="dir_forum">
					<option value="">&nbsp;</option>
					<!-- BEGIN: dir_forum -->
					<option value="{DIR_FORUM.id}"{DIR_FORUM.select}> {DIR_FORUM.value}</option>
					<!-- END: dir_forum -->
				</select></td>
			</tr>
			<tr>
				<td>{LANG.whoviewlistuser}</td>
				<td>
				<select name="whoviewuser">
					<!-- BEGIN: whoviewlistuser -->
					<option value="{WHOVIEW.id}"{WHOVIEW.select}> {WHOVIEW.value}</option>
					<!-- END: whoviewlistuser -->
				</select></td>
			</tr>
			<tr>
				<td>{LANG.allow_login}</td>
				<td><input name="allowuserlogin" value="1" type="checkbox"{DATA.allowuserlogin} /></td>
			</tr>
			<tr>
				<td>{LANG.allow_public}</td>
				<td><input name="allowuserpublic" value="1" type="checkbox"{DATA.allowuserpublic} /></td>
			</tr>
			<tr>
				<td>{LANG.allow_question}</td>
				<td><input name="allowquestion" value="1" type="checkbox"{DATA.allowquestion} /></td>
			</tr>
			<tr>
				<td>{LANG.allow_change_login}</td>
				<td><input name="allowloginchange" value="1" type="checkbox"{DATA.allowloginchange} /></td>
			</tr>
			<tr>
				<td>{LANG.allow_change_email}</td>
				<td><input name="allowmailchange" value="1" type="checkbox"{DATA.allowmailchange} /></td>
			</tr>
			<tr>
				<td>{LANG.allow_openid}</td>
				<td><input name="openid_mode" value="1" type="checkbox"{DATA.openid_mode} /></td>
			</tr>
			<tr>
				<td>{LANG.openid_servers}</td>
				<td>
				<!-- BEGIN: openid_servers -->
				<input name="openid_servers[]" value="{OPENID.name}" type="checkbox"{OPENID.checked} /> {OPENID.name}
				<br />
				<!-- END: openid_servers -->
				</td>
			</tr>
			<tr>
				<td>{LANG.deny_email}</td>
				<td><textarea name="deny_email" rows="7" cols="70">{DATA.deny_email}</textarea></td>
			</tr>
			<tr>
				<td>{LANG.deny_name}</td>
				<td><textarea name="deny_name" rows="7" cols="70">{DATA.deny_name}</textarea></td>
			</tr>
			<tr>
				<td>{LANG.password_simple}</td>
				<td><textarea name="password_simple" rows="7" cols="70">{DATA.password_simple}</textarea></td>
			</tr>
		</tbody>
	</table>
</form>
<!-- END: main -->