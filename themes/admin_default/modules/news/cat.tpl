<!-- BEGIN: main -->
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>

<div id="module_show_list">
	{CAT_LIST}
</div>
<br />

<div id="edit">
	<!-- BEGIN: error -->
	<div class="alert alert-warning">
		{ERROR}
	</div>
	<!-- END: error -->
	<!-- BEGIN: content -->
	<form action="{NV_BASE_ADMINURL}index.php" method="post">
		<input type="hidden" name ="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
		<input type="hidden" name ="{NV_OP_VARIABLE}" value="{OP}" />
		<input type="hidden" name ="catid" value="{catid}" />
		<input type="hidden" name ="parentid_old" value="{parentid}" />
		<input name="savecat" type="hidden" value="1" />
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover">
				<caption>
					<em class="fa fa-file-text-o">&nbsp;</em>{caption}
				</caption>
				<tbody>
					<tr>
						<th class="col-md-4 text-right">{LANG.name}: <sup class="required">(∗)</sup></th>
						<td class="col-md-20 text-left"><input class="form-control w500" name="title" type="text" value="{title}" maxlength="250" id="idtitle"/><span class="text-middle"> {GLANG.length_characters}: <span id="titlelength" class="red">0</span>. {GLANG.title_suggest_max} </span></td>
					</tr>
					<tr>
						<th class="text-right">{LANG.alias}: </th>
						<td><input class="form-control w500 pull-left" name="alias" type="text" value="{alias}" maxlength="250" id="idalias"/> &nbsp;<em class="fa fa-refresh fa-lg fa-pointer text-middle" onclick="get_alias('cat', {catid});">&nbsp;</em></td>
					</tr>
					<tr>
						<th class="text-right">{LANG.titlesite}: </th>
						<td><input class="form-control w500" name="titlesite" type="text" value="{titlesite}" maxlength="250" id="titlesite"/><span class="text-middle"> {GLANG.length_characters}: <span id="titlesitelength" class="red">0</span>. {GLANG.title_suggest_max} </span></td>
					</tr>
					<tr>
						<th class="text-right">{LANG.cat_sub}: </th>
						<td>
						<select class="form-control w200" name="parentid" id="parentid">
							<!-- BEGIN: cat_listsub -->
							<option value="{cat_listsub.value}" {cat_listsub.selected}>{cat_listsub.title}</option>
							<!-- END: cat_listsub -->
						</select></td>
					</tr>
					<tr>
						<th class="text-right">{LANG.keywords}: </th>
						<td><input class="form-control w500" name="keywords" type="text" value="{keywords}" maxlength="250" /></td>
					</tr>
					<tr>
						<th class="text-right">
						<br />
						{LANG.description} </th>
						<td >
							<textarea class="form-control" id="description" name="description" cols="100" rows="5">{description}</textarea>
							<br />
							<span class="text-middle"> {GLANG.length_characters}: <span id="descriptionlength" class="red">0</span>. {GLANG.description_suggest_max} </span></td>
					</tr>
					<tr>
						<th class="text-right">{LANG.content_homeimg}</th>
						<td><input class="form-control w500 pull-left" type="text" name="image" id="image" value="{image}"/> &nbsp;<input id="select-img-cat" type="button" value="Browse server" name="selectimg" class="btn btn-info" /></td>
					</tr>
					<tr>
						<th class="text-right">
						<br />
						{LANG.viewcat_detail} </th> 
						<td>
						<!-- BEGIN: groups_views -->
						<div class="row">
							<label><input name="groups_view[]" type="checkbox" value="{groups_views.value}" {groups_views.checked} />{groups_views.title}</label>
						</div>
						<!-- END: groups_views -->
						</td>
					</tr>
					<tr>
						<th class="text-right">{LANG.content_bodytext}: </th>
						<td>{DESCRIPTIONHTML}</td>
					</tr>
					<tr>
						<th class="text-right">{LANG.viewdescription}: </th>
						<td>
							<!-- BEGIN: viewdescription -->
							<input type="radio" name="viewdescription" value="{VIEWDESCRIPTION.value}" {VIEWDESCRIPTION.selected}> {VIEWDESCRIPTION.title} &nbsp; &nbsp;
							<!-- END: viewdescription -->
						</td>
					</tr>
					<!-- BEGIN: featured -->
					<tr>
						<th class="text-right">{LANG.featured}: </th>
						<td>
						<select class="form-control" name="featured" id="featured">
							<option value="0" >{LANG.not_featured}</option>
							<!-- BEGIN: featured_loop -->
							<option value="{FEATURED_NEWS.id}" {FEATURED_NEWS.selected}>{FEATURED_NEWS.title}</option>
							<!-- END: featured_loop -->
						</select></td>
					</tr>
					<!-- END: featured -->
					<tr>
						<th class="text-right">{LANG.ad_block_show}: </th> 
						<td>
						<!-- BEGIN: ad_block_cats -->
						<div class="row">
							<label><input name="ad_block_cat[]" type="checkbox" value="{ad_block_cats.value}" {ad_block_cats.checked} />{ad_block_cats.title}</label>
						</div>
						<!-- END: ad_block_cats -->
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<br />
		<div class="text-center">
			<input class="btn btn-primary" name="submit1" type="submit" value="{LANG.save}" />
		</div>
	</form>
</div>

<script type="text/javascript">
var CFG = [];
CFG.upload_current = '{UPLOAD_CURRENT}';
CFG.upload_path = '{UPLOAD_PATH}';
$(document).ready(function() {
	$("#parentid").select2();
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
	<!-- BEGIN: getalias -->
	$("#idtitle").change(function() {
		get_alias("cat", 0);
	});
	<!-- END: getalias -->
});
</script>
<!-- END: content -->
<!-- END: main -->