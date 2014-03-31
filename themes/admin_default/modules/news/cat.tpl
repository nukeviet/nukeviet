<!-- BEGIN: main -->
<div id="module_show_list">
	{CAT_LIST}
</div>
<br />

<div id="edit">
	<!-- BEGIN: error -->
	<div class="quote">
		<blockquote class="error"><span>{ERROR}</span></blockquote>
	</div>
	<!-- END: error -->
	<!-- BEGIN: content -->
	<form action="{NV_BASE_ADMINURL}index.php" method="post">
		<input type="hidden" name ="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
		<input type="hidden" name ="{NV_OP_VARIABLE}" value="{OP}" />
		<input type="hidden" name ="catid" value="{catid}" />
		<input type="hidden" name ="parentid_old" value="{parentid}" />
		<input name="savecat" type="hidden" value="1" />
		<table class="tab1">
			<caption>{caption}</caption>
			<tbody>
				<tr>
					<td class="right"><strong>{LANG.name}: </strong></td>
					<td><input class="w500" name="title" type="text" value="{title}" maxlength="255" id="idtitle"/> {GLANG.length_characters}: <span id="titlelength" class="red">0</span>. {GLANG.title_suggest_max} </td>
				</tr>
				<tr>
					<td class="right"><strong>{LANG.alias}: </strong></td>
					<td><input class="w500" name="alias" type="text" value="{alias}" maxlength="255" id="idalias"/>&nbsp; <em class="icon-refresh icon-large icon-pointer" onclick="get_alias('cat', {catid});">&nbsp;</em></td>
				</tr>
				<tr>
					<td class="right"><strong>Title Site: </strong></td>
					<td><input class="w500" name="titlesite" type="text" value="{titlesite}" maxlength="255" id="titlesite"/> {GLANG.length_characters}: <span id="titlesitelength" class="red">0</span>. {GLANG.title_suggest_max}</td>
				</tr>
				<tr>
					<td class="right"><strong>{LANG.cat_sub}: </strong></td>
					<td>
					<select name="parentid">
						<!-- BEGIN: cat_listsub -->
						<option value="{cat_listsub.value}" {cat_listsub.selected}>{cat_listsub.title}</option>
						<!-- END: cat_listsub -->
					</select></td>
				</tr>
				<tr>
					<td class="right"><strong>{LANG.keywords}: </strong></td>
					<td><input class="w500" name="keywords" type="text" value="{keywords}" maxlength="255" /></td>
				</tr>
				<tr>
					<td class="right">
					<br />
					<strong>{LANG.description} </strong></td>
					<td ><textarea class="w500" id="description"  name="description" cols="100" rows="5">{description}</textarea> {GLANG.length_characters}: <span id="descriptionlength" class="red">0</span>. {GLANG.description_suggest_max} </td>
				</tr>
				<tr>
					<td class="right"><strong>{LANG.content_homeimg}</strong></td>
					<td><input class="w500" type="text" name="image" id="image" value="{image}"/> <input type="button" value="Browse server" name="selectimg"/></td>
				</tr>
				<tr>
					<td class="right">
					<br />
					<strong>{GLANG.who_view} </strong></td>
					<td>
					<div class="message_body">
						<select class="w250" name="who_view" id="who_view" onchange="nv_sh('who_view','groups_list')">
							<!-- BEGIN: who_views -->
							<option value="{who_views.value}" {who_views.selected}>{who_views.title}</option>
							<!-- END: who_views -->
						</select>
						<br />
						<div id="groups_list" style="{hidediv}">
							{GLANG.groups_view}:
							<table style="margin-bottom:8px; width:250px;">
								<tr>
									<td>
									<!-- BEGIN: groups_views -->
									<p>
										<input name="groups_view[]" type="checkbox" value="{groups_views.value}" {groups_views.checked} />{groups_views.title}
									</p>
									<!-- END: groups_views -->
									</td>
								</tr>
							</table>
						</div>
					</div></td>
				</tr>
				<tr>
					<td class="right"><strong>{LANG.viewdescription}: </strong></td>
					<td>
					<!-- BEGIN: viewdescription -->
					<input type="radio" name="viewdescription" value="{VIEWDESCRIPTION.value}" {VIEWDESCRIPTION.selected}> {VIEWDESCRIPTION.title} &nbsp; &nbsp;
					<!-- END: viewdescription -->
					</td>
				</tr>
			</tbody>
		</table>
		<br />
		<div class="center">
			<input name="submit1" type="submit" value="{LANG.save}" />
		</div>
	</form>
</div>

<script type="text/javascript">
	$("#titlelength").html($("#idtitle").val().length);
	$("#idtitle").bind("keyup paste", function() {
		$("#titlelength").html($(this).val().length);
	});

	$("#titlesitelength").html($("#titlesite").val().length);
	$("#titlesite").bind("keyup paste", function() {
		$("#titlesitelength").html($(this).val().length);
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
		get_alias("cat", 0);
	});
</script>
<!-- END: getalias -->
<!-- END: content -->
<!-- END: main -->