<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<form method="post" action="{FORM_ACTION}">
	<input name="save" type="hidden" value="1" />
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<col class="w150"/>
			<col />
			<tbody>
				<tr>
					<td><strong>{LANG.part_row_title}</strong></td>
					<td><input class="w300 form-control pull-left" type="text" value="{DATA.full_name}" name="full_name" id="idfull_name" maxlength="255" />&nbsp;<span class="text-middle"></td>
				</tr>
				<tr>
                    <td><strong>{LANG.alias}</strong></td>
                    <td><input class="w300 form-control pull-left" type="text" name="alias" value="{DATA.alias}" id="idalias" maxlength="255" />&nbsp;<em class="fa fa-refresh fa-lg fa-pointer" onclick="get_alias('{ID}');">&nbsp;</em></td>
                </tr>
				<tr>
					<td><strong>{GLANG.phonenumber}</strong></td>
					<td><input class="w300 form-control" type="text" name="phone" value="{DATA.phone}"/></td>
				</tr>
				<tr>
					<td><strong>Fax</strong></td>
					<td><input class="w300 form-control" type="text" name="fax" value="{DATA.fax}"/></td>
				</tr>
				<tr>
					<td><strong>{GLANG.email}</strong></td>
					<td><input class="w300 form-control" type="email" oninvalid="setCustomValidity( nv_email )" oninput="setCustomValidity('')" name="email" value="{DATA.email}"/></td>
				</tr>
                <tr>
					<td><strong>{LANG.yahoo_row_title}</strong></td>
					<td><input class="w300 form-control" type="yahoo" name="yahoo" value="{DATA.yahoo}"/></td>
				</tr>
                <tr>
					<td><strong>{LANG.skype_row_title}</strong></td>
					<td><input class="w300 form-control" type="skype" name="skype" value="{DATA.skype}"/></td>
				</tr>
				<tr>
					<td colspan="2">
					<p>
						<strong>{LANG.note_row_title}</strong>
					</p> {DATA.note} </td>
				</tr>
			</tbody>
		</table>
		<table class="table table-striped table-bordered table-hover">
			<caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.list_admin_row_title}</caption>
			<thead>
				<tr>				    
					<th>{LANG.username_admin_row_title}</th>
					<th>{LANG.name_admin_row_title}</th>
					<th>{GLANG.email}</th>
					<th>{LANG.admin_view_title}</th>
					<th>{LANG.admin_reply_title}</th>
					<th>{LANG.admin_send2mail_title}</th>
				</tr>
			</thead>
			<tbody>
				<!-- BEGIN: admin -->
				<tr>
					<td>{ADMIN.login}</td>
					<td>{ADMIN.last_name}&nbsp;{ADMIN.first_name}</td>
					<td>{ADMIN.email}</td>
					<td class="text-center"><input type="checkbox" name="view_level[]" value="{ADMIN.admid}"{ADMIN.view_level}{ADMIN.disabled} /></td>
					<td class="text-center"><input type="checkbox" name="reply_level[]" value="{ADMIN.admid}"{ADMIN.reply_level}{ADMIN.disabled} /></td>
					<td class="text-center"><input type="checkbox" name="obt_level[]" value="{ADMIN.admid}"{ADMIN.obt_level} /></td>
				</tr>
				<!-- END: admin -->
			</tbody>
		</table>
		<table class="table table-striped table-bordered table-hover">
			<tr>
				<td class="text-center"><input name="submit1" type="submit" value="{GLANG.submit}" class="btn btn-primary" /></td>
			</tr>
		</table>
	</div>
</form>
<!-- BEGIN: get_alias -->
<script type="text/javascript">
    $(document).ready(function() {
        $('#idfull_name').change(function() {
            get_alias('{ID}');
        });
    });
</script>
<!-- END: get_alias -->
<!-- END: main -->