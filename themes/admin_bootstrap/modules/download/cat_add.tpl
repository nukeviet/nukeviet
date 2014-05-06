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
					<td style="vertical-align:top"> {LANG.who_view} </td>
					<td>
					<select name="who_view" class="form-control w200">
						<!-- BEGIN: who_view -->
						<option value="{WHO_VIEW.key}"{WHO_VIEW.selected}>{WHO_VIEW.title}</option>
						<!-- END: who_view -->
					</select>
					<!-- BEGIN: group_view_empty -->
					<br />
					{LANG.groups_upload}
					<br />
					<!-- BEGIN: groups_view -->
					<input name="groups_view[]" value="{GROUPS_VIEW.key}" type="checkbox"{GROUPS_VIEW.checked} /> {GROUPS_VIEW.title}
					<br />
					<!-- END: groups_view -->
					<!-- END: group_view_empty -->
					</td>
				</tr>
				<tr>
					<td style="vertical-align:top"> {LANG.who_download} </td>
					<td>
					<select name="who_download" class="form-control w200">
						<!-- BEGIN: who_download -->
						<option value="{WHO_DOWNLOAD.key}"{WHO_DOWNLOAD.selected}>{WHO_DOWNLOAD.title}</option>
						<!-- END: who_download -->
					</select>
					<!-- BEGIN: group_download_empty -->
					<br />
					{LANG.groups_upload}
					<br />
					<!-- BEGIN: groups_download -->
					<input name="groups_download[]" value="{GROUPS_DOWNLOAD.key}" type="checkbox"{GROUPS_DOWNLOAD.checked} /> {GROUPS_DOWNLOAD.title}
					<br />
					<!-- END: groups_download -->
					<!-- END: group_download_empty -->
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