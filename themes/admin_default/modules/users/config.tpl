<!-- BEGIN: main -->
<form action="{FORM_ACTION}" method="post">
	<table class="tab1">
		<caption> {LANG.access_caption} </caption>
		<thead>
			<tr align="center">
				<td>{LANG.access_admin}</td>
				<td>{LANG.access_addus}</td>
				<td>{LANG.access_waiting}</td>
				<td>{LANG.access_editus}</td>
				<td>{LANG.access_delus}</td>
				<td>{LANG.access_passus}</td>
				<td>{LANG.access_groups}</td>
			</tr>
		</thead>
		<!-- BEGIN: access -->
		<tbody {ACCESS.class}>
			<tr>
				<td><strong>{ACCESS.title}</strong></td>
				<td align="center"><input type="checkbox" {ACCESS.checked_addus} value="1" name="access_addus[{ACCESS.id}]"></td>
				<td align="center"><input type="checkbox" {ACCESS.checked_waiting} value="1" name="access_waiting[{ACCESS.id}]"></td>
				<td align="center"><input type="checkbox" {ACCESS.checked_editus} value="1" name="access_editus[{ACCESS.id}]"></td>
				<td align="center"><input type="checkbox" {ACCESS.checked_delus} value="1" name="access_delus[{ACCESS.id}]"></td>
				<td align="center"><input type="checkbox" {ACCESS.checked_passus} value="1" name="access_passus[{ACCESS.id}]"></td>
				<td align="center"><input type="checkbox" {ACCESS.checked_groups} value="1" name="access_groups[{ACCESS.id}]"></td>
			</tr>
		</tbody>
		<!-- END: access -->
	</table>
	<table class="tab1">
		<caption> {LANG.access_register} </caption>
		<colgroup>
			<col style="width: 320px;" />
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
		</tbody>
		<tbody class="second">
			<tr>
				<td>{LANG.nv_unick}</td>
				<td>
				<select name="nv_unickmin" style="text-align: right">
					<!-- BEGIN: nv_unickmin -->
					<option value="{OPTION.id}"{OPTION.select}> {OPTION.value}</option>
					<!-- END: nv_unickmin -->
				</select> ->
				<select name="nv_unickmax" style="text-align: right">
					<!-- BEGIN: nv_unickmax -->
					<option value="{OPTION.id}"{OPTION.select}> {OPTION.value}</option>
					<!-- END: nv_unickmax -->
				</select></td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td>{LANG.nv_unick_type}</td>
				<td>
				<select name="nv_unick_type">
					<!-- BEGIN: nv_unick_type -->
					<option value="{OPTION.id}"{OPTION.select}> {OPTION.value}</option>
					<!-- END: nv_unick_type -->
				</select></td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td>{LANG.nv_upass}</td>
				<td>
				<select name="nv_upassmin" style="text-align: right">
					<!-- BEGIN: nv_upassmin -->
					<option value="{OPTION.id}"{OPTION.select}> {OPTION.value}</option>
					<!-- END: nv_upassmin -->
				</select> ->
				<select name="nv_upassmax" style="text-align: right">
					<!-- BEGIN: nv_upassmax -->
					<option value="{OPTION.id}"{OPTION.select}> {OPTION.value}</option>
					<!-- END: nv_upassmax -->
				</select></td>
			</tr>
		</tbody>
		<tbody>
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
	<table name="facebook_config" class="tab1">
		<caption>{LANG.facebook_config}</caption>
		<col width="320"/>
		<tbody>
			<tr>
				<td><strong>{LANG.facebook_client_id}</strong></td>
				<td><input type="text" class="txt-half" name="facebook_client_id" value="{DATA.facebook_client_id}"/></td>
			</tr>
		<tbody>
		<tbody class="second">
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
		</colgroup>
		<tfoot>
			<tr>
				<td style="text-align: center;" colspan="7"><input type="submit" value="{LANG.save}" name="submit"></td>
			</tr>
		</tfoot>
		<!-- BEGIN: user_forum -->
		<tbody class="second">
			<tr>
				<td>{LANG.is_user_forum}</td>
				<td><input name="is_user_forum" value="1" type="checkbox"{DATA.is_user_forum} /></td>
			</tr>
		</tbody>
		<!-- END: user_forum -->
		<tbody>
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
		</tbody>
		<tbody class="second">
			<tr>
				<td>{LANG.whoviewlistuser}</td>
				<td>
				<select name="whoviewuser">
					<!-- BEGIN: whoviewlistuser -->
					<option value="{WHOVIEW.id}"{WHOVIEW.select}> {WHOVIEW.value}</option>
					<!-- END: whoviewlistuser -->
				</select></td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td>{LANG.allow_login}</td>
				<td><input name="allowuserlogin" value="1" type="checkbox"{DATA.allowuserlogin} /></td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td>{LANG.allow_public}</td>
				<td><input name="allowuserpublic" value="1" type="checkbox"{DATA.allowuserpublic} /></td>
			</tr>
		</tbody>
		<tbody >
			<tr>
				<td>{LANG.allow_question}</td>
				<td><input name="allowquestion" value="1" type="checkbox"{DATA.allowquestion} /></td>
			</tr>
		</tbody>
		<tbody class="second" >
			<tr>
				<td>{LANG.allow_change_login}</td>
				<td><input name="allowloginchange" value="1" type="checkbox"{DATA.allowloginchange} /></td>
			</tr>
		</tbody>
		<tbody >
			<tr>
				<td>{LANG.allow_change_email}</td>
				<td><input name="allowmailchange" value="1" type="checkbox"{DATA.allowmailchange} /></td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td>{LANG.allow_openid}</td>
				<td><input name="openid_mode" value="1" type="checkbox"{DATA.openid_mode} /></td>
			</tr>
		</tbody>
		<tbody >
			<tr>
				<td>{LANG.openid_servers}</td>
				<td>
				<!-- BEGIN: openid_servers -->
				<input name="openid_servers[]" value="{OPENID.name}" type="checkbox"{OPENID.checked} /> {OPENID.name}
				<br />
				<!-- END: openid_servers -->
				</td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td>{LANG.deny_email}</td>
				<td><textarea name="deny_email" rows="7" cols="70">{DATA.deny_email}</textarea></td>
			</tr>
		</tbody>
		<tbody >
			<tr>
				<td>{LANG.deny_name}</td>
				<td><textarea name="deny_name" rows="7" cols="70">{DATA.deny_name}</textarea></td>
			</tr>
		</tbody>
	</table>
</form>
<!-- END: main -->