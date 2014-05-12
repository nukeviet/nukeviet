<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<form action="{FORM_ACTION}" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<tbody>
				<tr>
					<td> {LANG.category_cat_name} </td>
					<td><input class="w300 form-control" type="text" value="{DATA.title}" name="title" id="title" maxlength="100" /></td>
				</tr>
				<tr>
					<td> {LANG.alias} </td>
					<td><input class="w300 form-control" type="text" value="{DATA.alias}" name="alias" id="alias" maxlength="100" /></td>
				</tr>
				<tr>
					<td> {LANG.description} </td>
					<td><input class="w300 form-control" type="text" value="{DATA.description}" name="description" maxlength="255" /></td>
				</tr>
				<tr>
					<td> {LANG.category_cat_parent} </td>
					<td>
					<select name="parentid" class="form-control w200">
						<!-- BEGIN: parentid -->
						<option value="{LISTCATS.id}"{LISTCATS.selected}>{LISTCATS.name}</option>
						<!-- END: parentid -->
					</select></td>
				</tr>
				<tr>
					<td style="vertical-align:top"> {LANG.groups_view} </td>
					<td>
						<!-- BEGIN: groups_view -->
						<input name="groups_view[]" value="{GROUPS_VIEW.key}" type="checkbox"{GROUPS_VIEW.checked} /> {GROUPS_VIEW.title}
						<br />
						<!-- END: groups_view -->
					</td>
				</tr>
				<tr>
					<td style="vertical-align:top"> {LANG.groups_download} </td>
					<td>
						<!-- BEGIN: groups_download -->
						<input name="groups_download[]" value="{GROUPS_DOWNLOAD.key}" type="checkbox"{GROUPS_DOWNLOAD.checked} /> {GROUPS_DOWNLOAD.title}
						<br />
						<!-- END: groups_download -->
					</td>
				</tr>
				<tr>
					<td colspan="2"><input type="submit" name="submit" value="{LANG.cat_save}" class="btn btn-primary" /></td>
				</tr>
			</tbody>
		</table>
	</div>
</form>
<!-- END: main -->