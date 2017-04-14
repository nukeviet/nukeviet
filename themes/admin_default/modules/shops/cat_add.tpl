<!-- BEGIN: main -->
<div id="module_show_list">
	{CAT_LIST}
</div>
<div id="cat-delete-area">&nbsp;</div>
<div id="edit" class="table-responsive">
	<!-- BEGIN: error -->
	<div class="alert alert-warning">{error}</div>
	<!-- END: error -->
	<form class="form-inline" action="{FORM_ACTION}" method="post">
		<input type="hidden" name="catid" value="{DATA.catid}" />
		<input type="hidden" name="parentid_old" value="{DATA.parentid}" />
		<input name="savecat" type="hidden" value="1" />
		<table class="table table-striped table-bordered table-hover">
			<caption>{CAPTION}</caption>
			<tbody>
				<tr>
					<th class="text-right">{LANG.catalog_name}</th>
					<td><input class="form-control" style="width: 500px" name="title" type="text" value="{DATA.title}" maxlength="255" id="idtitle" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" /><span class="text-middle"> {GLANG.length_characters}: <span id="titlelength" class="red">0</span>. {GLANG.title_suggest_max} </span></td>
				</tr>
				<tr>
					<th class="text-right">{LANG.alias} : </th>
					<td><input class="form-control" style="width: 500px" name="alias" type="text" value="{DATA.alias}" maxlength="255" id="idalias"/>&nbsp; <em class="fa fa-refresh fa-lg fa-pointer" onclick="get_alias('cat', {DATA.catid});">&nbsp;</em>
				</tr>
				<tr>
					<th class="text-right">{LANG.cat_sub}</th>
					<td>
					<select class="form-control" name="parentid">
						<!-- BEGIN: parent_loop -->
						<option value="{pcatid_i}" {pselect}>{ptitle_i}</option>
						<!-- END: parent_loop -->
					</select>
					</td>
				</tr>
				<tr>
					<th class="text-right">{LANG.typeprice} </th>
					<td>
						<!-- BEGIN: typeprice_loop -->
						<label><input type="radio" name="typeprice" value="{TYPEPRICE.key}" {TYPEPRICE.checked} />{TYPEPRICE.value}</label>&nbsp;&nbsp;
						<!-- END: typeprice_loop -->
					</td>
				</tr>
				<tr>
					<th class="text-right">{LANG.keywords}: </th>
					<td><input class="form-control" style="width: 500px" name="keywords" type="text" value="{DATA.keywords}" maxlength="255" /></td>
				</tr>
				<tr>
					<th class="text-right">{LANG.description}</th>
					<td>
						<textarea style="width: 500px" name="description" id="description" cols="100" rows="5" class="form-control">{DATA.description}</textarea> <span class="text-middle"> {GLANG.length_characters}: <span id="descriptionlength" class="red">0</span>. {GLANG.description_suggest_max} </span>
					</td>
				</tr>
				<tr>
					<th class="text-right">{LANG.content_homeimg}</th>
					<td>
						<input class="form-control" style="width: 500px" type="text" name="image" id="image" value="{DATA.image}"/>
						<a class="btn btn-info" name="selectimg"><em class="fa fa-folder-open-o">&nbsp;</em>{LANG.file_selectfile}</a>
					</td>
				</tr>
				<tr>
					<th class="text-right">{LANG.content_bodytext}</th>
					<td>
						{DESCRIPTIONHTML}
					</td>
				</tr>
				<tr>
					<th class="text-right">{LANG.content_bodytext_display}</th>
					<td>
						<!-- BEGIN: viewdescriptionhtml -->
						<label><input type="radio" name="viewdescriptionhtml" value="{VIEWDESCRIPTION.value}" {VIEWDESCRIPTION.checked} />{VIEWDESCRIPTION.title}</label>&nbsp;&nbsp;&nbsp;
						<!-- END: viewdescriptionhtml -->
					</td>
				</tr>
				<!-- BEGIN: cat_form -->
				<tr>
					<th class="text-right">{LANG.cat_form}: </th>
					<td>
						<select class="form-control" name="cat_form">
							<option value=""> -- </option>
							<!-- BEGIN: loop -->
							<option value="{CAT_FORM.value}" {CAT_FORM.selected}>{CAT_FORM.title}</option>
							<!-- END: loop -->
					</select>
					</td>
				</tr>
				<!-- END: cat_form -->
				<tr>
					<th class="text-right">{GLANG.groups_view}</th>
					<td>
						<!-- BEGIN: groups_view -->
						<div class="row">
							<label><input name="groups_view[]" type="checkbox" value="{GROUPS_VIEW.value}" {GROUPS_VIEW.checked} />{GROUPS_VIEW.title}</label>
						</div>
						<!-- END: groups_view -->
					</td>
				</tr>
			</tbody>
		</table>
		<!-- BEGIN: point -->
		<table class="table table-striped table-bordered table-hover">
			<caption>{LANG.setting_point}</caption>
			<colgroup>
				<col class="w300" />
			</colgroup>
			<tbody>
				<tr>
					<td><strong>{LANG.cat_allow_point}</strong></td>
					<td><input type="checkbox" name="cat_allow_point" value="1" {DATA.cat_allow_point} /></td>
				</tr>
				<tr>
					<td><strong>{LANG.cat_number_point}</strong></td>
					<td><input type="text" class="form-control" name="cat_number_point" value="{DATA.cat_number_point}" {DATA.cat_number_point_dis} /></td>
				</tr>
				<tr>
					<td><strong>{LANG.cat_number_product}</strong></td>
					<td><input type="text"  class="form-control" name="cat_number_product" value="{DATA.cat_number_product}" {DATA.cat_number_product_dis} /></td>
				</tr>
			</tbody>
		</table>
		<!-- END: point -->
		
		<table class="table table-striped table-bordered table-hover">
			<caption>{LANG.tag}</caption>
			<colgroup>
				<col class="w300" />
			</colgroup>
			<tbody>
				<tr>
					<td class="text-right"><strong>{LANG.tag_title}</strong></td>
					<td>
						<input class="form-control" style="width: 500px" name="title_custom" type="text" value="{DATA.title_custom}" maxlength="255" id="titlesite"/><span class="text-middle"> {GLANG.length_characters}: <span id="titlesitelength" class="red">0</span>. {GLANG.title_suggest_max} </span>
					</td>
				</tr>
				<tr>
					<td class="text-right"><strong>{LANG.tag_description}</strong></td>
					<td>
						<textarea class="form-control" style="width: 500px" name="tag_description">{DATA.tag_description}</textarea>
					</td>
				</tr>
			</tbody>
		</table>

		<table class="table table-striped table-bordered table-hover">
			<caption>{LANG.setting_group_price}</caption>
			<colgroup>
				<col class="w300" />
			</colgroup>
			<tbody>
				<tr>
					<td><strong>{LANG.setting_group_price_space}</strong><em class="help-block">{LANG.setting_group_price_space_note_cat}</em></td>
					<td>
						<textarea class="form-control" name="group_price" rows="9" style="width: 100%">{DATA.group_price}</textarea>
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
	$(document).ready(function() {
		$('input[name="cat_allow_point"]').change(function() {
			if($(this).is(":checked"))
			{
				$('input[name="cat_number_point"]').removeAttr('readonly');
				$('input[name="cat_number_product"]').removeAttr('readonly');
			}
			else
			{
				$('input[name="cat_number_point"]').attr('readonly','readonly');
				$('input[name="cat_number_product"]').attr('readonly','readonly');
			}
		});
	});

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
		get_alias("cat", {DATA.catid});
	});
</script>
<!-- END: getalias -->
<!-- END: main -->