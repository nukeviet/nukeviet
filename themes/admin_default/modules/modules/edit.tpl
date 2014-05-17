<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-danger">{DATA.error}</div>
<!-- END: error -->
<form method="post" action="{DATA.action}">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<tfoot>
				<tr>
					<td colspan="2" class="text-center"><input name="save" id="save" type="hidden" value="1" /><input name="go_add" type="submit" value="{DATA.submit}" class="btn btn-primary" /></td>
				</tr>
			</tfoot>
			<tbody>
				<tr>
					<td>{LANG.module_name}:</td>
					<td><input class="w300 form-control" name="mod_name" id="mod_name" type="text" value="{DATA.mod_name}" maxlength="55" readonly="readonly"/></td>
				</tr>
				<tr>
					<td>{LANG.custom_title}:</td>
					<td><input class="w300 form-control" name="custom_title" id="custom_title" type="text" value="{DATA.custom_title}" maxlength="100" /></td>
				</tr>
				<tr>
					<td>{LANG.admin_title}:</td>
					<td><input class="w300 form-control" name="admin_title" id="admin_title" type="text" value="{DATA.admin_title}" maxlength="100" /></td>
				</tr>
				<tr>
					<td>{LANG.theme}:</td>
					<td>
					<select name="theme" id="theme" class="form-control w200">
						<option value="">{DATA.theme.1}</option>
						<!-- BEGIN: theme -->
						<option value="{THEME.key}"{THEME.selected}>{THEME.key}</option>
						<!-- END: theme -->
					</select></td>
				</tr>
				<!-- BEGIN: mobile -->
				<tr>
					<td>{DATA.mobile.0}:</td>
					<td>
					<select name="mobile" id="mobile" class="form-control w200">
						<option value="">{DATA.mobile.1}</option>
						<!-- BEGIN: loop -->
						<option value="{MOBILE.key}"{MOBILE.selected}>{MOBILE.key}</option>
						<!-- END: loop -->
					</select></td>
				</tr>
				<!-- END: mobile -->
				<tr>
					<td>{LANG.description}:</td>
					<td><input class="w300 form-control" name="description" id="description" type="text" value="{DATA.description}" maxlength="255" /></td>
				</tr>
				<tr>
					<td>{LANG.keywords}:</td>
					<td><input class="w300 form-control" name="keywords" id="keywords" type="text" value="{DATA.keywords}" maxlength="255" /> {LANG.keywords_info}</td>
				</tr>
				<tr>
					<td>{GLANG.activate}:</td>
					<td><input name="act" id="act" type="checkbox" value="1"{ACTIVE} /></td>
				</tr>
				<!-- BEGIN: rss -->
				<tr>
					<td>{DATA.rss.0}:</td>
					<td><input name="rss" id="rss" type="checkbox" value="1"{RSS} /></td>
				</tr>
				<!-- END: rss -->
				<!-- BEGIN: groups_view -->
				<tr>
					<td>{DATA.groups_view.0}:</td>
					<td>
					<!-- BEGIN: loop -->
					<p><input name="groups_view[]" type="checkbox" value="{GROUPS_VIEW.key}"{GROUPS_VIEW.checked}/> {GROUPS_VIEW.title}
					</p>
					<!-- END: loop -->
					</td>
				</tr>
				<!-- END: groups_view -->
			</tbody>
		</table>
	</div>
</form>
<!-- END: main -->