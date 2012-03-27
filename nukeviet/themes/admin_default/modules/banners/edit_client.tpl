<!-- BEGIN: main -->
<div class="quote" style="width:98%">
<blockquote{CLASS}><span>{CONTENTS.info}</span></blockquote>
</div>
<div class="clear"></div>
<form method="post" action="{CONTENTS.action}">
	<input type="hidden" value="1" name="save" id="save" />
	<table summary="{CONTENTS.info}" class="tab1">
		<col style="width:150px;white-space:nowrap" />
		<col valign="top" width="10px" />
		<tbody class="second">
			<tr>
				<td>{CONTENTS.login.0}:</td>
				<td><sup class="required">&lowast;</sup></td>
				<td><input name="{CONTENTS.login.1}" id="{CONTENTS.login.1}" type="text" value="{CONTENTS.login.2}" style="width:300px" maxlength="{CONTENTS.login.3}" /></td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td>{CONTENTS.email.0}:</td>
				<td><sup class="required">&lowast;</sup></td>
				<td><input name="{CONTENTS.email.1}" id="{CONTENTS.email.1}" type="text" value="{CONTENTS.email.2}" style="width:300px" maxlength="{CONTENTS.email.3}" /></td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td>{CONTENTS.full_name.0}:</td>
				<td><sup class="required">&lowast;</sup></td>
				<td><input name="{CONTENTS.full_name.1}" id="{CONTENTS.full_name.1}" type="text" value="{CONTENTS.full_name.2}" style="width:300px" maxlength="{CONTENTS.full_name.3}" /></td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td>{CONTENTS.website.0}:</td>
				<td></td>
				<td><input name="{CONTENTS.website.1}" id="{CONTENTS.website.1}" type="text" value="{CONTENTS.website.2}" style="width:300px" maxlength="{CONTENTS.website.3}" /></td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td>{CONTENTS.location.0}:</td>
				<td></td>
				<td><input name="{CONTENTS.location.1}" id="{CONTENTS.location.1}" type="text" value="{CONTENTS.location.2}" style="width:300px" maxlength="{CONTENTS.location.3}" /></td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td>{CONTENTS.yim.0}:</td>
				<td></td>
				<td><input name="{CONTENTS.yim.1}" id="{CONTENTS.yim.1}" type="text" value="{CONTENTS.yim.2}" style="width:300px" maxlength="{CONTENTS.yim.3}" /></td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td>{CONTENTS.phone.0}:</td>
				<td></td>
				<td><input name="{CONTENTS.phone.1}" id="{CONTENTS.phone.1}" type="text" value="{CONTENTS.phone.2}" style="width:300px" maxlength="{CONTENTS.phone.3}" /></td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td>{CONTENTS.fax.0}:</td>
				<td></td>
				<td><input name="{CONTENTS.fax.1}" id="{CONTENTS.fax.1}" type="text" value="{CONTENTS.fax.2}" style="width:300px" maxlength="{CONTENTS.fax.3}" /></td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td>{CONTENTS.mobile.0}:</td>
				<td></td>
				<td><input name="{CONTENTS.mobile.1}" id="{CONTENTS.mobile.1}" type="text" value="{CONTENTS.mobile.2}" style="width:300px" maxlength="{CONTENTS.mobile.3}" /></td>
			</tr>
		</tbody>
		<tr>
			<td>{CONTENTS.uploadtype.0}:</td>
				<td></td>
				<td><label><input name="{CONTENTS.uploadtype.1}[]" id="{CONTENTS.uploadtype.1}" type="checkbox" value="images" {CONTENTS.uploadtype.2}/>images</label>&nbsp;<label><input name="{CONTENTS.uploadtype.1}[]" id="{CONTENTS.uploadtype.1}" type="checkbox" value="flash" {CONTENTS.uploadtype.3}/>flash</label></td>
			</tr>
	</table>
	<table summary="{CONTENTS.info}" class="tab1">
		<col style="width:150px;white-space:nowrap" />
		<col valign="top" width="10px" />
		<tbody>
			<tr>
				<td>{CONTENTS.pass.0}:</td>
				<td></td>
				<td><input name="{CONTENTS.pass.1}" id="{CONTENTS.pass.1}" type="password" autocomplete="off" value="{CONTENTS.pass.2}" style="width:300px" maxlength="{CONTENTS.pass.3}" /></td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td>{CONTENTS.re_pass.0}:</td>
				<td></td>
				<td><input name="{CONTENTS.re_pass.1}" id="{CONTENTS.re_pass.1}" type="password" autocomplete="off" value="{CONTENTS.re_pass.2}" style="width:300px" maxlength="{CONTENTS.re_pass.3}" /></td>
			</tr>
		</tbody>
	</table>
	<table summary="{CONTENTS.info}" class="tab1">
		<col style="width:150px;white-space:nowrap" />
		<col valign="top" width="10px" />
		<tbody>
			<tr>
				<td></td>
				<td></td>
				<td><input type="submit" value="{CONTENTS.submit}" /></td>
			</tr>
		</tbody>
	</table>
</form>
<!-- END: main -->