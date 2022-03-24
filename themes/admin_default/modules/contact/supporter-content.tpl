<!-- BEGIN: main -->

<!-- BEGIN: error -->
<div class="alert alert-warning">{ERROR}</div>
<!-- END: error -->

<form class="form-horizontal" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<div class="panel panel-default">
		<div class="panel-body">
			<input type="hidden" name="id" value="{ROW.id}" />
			<div class="form-group">
				<label class="col-sm-5 col-md-3 control-label"><strong>{LANG.department_parent}</strong></label>
				<div class="col-sm-19 col-md-21">
					<select name="departmentid" class="form-control">
						<!-- BEGIN: department -->
						<option value="{DEPARTMENT.id}"{DEPARTMENT.selected}>{DEPARTMENT.full_name}</option>
						<!-- END: department -->
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 col-md-3 control-label"><strong>{LANG.full_name}</strong></label>
				<div class="col-sm-19 col-md-21">
					<input class="form-control required" type="text" name="full_name" value="{ROW.full_name}" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 col-md-3 control-label"><strong>{LANG.image}</strong></label>
				<div class="col-sm-19 col-md-21">
					<div class="input-group">
						<input class="form-control" type="text" name="image" value="{ROW.image}" id="id_image" /> <span class="input-group-btn">
							<button class="btn btn-default selectfile" type="button">
								<em class="fa fa-folder-open-o fa-fix">&nbsp;</em>
							</button>
						</span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 col-md-3 control-label"><strong>{GLANG.phonenumber}</strong></label>
				<div class="col-sm-19 col-md-21">
					<input class="form-control required" type="text" name="phone" value="{ROW.phone}" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" />
					<span class="pointer help-block" onclick="modalShow('{GLANG.phone_note_title}','{GLANG.phone_note_content}');return!1;">{GLANG.phone_note_title}</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 col-md-3 control-label"><strong>{GLANG.email}</strong></label>
				<div class="col-sm-19 col-md-21">
					<input class="form-control" type="email" name="email" value="{ROW.email}" oninvalid="setCustomValidity( nv_email )" oninput="setCustomValidity('')" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 col-md-3 text-right"><strong>{LANG.otherContacts}</strong></label>
				<div class="col-sm-19 col-md-21">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>{LANG.otherVar}</th>
									<th>{LANG.otherVal}</th>
									<th class="w50"></th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<td colspan="3"><button class="btn btn-default btn-xs" onclick="nv_contact_add(); return !1;">{LANG.supporter_contact_add}</button></td>
								</tr>
							</tfoot>
							<tbody id="others">
								<!-- BEGIN: others -->
								<tr>
									<td><input type="text" name="others[{OTHERS.index}][name]" class="form-control" value="{OTHERS.name}" /></td>
									<td><input type="text" name="others[{OTHERS.index}][value]" class="form-control" value="{OTHERS.value}" /></td>
									<td class="text-center" onclick="$(this).closest('tr').remove();"><em class="fa fa-trash-o fa-pointer fa-lg">&nbsp;</em></td>
								</tr>
								<!-- END: others -->
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="text-center">
		<input class="btn btn-primary" name="submit" type="submit" value="{LANG.save}" />
	</div>
</form>

<script type="text/javascript">
	//<![CDATA[
	
	var count = {COUNT};
	
	function nv_contact_add()
	{
		var html = '';
		html += '<tr>';
		html += '	<td><input type="text" name="others[' + count + '][name]" class="form-control" /></td>';
		html += '	<td><input type="text" name="others[' + count + '][value]" class="form-control" /></td>';
		html += '<td class="text-center" onclick="$(this).closest(\'tr\').remove();"><em class="fa fa-trash-o fa-pointer fa-lg">&nbsp;</em></td>';
		html += '</tr>';
		count++;
		$('#others').append(html);
	}
			
	$(".selectfile")
			.click(
					function() {
						var area = "id_image";
						var path = "{NV_UPLOADS_DIR}/{MODULE_UPLOAD}";
						var currentpath = "{NV_UPLOADS_DIR}/{MODULE_UPLOAD}";
						var type = "image";
						nv_open_browse(script_name + "?" + nv_name_variable
								+ "=upload&popup=1&area=" + area + "&path="
								+ path + "&type=" + type + "&currentpath="
								+ currentpath, "NVImg", 850, 420,
								"resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
						return false;
					});
	//]]>
</script>
<!-- END: main -->