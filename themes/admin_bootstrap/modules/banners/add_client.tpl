<!-- BEGIN: main -->
<div class="alert alert-info">{CONTENTS.info}</div>
<form method="post" id="form_add_client" style="FLOAT:left;width:100%;margin-bottom:20px" action="{CONTENTS.action}">
	<input type="hidden" value="1" name="save" id="save" />
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<col class="w200">
			<col class="w20">
			<col/>
			<tfoot>
				<tr>
					<td colspan="3"><input type="submit" value="{CONTENTS.submit}" class="btn btn-primary" /></td>
				</tr>
			</tfoot>
			<tbody>
				<tr>
					<td>{CONTENTS.login.0}:</td>
					<td><sup class="required">&lowast;</sup></td>
					<td><input class="w300 form-control" name="{CONTENTS.login.1}" id="{CONTENTS.login.1}" type="text" value="{CONTENTS.login.2}" maxlength="{CONTENTS.login.3}" /></td>
				</tr>
				<tr>
					<td>{CONTENTS.pass.0}:</td>
					<td><sup class="required">&lowast;</sup></td>
					<td><input class="w300 form-control" name="{CONTENTS.pass.1}" id="{CONTENTS.pass.1}" type="password" value="{CONTENTS.pass.2}" maxlength="{CONTENTS.pass.3}" /></td>
				</tr>
				<tr>
					<td>{CONTENTS.re_pass.0}:</td>
					<td><sup class="required">&lowast;</sup></td>
					<td><input class="w300 form-control" name="{CONTENTS.re_pass.1}" id="{CONTENTS.re_pass.1}" type="password" value="{CONTENTS.re_pass.2}" maxlength="{CONTENTS.re_pass.3}" /></td>
				</tr>
				<tr>
					<td>{CONTENTS.email.0}:</td>
					<td><sup class="required">&lowast;</sup></td>
					<td><input class="w300 form-control" name="{CONTENTS.email.1}" id="{CONTENTS.email.1}" type="text" value="{CONTENTS.email.2}" maxlength="{CONTENTS.email.3}" /></td>
				</tr>
				<tr>
					<td>{CONTENTS.full_name.0}:</td>
					<td><sup class="required">&lowast;</sup></td>
					<td><input class="w300 form-control" name="{CONTENTS.full_name.1}" id="{CONTENTS.full_name.1}" type="text" value="{CONTENTS.full_name.2}" maxlength="{CONTENTS.full_name.3}" /></td>
				</tr>
				<tr>
					<td>{CONTENTS.website.0}:</td>
					<td>&nbsp;</td>
					<td><input class="w300 form-control" name="{CONTENTS.website.1}" id="{CONTENTS.website.1}" type="text" value="{CONTENTS.website.2}" maxlength="{CONTENTS.website.3}" /></td>
				</tr>
				<tr>
					<td>{CONTENTS.location.0}:</td>
					<td>&nbsp;</td>
					<td><input class="w300 form-control" name="{CONTENTS.location.1}" id="{CONTENTS.location.1}" type="text" value="{CONTENTS.location.2}" maxlength="{CONTENTS.location.3}" /></td>
				</tr>
				<tr>
					<td>{CONTENTS.yim.0}:</td>
					<td>&nbsp;</td>
					<td><input class="w300 form-control" name="{CONTENTS.yim.1}" id="{CONTENTS.yim.1}" type="text" value="{CONTENTS.yim.2}" maxlength="{CONTENTS.yim.3}" /></td>
				</tr>
				<tr>
					<td>{CONTENTS.phone.0}:</td>
					<td>&nbsp;</td>
					<td><input class="w300 form-control" name="{CONTENTS.phone.1}" id="{CONTENTS.phone.1}" type="text" value="{CONTENTS.phone.2}" maxlength="{CONTENTS.phone.3}" /></td>
				</tr>
				<tr>
					<td>{CONTENTS.fax.0}:</td>
					<td>&nbsp;</td>
					<td><input class="w300 form-control" name="{CONTENTS.fax.1}" id="{CONTENTS.fax.1}" type="text" value="{CONTENTS.fax.2}" maxlength="{CONTENTS.fax.3}" /></td>
				</tr>
				<tr>
					<td>{CONTENTS.mobile.0}:</td>
					<td>&nbsp;</td>
					<td><input class="w300 form-control" name="{CONTENTS.mobile.1}" id="{CONTENTS.mobile.1}" type="text" value="{CONTENTS.mobile.2}" maxlength="{CONTENTS.mobile.3}" /></td>
				</tr>
				<tr>
					<td>{CONTENTS.uploadtype.0}:</td>
					<td>&nbsp;</td>
					<td><label><input name="{CONTENTS.uploadtype.1}[]" type="checkbox" value="images"/>images</label>&nbsp;<label><input name="{CONTENTS.uploadtype.1}[]" type="checkbox" value="flash"/>flash</label></td>
				</tr>
	
			</tbody>
		</table>
	</div>
</form>
<!-- END: main -->