<!-- BEGIN: main -->
<div class="well">
	<form action="{NV_BASE_ADMINURL}index.php" method="get">
		<input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}" />
		<input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
		<input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
		<div class="row">
			<div class="col-xs-6">
				<div class="form-group">
					<input type="text" class="form-control" value="{SEARCH.keywords}" name="keywords" placeholder="{LANG.search_key}" />
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<select class="form-control" name="status">
						<option value="-1">---{LANG.status}---</option>
						<!-- BEGIN: status -->
						<option value="{STATUS.key}" {STATUS.selected}>{STATUS.value}</option>
						<!-- END: status -->
					</select>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<input type="submit" class="btn btn-primary" value="{LANG.search}" />
				</div>
			</div>
		</div>
	</form>
</div>
<form>
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<colgroup>
				<col class="w50" />
				<col />
				<col class="w250" />
				<col class="w100" />
				<col />
				<col span="2" class="w150" />
				<col class="w100" />
			</colgroup>
			<thead>
				<tr>
					<th class="text-center"><input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" /></th>
					<th>{LANG.name}</th>
					<th>{LANG.review_sender}</th>
					<th class="text-center">{LANG.review_rating}</th>
					<th>{LANG.search_bodytext}</th>
					<th>{LANG.review_add_time}</th>
					<th>{LANG.status}</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				<!-- BEGIN: loop -->
				<tr id="row_{VIEW.review_id}">
					<td class="text-center"><input type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{VIEW.review_id}" name="idcheck[]" /></td>
					<td><a href="{VIEW.link_product}" title="{VIEW.title}">{VIEW.title}</a></td>
					<td>{VIEW.sender}</td>
					<td class="text-center">{VIEW.rating}</td>
					<td>{VIEW.content}</td>
					<td>{VIEW.add_time}</td>
					<td>{VIEW.status}</td>
					<td class="text-center"><em class="fa fa-trash-o fa-lg">&nbsp;</em><a href="javascript:void(0)" title="{GLANG.delete}" onclick="nv_del_review({VIEW.review_id})">{GLANG.delete}</a></td>
				</tr>
				<!-- END: loop -->
			</tbody>
			<!-- BEGIN: generate_page -->
			<tfoot>
				<tr class="text-center">
					<td colspan="7">{NV_GENERATE_PAGE}</td>
				</tr>
			</tfoot>
			<!-- END: generate_page -->
		</table>
	</div>
	<select class="form-control w200 pull-left" id="action" name="action">
		<option value="delete">{LANG.prounit_del_select}</option>
		<option value="review_status_1">{LANG.review_status_1}</option>
		<option value="review_status_0">{LANG.review_status_0}</option>
	</select>&nbsp;
	<input type="button" class="btn btn-primary" onclick="nv_review_action(this.form, '{LANG.prounit_del_no_items}')" value="{LANG.action}" />
</form>
<!-- END: main -->