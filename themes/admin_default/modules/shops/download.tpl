<!-- BEGIN: main -->

<!-- BEGIN: non_popup -->
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
			<col class="w100" span="2" />
			<col class="w150" />
		</colgroup>
		<thead>
			<tr>
				<th>{LANG.download_file_title}</th>
				<th class="text-center">{LANG.download_file_time}</th>
				<th class="text-center">{LANG.download_file_count}</th>
				<th class="text-center">{LANG.download_file_down_hits}</th>
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
				<td class="text-center">{VIEW.download_hits}</td>
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

<h3>{LANG.download_file_add}</h3>

<div id="edit">
	<!-- BEGIN: error -->
	<div class="alert alert-danger">{ERROR}</div>
	<!-- END: error -->

	<form action="{ACTION}" method="post" class="form-horizontal" id="frm_add_file">
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
					<div class="col-sm-21">
						<div class="input-group">
							<input type="text" name="path" id="path" value="{FILE_PATH}" class="form-control" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')">
							<span class="input-group-btn">
								<button class="btn btn-default" type="button" id="open_files">
									<em class="fa fa-folder-open-o fa-fix">&nbsp;</em>
								</button>
							</span>
						</div>
						<em class="help-block">{LANG.download_file_path_note}</em>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label"><strong>{LANG.download_file_description}</strong></label>
					<div class="col-sm-21">
						<textarea class="form-control" name="description">{DATA.description}</textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3"><strong>{LANG.download_setting_groups}</strong></label>
					<div class="col-sm-21">
						<ul class="list-inline">
							<!-- BEGIN: download_groups -->
							<li><label><input name="download_groups[]" type="checkbox" value="{DOWNLOAD_GROUPS.value}" {DOWNLOAD_GROUPS.checked} />{DOWNLOAD_GROUPS.title}</label></li>
							<!-- END: download_groups -->
						</ul>
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
<!-- END: non_popup -->

<!-- BEGIN: popup -->
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>

<div id="divsuccess" class="alert alert-info" style="display: none">{LANG.download_file_add_success}</div>

<form action="{ACTION}" method="post" class="form-horizontal" id="frm_add_file">
	<input type="hidden" name="id" value="{DATA.id}" />
	<table style="width: 100%" class="table table-striped table-hover">
		<tr>
			<td>{LANG.download_file_title}</td>
			<td colspan="2"><input type="text" name="title" value="{DATA.title}" class="form-control" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" style="width: 100%"></td>
		</tr>
		<tr>
			<td>{LANG.download_file_path}</td>
			<td colspan="2">
				<div class="input-group">
					<input type="text" name="path" id="path" value="{FILE_PATH}" class="form-control" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')">
					<span class="input-group-btn">
						<button class="btn btn-default" type="button" id="open_files">
							<em class="fa fa-folder-open-o fa-fix">&nbsp;</em>
						</button>
					</span>
				</div>
				<em class="help-block">{LANG.download_file_path_note}</em>
			</td>
		</tr>
		<tr>
			<td>{LANG.download_file_description}</td>
			<td colspan="2"><textarea class="form-control" name="description" style="width: 100%">{DATA.description}</textarea></td>
		</tr>
		<tr>
			<td>{LANG.download_setting_groups}</td>
			<td colspan="2">
				<!-- BEGIN: download_groups -->
				<label class="show"><input name="download_groups[]" type="checkbox" value="{DOWNLOAD_GROUPS.value}" {DOWNLOAD_GROUPS.checked} />{DOWNLOAD_GROUPS.title}</label>
				<!-- END: download_groups -->
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="2"><input type="submit" name="submit" class="btn btn-primary" value="{LANG.save}" /></td>
		</tr>
	</table>
</form>
<!-- END: popup -->


<script type="text/javascript">
	var popup = '{POPUP}';
	$('#frm_add_file').submit(function(){
		$.ajax({
			type: 'POST',
			url: $(this).attr( 'action' ),
			data: $(this).serialize() + '&submit=1',
			success: function(res){
				var r_split = res.split('_');
				if( r_split[0] == 'OK' )
				{
					if( popup == '1' ){
						$('#divsuccess').slideDown();
						$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=download&nocache=' + new Date().getTime(), 'get_files=1', function(res) {
							$('#files').html( res );
						});
						$('#frm_add_file').clearForm();
					}
					else{
						window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=download';
					}
				}
				else
				{
					alert( r_split[1] );
				}
	    	}
	    });
		return false;
	});

	$("#open_files").click(function() {
		var area = "path";
		var path = "{UPLOADS_FILES_DIR}";
		var type = "file";
		nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + area + "&path=" + path + "&type=" + type, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
		return false;
	});
</script>
<!-- END: main -->
