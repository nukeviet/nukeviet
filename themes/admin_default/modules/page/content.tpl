<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<form action="{FORM_ACTION}" method="post" class="confirm-reload">
	<input name="save" type="hidden" value="1" />
	<div class="row">
		<div class="col-sm-12 col-md-9">
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover">
					<colgroup>
						<col class="w200" />
						<col />
					</colgroup>
					<tbody>
						<tr>
							<td class="text-right"> {LANG.title}</td>
							<td><input class="w500 form-control pull-left" type="text" value="{DATA.title}" name="title" id="idtitle" maxlength="255" />&nbsp;<span class="text-middle"> {GLANG.length_characters}: <span id="titlelength" class="red">0</span>. {GLANG.title_suggest_max} </span></td>
						</tr>
						<tr>
							<td class="text-right">{LANG.alias}</td>
							<td><input class="w500 form-control pull-left" type="text" value="{DATA.alias}" name="alias" id="idalias" maxlength="255" />&nbsp;<em class="fa fa-refresh fa-lg fa-pointer" onclick="get_alias('{ID}');">&nbsp;</em></td>
						</tr>
						<tr>
							<td class="text-right">{LANG.image}</td>
							<td><input class="w500 form-control pull-left" type="text" name="image" id="image" value="{DATA.image}" style="margin-right: 5px"/> <input type="button" value="Browse server" name="selectimg" class="btn btn-info"/></td>
						</tr>
						<tr>
							<td class="text-right">{LANG.imagealt}</td>
							<td><input class="w500 form-control" type="text" name="imagealt" id="imagealt" value="{DATA.imagealt}"/></td>
						</tr>
						<tr>
							<td class="text-right">{LANG.description} </td>
							<td ><textarea class="w500 form-control" id="description" name="description" cols="100" rows="5">{DATA.description}</textarea> {GLANG.length_characters}: <span id="descriptionlength" class="red">0</span>. {GLANG.description_suggest_max} </td>
						</tr>
						<tr>
							<td colspan="2" class="strong"> {LANG.bodytext}
								<div>
									{BODYTEXT}
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="col-sm-12 col-md-3">
			<div class="row">
				<div class="col-sm-6 col-md-12">
					<div class="form-group">
						<label>{LANG.keywords}</label>
						<input class="form-control" type="text" value="{DATA.keywords}" name="keywords" />
					</div>
					<div class="form-group">
						<label>{LANG.socialbutton}</label>
						<p>
							<input type="checkbox" value="1" name="socialbutton"{SOCIALBUTTON}/> {LANG.socialbuttonnote}
						</p>
					</div>
					<div class="form-group">
						<label>{LANG.facebookAppID}</label>
						<input class="form-control" name="facebookappid" value="{DATA.facebookappid}" type="text"/>
						<span class="text-middle">&nbsp; {LANG.facebookAppIDNote} </span>
					</div>
					<div class="form-group">
						<label>{LANG.layout_func} </label>
						<select name="layout_func" class="form-control w200">
							<option value="">{LANG.layout_default}</option>
							<!-- BEGIN: layout_func -->
							<option value="{LAYOUT_FUNC.key}"{LAYOUT_FUNC.selected}>{LAYOUT_FUNC.key}</option>
							<!-- END: layout_func -->
						</select>
					</div>
					<!-- BEGIN: googleplus -->
					<div class="form-group">
						<label>{LANG.googleplus}</label>
						<select name="gid" class="form-control w200">
							<!-- BEGIN: gid -->
							<option value="{GOOGLEPLUS.gid}"{GOOGLEPLUS.selected}>{GOOGLEPLUS.title}</option>
							<!-- END: gid -->
						</select>
					</div>
					<!-- END: googleplus -->
				</div>
				<div class="col-sm-6 col-md-12">
					<div class="form-group">
						<label>{LANG.activecomm}</label>
						<!-- BEGIN: activecomm -->
						<div class="row">
							<label><input name="activecomm[]" type="checkbox" value="{ACTIVECOMM.value}" {ACTIVECOMM.checked} />{ACTIVECOMM.title}</label>
						</div>
						<!-- END: activecomm -->
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row text-center"><input type="submit" value="{LANG.save}" class="btn btn-primary"/></div>
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
		nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + area + "&alt=" + alt + "&path=" + path + "&type=" + type, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
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