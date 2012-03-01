<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="quote" style="width:98%">
	<blockquote class="error"><span>{ERROR}</span></blockquote>
</div>
<div class="clear"></div>
<!-- END: error -->
<form method="post" action="{FORM_ACTION}">
<input name="save" type="hidden" value="1" />
<table class="tab1">
	<col width="150px" />
	<tbody>
		<tr>
			<td><strong>{LANG.part_row_title}</strong></td>
			<td><input class="txt-half" type="text" name="full_name" value="{DATA.full_name}"/></td>
		</tr>
	</tbody>
	<tbody class="second">
		<tr>
			<td><strong>{GLANG.phonenumber}</strong></td>
			<td><input class="txt-half" type="text" name="phone" value="{DATA.phone}"/></td>
		</tr>
	</tbody>
	<tbody>
		<tr>
			<td><strong>Fax</strong></td>
			<td><input class="txt-half" type="text" name="fax" value="{DATA.fax}"/></td>
		</tr>
	</tbody>
	<tbody class="second">
		<tr>
			<td><strong>{GLANG.email}</strong></td>
			<td><input class="txt-half" type="text" name="email" value="{DATA.email}"/></td>
		</tr>
	</tbody>
	<tbody>
		<tr>
			<td colspan="2">
				<p><strong>{LANG.note_row_title}</strong></p>
				{DATA.note}
			</td>
		</tr>
	</tbody>
</table>
<table class="tab1">
	<caption>{LANG.list_admin_row_title}</caption>
	<thead>
		<tr>
			<td>{LANG.username_admin_row_title}</td>
			<td>{LANG.name_admin_row_title}</td>
			<td>{GLANG.email}</td>
			<td>{LANG.admin_view_title}</td>
			<td>{LANG.admin_reply_title}</td>
			<td>{LANG.admin_send2mail_title}</td>
		</tr>
	</thead>
	<!-- BEGIN: admin -->
	<tbody{ADMIN.class}>
		<tr>
			<td>{ADMIN.login}</td>
			<td>{ADMIN.fullname}</td>
			<td>{ADMIN.email}</td>
			<td class="center"><input type="checkbox" name="view_level[]" value="{ADMIN.admid}"{ADMIN.view_level}{ADMIN.disabled} /></td>
			<td class="center"><input type="checkbox" name="reply_level[]" value="{ADMIN.admid}"{ADMIN.reply_level}{ADMIN.disabled} /></td>
			<td class="center"><input type="checkbox" name="obt_level[]" value="{ADMIN.admid}"{ADMIN.obt_level} /></td>
		</tr>
	</tbody>
	<!-- END: admin -->
</table>
<table class="tab1">
	<tr><td class="center"><input name="submit1" type="submit" value="{GLANG.submit}" /></td></tr>
</table>
</form>
<!-- END: main -->
