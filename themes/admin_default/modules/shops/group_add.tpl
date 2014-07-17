<!-- BEGIN: main -->
<div id="module_show_list">
	{GROUP_LIST}
</div>
<div id="group-delete-area">&nbsp;</div>
<div id="edit" class="table-responsive">
	<!-- BEGIN: error -->
	<div class="alert alert-warning">{error}</div>
	<!-- END: error -->
	<form class="form-inline" action="" method="post">
		<input type="hidden" name ="groupid" value="{DATA.groupid}" />
		<input type="hidden" name ="parentid_old" value="{DATA.parentid}" />
		<input name="savegroup" type="hidden" value="1" />
		<table class="table table-striped table-bordered table-hover">
			<caption>{CAPTION}</caption>
			<tbody>
				<tr>
					<td align="right"><strong>{LANG.group_name}</strong></td>
					<td><input class="form-control" style="width: 500px" name="title" type="text" value="{DATA.title}" maxlength="255" id="idtitle"  required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')"  /><span class="text-middle"> {GLANG.length_characters}: <span id="titlelength" class="red">0</span>. {GLANG.title_suggest_max} </span></td>
				</tr>
				<tr>
					<td align="right"  width="180px"><strong>{LANG.alias} : </strong></td>
					<td><input class="form-control" style="width: 500px" name="alias" type="text" value="{DATA.alias}" maxlength="255" id="idalias"/>&nbsp; <em class="fa fa-refresh fa-lg fa-pointer" onclick="get_alias('group', {DATA.groupid});">&nbsp;</em></td>
				</tr>
				<tr>
					<td align="right"><strong>{LANG.group_sub}</strong></td>
					<td>
						<select class="form-control" name="parentid" onchange="nv_getcatalog(this)">
							<!-- BEGIN: parent_loop -->
							<option value="{pgroup_i}" {pselect}>{ptitle_i}</option>
							<!-- END: parent_loop -->
						</select>&nbsp;
						<span id="vcatid"></span>
					</td>
				</tr>
				<tr>
					<td align="right"><strong>{LANG.keywords}: </strong></td>
					<td><input class="form-control" style="width: 500px" name="keywords" type="text" value="{DATA.keywords}" maxlength="255" /></td>
				</tr>
				<tr>
					<td align="right"><strong>{LANG.description}</strong></td>
					<td><textarea style="width: 500px" name="description" id="description" cols="100" rows="5" class="form-control">{DATA.description}</textarea> <span class="text-middle"> {GLANG.length_characters}: <span id="descriptionlength" class="red">0</span>. {GLANG.description_suggest_max} </span></td>
				</tr>
				<tr>
					<th class="text-right">{LANG.content_homeimg}</th>
					<td>
						<input class="form-control" style="width: 500px" type="text" name="image" id="image" value="{DATA.image}"/>
						<a class="btn btn-info" name="selectimg"><em class="fa fa-folder-open-o">&nbsp;</em>{LANG.file_selectfile}</a>
					</td>
				</tr>
			</tbody>
		</table>
		<div class="text-center">
			<input class="btn btn-primary" name="submit1" type="submit" value="{LANG.save}"/>
		</div>
	</form>
</div>
<script type="text/javascript">
	$('#vcatid').load('{URL}');

	$("#titlelength").html($("#idtitle").val().length);
	$("#idtitle").bind("keyup paste", function() {
		$("#titlelength").html($(this).val().length);
	});

	$("#descriptionlength").html($("#description").val().length);
	$("#description").bind("keyup paste", function() {
		$("#descriptionlength").html($(this).val().length);
	});

	$("a[name=selectimg]").click(function() {
		var area = "image";
		var path = "{UPLOAD_CURRENT}";
		var currentpath = "{UPLOAD_CURRENT}";
		var type = "image";
		nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + area + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
		return false;
	});
</script>

<!-- BEGIN: getalias -->
<script type="text/javascript">
	$("#idtitle").change(function() {
		get_alias("cat", 0);
	});
</script>
<!-- END: getalias -->
<!-- END: main -->