<!-- BEGIN: main -->
<link rel="stylesheet" href="{NV_BASE_SITEURL}js/select2/select2.min.css">
<script type="text/javascript" src="{NV_BASE_SITEURL}js/select2/select2.min.js"></script>

<form class="form-inline" role="form" action="{NV_BASE_ADMINURL}index.php" method="post">
	<input type="hidden" name ="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
	<input type="hidden" name ="{NV_OP_VARIABLE}" value="{OP}" />
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.setting_view}</caption>
			<tbody>
				<tr>
					<th>{LANG.setting_indexfile}</th>
					<td>
					<select class="form-control" name="indexfile">
						<!-- BEGIN: indexfile -->
						<option value="{INDEXFILE.key}"{INDEXFILE.selected}>{INDEXFILE.title}</option>
						<!-- END: indexfile -->
					</select></td>
				</tr>
				<tr>
					<th>{LANG.setting_homesite}</th>
					<td><input class= "form-control" type="text" value="{DATA.homewidth}" name="homewidth" /><span class="text-middle"> x </span><input class= "form-control" type="text" value="{DATA.homeheight}" name="homeheight" /></td>
				</tr>
				<tr>
					<th>{LANG.setting_thumbblock}</th>
					<td><input class= "form-control" type="text" value="{DATA.blockwidth}" name="blockwidth" /><span class="text-middle"> x </span><input class= "form-control" type="text" value="{DATA.blockheight}" name="blockheight" /></td>
				</tr>
				<tr>
					<th>{LANG.setting_imagefull}</th>
					<td><input class= "form-control" type="text" value="{DATA.imagefull}" name="imagefull" /></td>
				</tr>
				<tr>
					<th>{LANG.setting_per_page}</th>
					<td>
					<select class="form-control" name="per_page">
						<!-- BEGIN: per_page -->
						<option value="{PER_PAGE.key}"{PER_PAGE.selected}>{PER_PAGE.title}</option>
						<!-- END: per_page -->
					</select></td>
				</tr>
				<tr>
					<th>{LANG.setting_st_links}</th>
					<td>
					<select class="form-control" name="st_links">
						<!-- BEGIN: st_links -->
						<option value="{ST_LINKS.key}"{ST_LINKS.selected}>{ST_LINKS.title}</option>
						<!-- END: st_links -->
					</select></td>
				</tr>
				<tr>
					<th>{LANG.showtooltip}</th>
					<td>
						<input type="checkbox" value="1" name="showtooltip"{SHOWTOOLTIP}/>
						&nbsp;&nbsp;&nbsp;<span class="text-middle">{LANG.showtooltip_position}</span>
						<select name="tooltip_position" class="form-control">
							<!-- BEGIN: tooltip_position -->
							<option value="{TOOLTIP_P.key}"{TOOLTIP_P.selected}>{TOOLTIP_P.title}</option>
							<!-- END: tooltip_position -->
						</select>
						&nbsp;&nbsp;&nbsp;<span class="text-middle">{LANG.showtooltip_length}</span>
						<input type="text" name="tooltip_length" class="form-control" value="{DATA.tooltip_length}" style="width: 100px" />
					</td>
				</tr>
				<tr>
					<th>{LANG.showhometext}</th>
					<td><input type="checkbox" value="1" name="showhometext"{SHOWHOMETEXT}/></td>
				</tr>
				<tr>
					<th>{LANG.socialbutton}</th>
					<td><input type="checkbox" value="1" name="socialbutton"{SOCIALBUTTON}/></td>
				</tr>
				<tr>
					<th>{LANG.allowed_rating_point}</th>
					<td>
						<select class="form-control" name="allowed_rating_point">
							<!-- BEGIN: allowed_rating_point -->
							<option value="{RATING_POINT.key}"{RATING_POINT.selected}>{RATING_POINT.title}</option>
							<!-- END: allowed_rating_point -->
						</select>
					</td>
				</tr>
				<tr>
					<th>{LANG.show_no_image}</th>
					<td><input class="form-control" name="show_no_image" id="show_no_image" value="{SHOW_NO_IMAGE}" style="width:340px;" type="text"/> <input value="{GLANG.browse_image}" name="selectimg" type="button" class="btn btn-info"/></td>
				</tr>
				<tr>
					<th>{LANG.config_source}</th>
					<td>
					<select class="form-control" name="config_source">
						<!-- BEGIN: config_source -->
						<option value="{CONFIG_SOURCE.key}"{CONFIG_SOURCE.selected}>{CONFIG_SOURCE.title}</option>
						<!-- END: config_source -->
					</select></td>
				</tr>
				<tr>
					<th>{LANG.setting_copyright}</th>
					<td>{COPYRIGHTHTML}</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.setting_post}</caption>
			<tbody>
				<tr>
					<th>{LANG.facebookAppID}</th>
					<td><input class="form-control w150" name="facebookappid" value="{DATA.facebookappid}" type="text"/><span class="text-middle">{LANG.facebookAppIDNote}</span></td>
				</tr>
				<tr>
					<th>{LANG.setting_alias_lower}</th>
					<td><input type="checkbox" value="1" name="alias_lower"{ALIAS_LOWER}/></td>
				</tr>
				<tr>
					<th>{LANG.tags_alias}</th>
					<td><input type="checkbox" value="1" name="tags_alias"{TAGS_ALIAS}/></td>
				</tr>
				<tr>
					<th>{LANG.structure_image_upload}</th>
					<td>
					<select class="form-control" name="structure_upload" id="structure_upload">
						<!-- BEGIN: structure_upload -->
						<option value="{STRUCTURE_UPLOAD.key}"{STRUCTURE_UPLOAD.selected}>{STRUCTURE_UPLOAD.title}</option>
						<!-- END: structure_upload -->
					</select></td>
				</tr>
				<tr>
					<th>{LANG.imgpositiondefault}</th>
					<td>
						<select class="form-control" name="imgposition">
							<!-- BEGIN: looppos -->
							<option value="{id_imgposition}" {posl}>{title_imgposition}</option>
							<!-- END: looppos -->
						</select>
					</td>
				</tr>
				<tr>
					<th>{LANG.setting_auto_tags}</th>
					<td><input type="checkbox" value="1" name="auto_tags"{AUTO_TAGS}/></td>
				</tr>
				<tr>
					<th>{LANG.setting_tags_remind}</th>
					<td><input type="checkbox" value="1" name="tags_remind"{TAGS_REMIND}/></td>
				</tr>
			</tbody>
			<tfoot>
				<tr>
					<td class="text-center" colspan="2">
						<input class="btn btn-primary" type="submit" value="{LANG.save}" name="Submit1" />
						<input type="hidden" value="1" name="savesetting" />
					</td>
				</tr>
			</tfoot>
		</table>
	</div>
