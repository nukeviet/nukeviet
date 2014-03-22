<!-- BEGIN: main -->
<div id="module_show_list">
	{BLOCK_CAT_LIST}
</div>
<br />
<a id="edit"></a>
<!-- BEGIN: error -->
<div class="quote">
	<blockquote class="error"><span>{ERROR}</span></blockquote>
</div>
<!-- END: error -->
<form action="{NV_BASE_ADMINURL}index.php" method="post">
	<input type="hidden" name ="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
	<input type="hidden" name ="{NV_OP_VARIABLE}" value="{OP}" />
	<input type="hidden" name ="bid" value="{bid}" />
	<input name="savecat" type="hidden" value="1" />
	<table class="tab1">
		<caption>{LANG.add_block_cat}</caption>
		<tfoot>
			<tr>
				<td class="center" colspan="2"><input name="submit1" type="submit" value="{LANG.save}" /></td>
			</tr>
		</tfoot>
		<tbody>
			<tr>
				<td class="right"><strong>{LANG.name}: </strong></td>
				<td><input class="w500" name="title" id="idtitle" type="text" value="{title}" maxlength="255" /> {GLANG.length_characters}: <span id="titlelength" class="red">0</span>. {GLANG.title_suggest_max} </td>
			</tr>
			<tr>
				<td class="right"><strong>{LANG.alias}: </strong></td>
				<td><input class="w500" name="alias" id="idalias" type="text" value="{alias}" maxlength="255" /> &nbsp; <em class="icon-refresh icon-large icon-pointer"onclick="get_alias('blockcat', {bid});">&nbsp;</em></td>
			</tr>
			<tr>
				<td class="right"><strong>{LANG.keywords}: </strong></td>
				<td><input class="w500" name="keywords" type="text" value="{keywords}" maxlength="255" /></td>
			</tr>
			<tr>
				<td class="right top">
				<br />
				<strong>{LANG.description}</strong></td>
				<td><textarea class="w500" id="description" name="description" cols="100" rows="5">{description}</textarea> {GLANG.length_characters}: <span id="descriptionlength" class="red">0</span>. {GLANG.description_suggest_max} </td>
			</tr>
			<tr>
				<td class="right"><strong>{LANG.content_homeimg}</strong></td>
				<td><input class="w500" type="text" name="image" id="image" value="{image}"/> <input type="button" value="Browse server" name="selectimg"/></td>
			</tr>
		</tbody>
	</table>
</form>
<script type="text/javascript">
	$("#titlelength").html($("#idtitle").val().length);
	$("#idtitle").bind("keyup paste", function() {
		$("#titlelength").html($(this).val().length);
	});

	$("#descriptionlength").html($("#description").val().length);
	$("#description").bind("keyup paste", function() {
		$("#descriptionlength").html($(this).val().length);
	});
	$("input[name=selectimg]").click(function() {
		var area = "image";
		var path = "{UPLOAD_CURRENT}";
		var currentpath = "{UPLOAD_CURRENT}";
		var type = "image";
		nv_open_browse_file(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + area + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
		return false;
	});
</script>
<!-- BEGIN: getalias -->
<script type="text/javascript">
	$("#idtitle").change(function() {
		get_alias("blockcat", '{bid}');
	});
</script>
<!-- END: getalias -->
<!-- END: main -->
