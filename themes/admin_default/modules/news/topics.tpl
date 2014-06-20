<!-- BEGIN: main -->
<div id="module_show_list">
	{TOPIC_LIST}
</div>
<br />
<a id="edit"></a>
<!-- BEGIN: error -->
<div class="alert alert-warning">{ERROR}</div>
<!-- END: error -->
<form class="form-inline" action="{NV_BASE_ADMINURL}index.php" method="post">
	<input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
	<input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
	<input type="hidden" name="topicid" value="{DATA.topicid}" />
	<input name="savecat" type="hidden" value="1" />
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.add_topic}</caption>
			<tfoot>
				<tr>
					<td class="text-center" colspan="2"><input class="btn btn-primary" name="submit1" type="submit" value="{LANG.save}" /></td>
				</tr>
			</tfoot>
			<tbody>
				<tr>
					<td class="text-right"><strong>{LANG.name}: </strong></td>
					<td><input class="form-control w500" name="title" id="idtitle" type="text" value="{DATA.title}" maxlength="255" /></td>
				</tr>
				<tr>
					<td class="text-right"><strong>{LANG.alias}: </strong></td>
					<td><input class="form-control w500" name="alias" id="idalias" type="text" value="{DATA.alias}" maxlength="255" /> &nbsp; <em class="fa fa-refresh fa-lg fa-pointer" onclick="get_alias('topics', {DATA.topicid});">&nbsp;</em></td>
				</tr>
				<tr>
					<td class="text-right"><strong>{LANG.content_homeimg}:</strong></td>
					<td><input class="form-control pull-left w500" style="margin-right: 5px" type="text" name="homeimg" id="homeimg" value="{DATA.image}" /> <input type="button" value="Browse server" name="selectimg" class="btn btn-info" /></td>
				</tr>
				<tr>
					<td class="text-right"><strong>{LANG.keywords}: </strong></td>
					<td><input class="form-control w500" name="keywords" type="text" value="{DATA.keywords}" maxlength="255" /></td>
				</tr>
				<tr>
					<td class="text-right">
					<br />
					<strong>{LANG.description}</strong></td>
					<td><textarea class="form-control w500" name="description" cols="100" rows="5">{DATA.description}</textarea></td>
				</tr>
			</tbody>
		</table>
	</div>
	<br />
</form>
<!-- BEGIN: getalias -->
<script type="text/javascript">
	$("#idtitle").change(function() {
		get_alias('topics', '{DATA.topicid}');
	});
</script>
<!-- END: getalias -->

<script type="text/javascript">
	$("input[name=selectimg]").click(function() {
		var area = "homeimg";
		var path = "{UPLOADS_DIR}";
		var currentpath = "{UPLOADS_DIR}";
		var type = "image";
		nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + area + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
		return false;
	});
</script>
<!-- END: main -->