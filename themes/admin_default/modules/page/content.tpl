<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="quote">
	<blockquote class="error"><span>{ERROR}</span></blockquote>
</div>
<!-- END: error -->
<form action="{FORM_ACTION}" method="post" class="confirm-reload">
	<input name="save" type="hidden" value="1" />
	<table class="tab1">
		<colgroup>
			<col class="w200" />
			<col />
		</colgroup>
		<tfoot>
			<tr>
				<td colspan="2" class="center"><input type="submit" value="{LANG.save}"/></td>
			</tr>
		</tfoot>
		<tbody>
			<tr>
				<td class="right strong">{LANG.title}</td>
				<td><input class="w500" type="text" value="{DATA.title}" name="title" id="idtitle" maxlength="255" /> {GLANG.length_characters}: <span id="titlelength" class="red">0</span>. {GLANG.title_suggest_max} </td>
			</tr>
			<tr>
				<td class="right strong">{LANG.alias}</td>
				<td><input class="w500" type="text" value="{DATA.alias}" name="alias" id="idalias" maxlength="255" /> <em class="icon-refresh icon-large icon-pointer" onclick="get_alias('{ID}');">&nbsp;</em></td>
			</tr>
			<tr>
				<td class="right strong">{LANG.image}</td>
				<td><input class="w500" type="text" name="image" id="image" value="{DATA.image}"/> <input type="button" value="Browse server" name="selectimg"/></td>
			</tr>
			<tr>
				<td class="right strong">{LANG.imagealt}</td>
				<td><input class="w500" type="text" name="imagealt" id="imagealt" value="{DATA.imagealt}"/></td>
			</tr>
			<tr>
				<td class="right strong">{LANG.description} </td>
				<td ><textarea class="w500" id="description" name="description" cols="100" rows="5">{DATA.description}</textarea> {GLANG.length_characters}: <span id="descriptionlength" class="red">0</span>. {GLANG.description_suggest_max} </td>
			</tr>
			<tr>
				<td colspan="2" class="strong"> {LANG.bodytext}
					<div>
						{BODYTEXT}
					</div>
				</td>
			</tr>
			<tr>
				<td class="right strong">{LANG.keywords}</td>
				<td><input class="w500" type="text" value="{DATA.keywords}" name="keywords" /></td>
			</tr>
			<tr>
				<td class="right strong">{LANG.socialbutton}</td>
				<td><input type="checkbox" value="1" name="socialbutton"{SOCIALBUTTON}/> {LANG.socialbuttonnote}</td>
			</tr>
			<tr>
				<td class="right strong">{LANG.activecomm}</td>
				<td>
				<select name="activecomm">
					<!-- BEGIN: activecomm -->
					<option value="{ACTIVECOMM.key}"{ACTIVECOMM.selected}>{ACTIVECOMM.title}</option>
					<!-- END: activecomm -->
				</select></td>
			</tr>
			<tr>
				<td class="right strong">{LANG.facebookAppID}</td>
				<td><input class="w150" name="facebookappid" value="{DATA.facebookappid}" type="text"/>{LANG.facebookAppIDNote}</td>
			</tr>
			<tr>
				<td class="right strong">{LANG.layout_func} </td>
				<td>
				<select name="layout_func">
					<option value="">{LANG.layout_default}</option>
					<!-- BEGIN: layout_func -->
					<option value="{LAYOUT_FUNC.key}"{LAYOUT_FUNC.selected}>{LAYOUT_FUNC.key}</option>
					<!-- END: layout_func -->
				</select></td>
			</tr>
			<!-- BEGIN: googleplus -->
			<tr>
				<td class="right strong">{LANG.googleplus}</td>
				<td>
				<select name="gid">
					<!-- BEGIN: gid -->
					<option value="{GOOGLEPLUS.gid}"{GOOGLEPLUS.selected}>{GOOGLEPLUS.title}</option>
					<!-- END: gid -->
				</select></td>
			</tr>
			<!-- END: googleplus -->
		</tbody>
	</table>
</form>
<script type="text/javascript">
	$("#titlelength").html($("#idtitle").val().length);
	$("#idtitle").bind('keyup paste', function() {
		$("#titlelength").html($(this).val().length);
	});

	$("#descriptionlength").html($("#description").val().length);
	$("#description").bind('keyup paste', function() {
		$("#descriptionlength").html($(this).val().length);
	});

	$("input[name=selectimg]").click(function() {
		var area = "image";
		var alt = "imagealt";
		var path = "{UPLOADS_DIR_USER}";
		var type = "image";
		nv_open_browse_file(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + area + "&alt=" + alt + "&path=" + path + "&type=" + type, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
		return false;
	});
</script>
<!-- BEGIN: get_alias -->
<script type="text/javascript">
	$(document).ready(function() {
		$('#idtitle').change(function() {
			get_alias('{ID}');
		});
	});
</script>
<!-- END: get_alias -->
<!-- END: main -->
