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

<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<colgroup>
			<col />
			<col class="w150" />
			<col class="w150" />
			<col class="w100" />
			<col class="w150" />
		</colgroup>
		<thead>
			<tr>
				<th>{LANG.download_file_title}</th>
				<th class="text-center">{LANG.download_file_time}</th>
				<th class="text-center">{LANG.download_file_count}</th>
				<th class="text-center">{LANG.status}</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr id="row_{VIEW.id}">
				<td>{VIEW.title}</td>
				<td class="text-center">{VIEW.addtime}</td>
				<td class="text-center">{VIEW.count_product}</td>
				<td class="text-center"><input type="checkbox" id="change_active_{VIEW.id}" onclick="nv_change_active_files({VIEW.id})" {VIEW.active} /></td>
				<td class="text-center">
					<em class="fa fa-trash-o fa-lg">&nbsp;</em><a href="{VIEW.url_edit}" title="{GLANG.delete}">{GLANG.edit}</a>&nbsp;
					<em class="fa fa-trash-o fa-lg">&nbsp;</em><a href="javascript:void(0)" title="{GLANG.delete}" onclick="nv_del_files({VIEW.id})">{GLANG.delete}</a>
				</td>
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

<div id="edit">
	<h3>{LANG.download_file_add}</h3>

	<!-- BEGIN: error -->
	<div class="alert alert-danger">{ERROR}</div>
	<!-- END: error -->

	<form action="{ACTION}" method="post" class="form-horizontal">
		<input type="hidden" name="id" value="{DATA.id}" />
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="form-group">
					<label class="col-sm-3 control-label"><strong>{LANG.download_file_title}</strong> <span class="red">*</span></label>
					<div class="col-sm-21">
						<input type="text" name="title" value="{DATA.title}" class="form-control" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label"><strong>{LANG.download_file_path}</strong> <span class="red">*</span></label>
					<div class="col-sm-18">
						<input type="text" name="path" id="path" value="{DATA.path}" class="form-control" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')">
					</div>
					<div class="col-sm-3">
						<button class="btn btn-primary" id="open_files"><em class="fa fa-folder-open-o">&nbsp;</em>{LANG.download_file_chose}</button>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label"><strong>{LANG.download_file_description}</strong></label>
					<div class="col-sm-21">
						<textarea class="form-control" name="description">{DATA.description}</textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">&nbsp;</label>
					<div class="col-sm-21">
						<input type="submit" name="submit" class="btn btn-primary" value="{LANG.save}" />
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
<script type="text/javascript">
	$("#open_files").click(function() {
		var area = "path";
		var path = "{UPLOADS_FILES_DIR}";
		var type = "file";
		nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + area + "&path=" + path + "&type=" + type, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
		return false;
	});
</script>
<!-- END: main -->