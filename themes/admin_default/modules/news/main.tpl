<!-- BEGIN: main -->
<link rel="stylesheet" href="{NV_BASE_SITEURL}js/select2/select2.min.css">
<script type="text/javascript" src="{NV_BASE_SITEURL}js/select2/select2.min.js"></script>

<div class="well">
	<form action="{NV_BASE_ADMINURL}index.php" method="get">
		<input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
		<input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />

		<div class="row">
			<div class="col-xs-12 col-md-6">
				<div class="form-group">
					<input class="form-control" type="text" value="{Q}" maxlength="64" name="q" placeholder="{LANG.search_key}" />
				</div>
			</div>
			<div class="col-xs-12 col-md-4">
				<div class="form-group">
					<select class="form-control" name="stype">
						<!-- BEGIN: search_type -->
						<option value="{SEARCH_TYPE.key}" {SEARCH_TYPE.selected} >{SEARCH_TYPE.value}</option>
						<!-- END: search_type -->
					</select>
				</div>
			</div>
			<div class="col-xs-12 col-md-6">
				<div class="form-group">
					<select class="form-control" name="catid" id="catid">
						<!-- BEGIN: cat_content -->
						<option value="{CAT_CONTENT.value}" {CAT_CONTENT.selected} >{CAT_CONTENT.title}</option>
						<!-- END: cat_content -->
					</select>
				</div>
			</div>
			<div class="col-xs-12 col-md-3">
				<div class="form-group">
					<select class="form-control" name="sstatus">
						<option value="-1"> -- {LANG.search_status} -- </option>
						<!-- BEGIN: search_status -->
						<option value="{SEARCH_STATUS.key}" {SEARCH_STATUS.selected} >{SEARCH_STATUS.value}</option>
						<!-- END: search_status -->
					</select>
				</div>
			</div>
			<div class="col-xs-12 col-md-2">
				<div class="form-group">
					<select class="form-control" name="per_page">
						<option value="">{LANG.search_per_page}</option>
						<!-- BEGIN: s_per_page -->
						<option value="{SEARCH_PER_PAGE.page}" {SEARCH_PER_PAGE.selected}>{SEARCH_PER_PAGE.page}</option>
						<!-- END: s_per_page -->
					</select>
				</div>
			</div>
			<div class="col-xs-12 col-md-3">
				<div class="form-group">
					<input class="btn btn-primary" type="submit" value="{LANG.search}" />
				</div>
			</div>
		</div>
		<input type="hidden" name="checkss" value="{CHECKSS}" />
		<label><em>{LANG.search_note}</em></label>
	</form>
</div>

<form class="navbar-form" name="block_list" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th class="text-center"><input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" /></th>
					<th class="text-center"><a href="{base_url_name}">{LANG.name}</a></th>
					<th class="text-center"><a href="{base_url_publtime}">{LANG.content_publ_date}</a></th>
					<th>{LANG.content_admin}</th>
					<th>{LANG.status}</th>
                    <th class="text-center">
					   <a href="{base_url_hitstotal}"><em title="{LANG.hitstotal}" class="fa fa-eye">&nbsp;</em></a>
                    </th>
                    <th class="text-center">
					   <a href="{base_url_hitscm}"><em title="{LANG.numcomments}" class="fa fa-comment-o">&nbsp;</em></a>
                    </th>
                    <th class="text-center">
					   <em title="{LANG.keywords}" class="fa fa-tags">&nbsp;</em>
                    </th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				<!-- BEGIN: loop -->
				<tr class="{ROW.class}">
					<td class="text-center"><input type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{ROW.id}" name="idcheck[]" /></td>
					<td class="text-left">
						<p><a target="_blank" href="{ROW.link}">{ROW.title}</a></p>
					</td>
					<td>{ROW.publtime}</td>
					<td>{ROW.username}</td>
					<td title="{ROW.status}">{ROW.status}</td>
                    <td class="text-center">{ROW.hitstotal}</td>
                    <td class="text-center">{ROW.hitscm}</td>
                    <td class="text-center">{ROW.numtags}</td>
					<td class="text-center">{ROW.feature}</td>
				</tr>
				<!-- END: loop -->
			</tbody>
			<tfoot>
				<tr class="text-left">
					<td colspan="12">
						<select class="form-control" name="action" id="action">
							<!-- BEGIN: action -->
							<option value="{ACTION.value}">{ACTION.title}</option>
							<!-- END: action -->
						</select>
						<input type="button" class="btn btn-primary" onclick="nv_main_action(this.form, '{SITEKEY}', '{LANG.msgnocheck}')" value="{LANG.action}" />
					</td>
				</tr>
			</tfoot>
		</table>
	</div>
</form>
<!-- BEGIN: generate_page -->
<div class="text-center">
	{GENERATE_PAGE}
</div>
<!-- END: generate_page -->

<script type="text/javascript">
	$(document).ready(function() {
		$("#catid").select2();
	});
</script>

<!-- END: main -->
