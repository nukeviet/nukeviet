<!-- BEGIN: main -->
<div id="users">
	<!-- BEGIN: is_forum -->
	<div class="alert alert-warning">{LANG.modforum}</div>
	<!-- END: is_forum -->
	<div class="well">
		<form action="{FORM_ACTION}" method="get">
			<input name="{NV_NAME_VARIABLE}" type="hidden" value="{MODULE_NAME}" />
			<div class="row">
				<div class="col-xs-12 col-md-6">
					<div class="form-group">
						<input class="form-control" type="text" name="value" value="{SEARCH_VALUE}" id="f_value" placeholder="{LANG.search_key}" />
					</div>
				</div>
				<div class="col-xs-12 col-md-6">
					<div class="form-group">
						<select class="form-control" name="method" id="f_method">
							<option value="">---{LANG.search_type}---</option>
							<!-- BEGIN: method -->
							<option value="{METHODS.key}"{METHODS.selected}>{METHODS.value}</option>
							<!-- END: method -->
						</select>
					</div>
				</div>
				<div class="col-xs-12 col-md-6">
					<div class="form-group">
						<select class="form-control" name="usactive">
							<option value="-1">---{LANG.usactive}---</option>
							<!-- BEGIN: usactive -->
							<option value="{USACTIVE.key}"{USACTIVE.selected}>{USACTIVE.value}</option>
							<!-- END: usactive -->
						</select>
					</div>
				</div>
				<div class="col-xs-12 col-md-6">
					<div class="form-group">
						<input class="btn btn-primary" name="search" type="submit" value="{LANG.submit}" />
					</div>
				</div>
			</div>
			<label><em>{LANG.search_note}</em></label>
		</form>
	</div>
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<caption><em class="fa fa-file-text-o">&nbsp;</em>{TABLE_CAPTION}</caption>
			<thead>
				<tr>
					<!-- BEGIN: head_td -->
					<th><a href="{HEAD_TD.href}">{HEAD_TD.title}</a></th>
					<!-- END: head_td -->
					<th class="text-center">{LANG.memberlist_active}</th>
					<th class="text-center">{LANG.funcs}</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="8">
					<!-- BEGIN: exportfile -->
					<input type="button" class="btn btn-primary" value="{LANG.export}" name="data_export"/>
					<!-- END: exportfile -->
					<!-- BEGIN: generate_page -->
					{GENERATE_PAGE}
					<!-- END: generate_page -->
					</td>
				</tr>
			</tfoot>
			<tbody>
				<!-- BEGIN: xusers -->
				<tr>
					<td> {CONTENT_TD.userid} </td>
					<td>
					<!-- BEGIN: is_admin -->
					<img style="vertical-align:middle;" alt="{CONTENT_TD.level}" src="{NV_BASE_SITEURL}themes/{NV_ADMIN_THEME}/images/{CONTENT_TD.img}.png" width="38" height="18" />
					<!-- END: is_admin -->
					{CONTENT_TD.username} </td>
					<td> {CONTENT_TD.full_name} </td>
					<td><a href="mailto:{CONTENT_TD.email}">{CONTENT_TD.email}</a></td>
					<td> {CONTENT_TD.regdate} </td>
					<td class="text-center"><input type="checkbox" name="active" id="change_status_{CONTENT_TD.userid}" value="{CONTENT_TD.userid}"{CONTENT_TD.checked}{CONTENT_TD.disabled} /></td>
					<td class="text-center">
					<!-- BEGIN: edit -->
					&nbsp;&nbsp; <em class="fa fa-edit fa-lg">&nbsp;</em> <a href="{EDIT_URL}">{LANG.memberlist_edit}</a>
					<!-- END: edit -->
					<!-- BEGIN: del -->
					&nbsp;&nbsp; <em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="javascript:void(0);" onclick="nv_row_del({CONTENT_TD.userid});">{LANG.delete}</a>
					<!-- END: del -->
					</td>
				</tr>
				<!-- END: xusers -->
			</tbody>
		</table>
	</div>
</div>
<script type="text/javascript">
 var export_note = '{LANG.export_note}';
 var export_complete = '{LANG.export_complete}';
</script>
<!-- END: main -->