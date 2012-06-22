<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="quote" style="width:98%">
	<blockquote><span>{CONTENT.error}</span></blockquote>
</div>
<div class="clear"></div>
<!-- END: error -->
<form method="post" action="{CONTENT.action}">
<table class="tab1 fixtab">
	<col valign="top" width="150px" />
	<tr>
		<td>{CONTENT.custom_title.0}:</td>
		<td><input name="custom_title" id="custom_title" type="text" value="{CONTENT.custom_title.1}" style="width:300px" maxlength="{CONTENT.custom_title.2}" /></td>
	</tr>
</table>
<table class="tab1 fixtab">
	<col valign="top" width="150px" />
	<tr>
		<td>{CONTENT.admin_title.0}:</td>
		<td><input name="admin_title" id="admin_title" type="text" value="{CONTENT.admin_title.1}" style="width:300px" maxlength="{CONTENT.admin_title.2}" /></td>
	</tr>
</table>
<table class="tab1 fixtab">
	<col valign="top" width="150px" />
	<tr>
		<td>{CONTENT.theme.0}:</td>
		<td>
			<select name="theme" id="theme">
				<option value="">{CONTENT.theme.1}</option>
				<!-- BEGIN: theme -->
				<option value="{THEME.key}"{THEME.selected}>{THEME.key}</option>
				<!-- END: theme -->
			</select>
		</td>
		<td></td>
	</tr>
</table>
<!-- BEGIN: mobile -->
<table class="tab1 fixtab">
	<col valign="top" width="150px" />
	<tr>
		<td>{CONTENT.mobile.0}:</td>
		<td>
			<select name="mobile" id="mobile">
				<option value="">{CONTENT.mobile.1}</option>
				<!-- BEGIN: loop -->
				<option value="{MOBILE.key}"{MOBILE.selected}>{MOBILE.key}</option>
				<!-- END: loop -->
			</select>
		</td>
		<td></td>
	</tr>
</table>
<!-- END: mobile -->
<table class="tab1 fixtab">
	<col valign="top" width="150px" />
	<col valign="top" width="310px" />
	<tr>
		<td>{CONTENT.description.0}:</td>
		<td><input name="description" id="description" type="text" value="{CONTENT.description.1}" style="width:300px" maxlength="{CONTENT.description.2}" /></td>
		<td></td>
	</tr>
</table>
<table class="tab1 fixtab">
	<col valign="top" width="150px" />
	<col valign="top" width="310px" />
	<tr>
		<td>{CONTENT.keywords.0}:</td>
		<td><input name="keywords" id="keywords" type="text" value="{CONTENT.keywords.1}" style="width:300px" maxlength="{CONTENT.keywords.2}" /></td>
		<td>{CONTENT.keywords.3}</td>
	</tr>
</table>
<!-- BEGIN: who_view -->
<table class="tab1 fixtab">
	<col valign="top" width="150px" />
	<tr>
		<td>{CONTENT.who_view.0}:</td>
		<td>
			<select name="who_view" id="who_view" onchange="nv_sh('who_view','groups_list')">
				<!-- BEGIN: loop -->
				<option value="{WHO_VIEW.key}"{WHO_VIEW.selected}>{WHO_VIEW.title}</option>
				<!-- END: loop -->
			</select>
		</td>
		<td></td>
	</tr>
</table>
<div id="groups_list" style="{DISPLAY}">
	<table class="tab1 fixtab">
		<col valign="top" width="150px" />
		<tr>
			<td>{CONTENT.groups_view.0}:</td>
			<td>
				<!-- BEGIN: groups_view -->
				<p><input name="groups_view[]" type="checkbox" value="{GROUPS_VIEW.key}"{GROUPS_VIEW.checked}/> {GROUPS_VIEW.title}</p>
				<!-- END: groups_view -->
			</td>
		</tr>
	</table>
</div>
<!-- END: who_view -->
<table class="tab1 fixtab">
	<col valign="top" width="150px" />
	<tr>
		<td>{CONTENT.act.0}:</td>
		<td><input name="act" id="act" type="checkbox" value="1"{ACTIVE} /></td>
	</tr>
</table>
<!-- BEGIN: rss -->
<table class="tab1 fixtab">
	<col valign="top" width="150px" />
	<tr>
		<td>{CONTENT.rss.0}:</td>
		<td><input name="rss" id="rss" type="checkbox" value="1"{RSS} /></td>
	</tr>
</table>
<!-- END: rss -->
<table class="tab1">
	<col valign="top" width="150px" />
	<tr>
		<td><input name="save" id="save" type="hidden" value="1" /></td>
		<td><input name="go_add" type="submit" value="{CONTENT.submit}" /></td>
	</tr>
</table>
</form>
<!-- END: main -->