</form>
<script type="text/javascript">
	//<![CDATA[
	$(document).ready(function() {
		$("#structure_upload").select2();
	});

	$("input[name=selectimg]").click(function() {
		var area = "show_no_image";
		var type = "image";
		var path = "{PATH}";
		var currentpath = "{CURRENTPATH}";
		nv_open_browse("{NV_BASE_ADMINURL}index.php?" + nv_name_variable + "=upload&popup=1&area=" + area + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
		return false;
	});
	//]]>
</script>
<!-- BEGIN: admin_config_post -->
<form action="{FORM_ACTION}" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.group_content}</caption>
			<thead>
				<tr class="text-center">
					<th>{GLANG.mod_groups}</th>
					<th>{LANG.group_addcontent}</th>
					<th>{LANG.group_postcontent}</th>
					<th>{LANG.group_editcontent}</th>
					<th>{LANG.group_delcontent}</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td style="text-align: center;" colspan="5"><input class="btn btn-primary" type="submit" value="{LANG.save}" name="Submit1" /><input type="hidden" value="1" name="savepost" /></td>
				</tr>
			</tfoot>
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td><strong>{ROW.group_title}</strong><input type="hidden" value="{ROW.group_id}" name="array_group_id[]" /></td>
					<td class="text-center"><input type="checkbox" value="1" name="array_addcontent[{ROW.group_id}]"{ROW.addcontent}/></td>
					<td class="text-center"><input type="checkbox" value="1" name="array_postcontent[{ROW.group_id}]"{ROW.postcontent}/></td>
					<td class="text-center"><input type="checkbox" value="1" name="array_editcontent[{ROW.group_id}]"{ROW.editcontent}/></td>
					<td class="text-center"><input type="checkbox" value="1" name="array_delcontent[{ROW.group_id}]"{ROW.delcontent}/></td>
				</tr>
				<!-- END: loop -->
			</tbody>
		</table>
	</div>
</form>
<!-- END: admin_config_post -->
<!-- END: main -->