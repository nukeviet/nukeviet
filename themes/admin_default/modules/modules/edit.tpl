<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="quote" style="width:98%">
	<blockquote><span>{CONTENT.error}</span></blockquote>
</div>
<!-- END: error -->
<form method="post" action="{CONTENT.action}">
	<table class="tab1 fixtab">
		<tfoot>
			<tr>
				<td colspan="2" class="center"><input name="save" id="save" type="hidden" value="1" /><input name="go_add" type="submit" value="{CONTENT.submit}" /></td>
			</tr>
		</tfoot>
		<tbody>
			<tr>
				<td>{CONTENT.custom_title.0}:</td>
				<td><input class="w300" name="custom_title" id="custom_title" type="text" value="{CONTENT.custom_title.1}" maxlength="{CONTENT.custom_title.2}" /></td>
			</tr>
			<tr>
				<td>{CONTENT.admin_title.0}:</td>
				<td><input class="w300" name="admin_title" id="admin_title" type="text" value="{CONTENT.admin_title.1}" maxlength="{CONTENT.admin_title.2}" /></td>
			</tr>
			<tr>
				<td>{CONTENT.theme.0}:</td>
				<td>
				<select name="theme" id="theme">
					<option value="">{CONTENT.theme.1}</option>
					<!-- BEGIN: theme -->
					<option value="{THEME.key}"{THEME.selected}>{THEME.key}</option>
					<!-- END: theme -->
				</select></td>
			</tr>
			<!-- BEGIN: mobile -->
			<tr>
				<td>{CONTENT.mobile.0}:</td>
				<td>
				<select name="mobile" id="mobile">
					<option value="">{CONTENT.mobile.1}</option>
					<!-- BEGIN: loop -->
					<option value="{MOBILE.key}"{MOBILE.selected}>{MOBILE.key}</option>
					<!-- END: loop -->
				</select></td>
			</tr>
			<!-- END: mobile -->
			<tr>
				<td>{CONTENT.description.0}:</td>
				<td><input class="w300" name="description" id="description" type="text" value="{CONTENT.description.1}" maxlength="{CONTENT.description.2}" /></td>
			</tr>
			<tr>
				<td>{CONTENT.keywords.0}:</td>
				<td><input class="w300" name="keywords" id="keywords" type="text" value="{CONTENT.keywords.1}" maxlength="{CONTENT.keywords.2}" /> {CONTENT.keywords.3}</td>
			</tr>
			<!-- BEGIN: who_view -->
			<tr>
				<td>{CONTENT.who_view.0}:</td>
				<td>
				<select name="who_view" id="who_view" onchange="nv_sh('who_view','groups_list')">
					<!-- BEGIN: loop -->
					<option value="{WHO_VIEW.key}"{WHO_VIEW.selected}>{WHO_VIEW.title}</option>
					<!-- END: loop -->
				</select></td>
			</tr>
			<tr>
				<td>{CONTENT.groups_view.0}:</td>
				<td>
				<!-- BEGIN: groups_view -->
				<p><input name="groups_view[]" type="checkbox" value="{GROUPS_VIEW.key}"{GROUPS_VIEW.checked}/> {GROUPS_VIEW.title}
				</p>
				<!-- END: groups_view -->
				</td>
			</tr>
			<!-- END: who_view -->
			<tr>
				<td>{CONTENT.act.0}:</td>
				<td><input name="act" id="act" type="checkbox" value="1"{ACTIVE} /></td>
			</tr>
			<!-- BEGIN: rss -->
			<tr>
				<td>{CONTENT.rss.0}:</td>
				<td><input name="rss" id="rss" type="checkbox" value="1"{RSS} /></td>
			</tr>
			<!-- END: rss -->
		</tbody>
	</table>
</form>
<!-- END: main -->