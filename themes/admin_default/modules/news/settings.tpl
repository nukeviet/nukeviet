<!-- BEGIN: main -->
<form class="form-inline" role="form" action="{NV_BASE_ADMINURL}index.php" method="post">
	<input type="hidden" name ="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
	<input type="hidden" name ="{NV_OP_VARIABLE}" value="{OP}" />
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<tfoot>
				<tr>
					<td class="text-center" colspan="2"><input class="btn btn-primary" type="submit" value="{LANG.save}" name="Submit1" /><input type="hidden" value="1" name="savesetting" /></td>
				</tr>
			</tfoot>
			<tbody>
				<tr>
					<td><strong>{LANG.setting_indexfile}</strong></td>
					<td>
					<select class="form-control" name="indexfile">
						<!-- BEGIN: indexfile -->
						<option value="{INDEXFILE.key}"{INDEXFILE.selected}>{INDEXFILE.title}</option>
						<!-- END: indexfile -->
					</select></td>
				</tr>
				<tr>
					<td><strong>{LANG.setting_homesite}</strong></td>
					<td><input class= "form-control" type="text" value="{DATA.homewidth}" name="homewidth" /><span class="text-middle"> x </span><input class= "form-control" type="text" value="{DATA.homeheight}" name="homeheight" /></td>
				</tr>
				<tr>
					<td><strong>{LANG.setting_thumbblock}</strong></td>
					<td><input class= "form-control" type="text" value="{DATA.blockwidth}" name="blockwidth" /><span class="text-middle"> x </span><input class= "form-control" type="text" value="{DATA.blockheight}" name="blockheight" /></td>
				</tr>
				<tr>
					<td><strong>{LANG.setting_imagefull}</strong></td>
					<td><input class= "form-control" type="text" value="{DATA.imagefull}" name="imagefull" /></td>
				</tr>
				<tr>
					<td><strong>{LANG.setting_per_page}</strong></td>
					<td>
					<select class="form-control" name="per_page">
						<!-- BEGIN: per_page -->
						<option value="{PER_PAGE.key}"{PER_PAGE.selected}>{PER_PAGE.title}</option>
						<!-- END: per_page -->
					</select></td>
				</tr>
				<tr>
					<td><strong>{LANG.setting_st_links}</strong></td>
					<td>
					<select class="form-control" name="st_links">
						<!-- BEGIN: st_links -->
						<option value="{ST_LINKS.key}"{ST_LINKS.selected}>{ST_LINKS.title}</option>
						<!-- END: st_links -->
					</select></td>
				</tr>
				<tr>
					<td><strong>{LANG.showtooltip}</strong></td>
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
					<td><strong>{LANG.showhometext}</strong></td>
					<td><input type="checkbox" value="1" name="showhometext"{SHOWHOMETEXT}/></td>
				</tr>
				<tr>
					<td><strong>{LANG.facebookAppID}</strong></td>
					<td><input class="form-control w150" name="facebookappid" value="{DATA.facebookappid}" type="text"/><span class="text-middle">{LANG.facebookAppIDNote}</span></td>
				</tr>
				<tr>
					<td><strong>{LANG.socialbutton}</strong></td>
					<td><input type="checkbox" value="1" name="socialbutton"{SOCIALBUTTON}/></td>
				</tr>
				<tr>
					<td><strong>{LANG.allowed_rating_point}</strong></td>
					<td>
					<select class="form-control" name="allowed_rating_point">
						<!-- BEGIN: allowed_rating_point -->
						<option value="{RATING_POINT.key}"{RATING_POINT.selected}>{RATING_POINT.title}</option>
						<!-- END: allowed_rating_point -->
					</select></td>
				</tr>
				<tr>
					<td><strong>{LANG.structure_image_upload}</strong></td>
					<td>
					<select class="form-control" name="structure_upload">
						<!-- BEGIN: structure_upload -->
						<option value="{STRUCTURE_UPLOAD.key}"{STRUCTURE_UPLOAD.selected}>{STRUCTURE_UPLOAD.title}</option>
						<!-- END: structure_upload -->
					</select></td>
				</tr>
				<tr>
					<td><strong>{LANG.show_no_image}</strong></td>
					<td><input class="form-control" name="show_no_image" id="show_no_image" value="{SHOW_NO_IMAGE}" style="width:340px;" type="text"/> <input value="{GLANG.browse_image}" name="selectimg" type="button" class="btn btn-info"/></td>
				</tr>
				<tr>
					<td><strong>{LANG.config_source}</strong></td>
					<td>
					<select class="form-control" name="config_source">
						<!-- BEGIN: config_source -->
						<option value="{CONFIG_SOURCE.key}"{CONFIG_SOURCE.selected}>{CONFIG_SOURCE.title}</option>
						<!-- END: config_source -->
					</select></td>
				</tr>
				<tr>
					<td><strong>{LANG.setting_copyright}</strong></td>
					<td><textarea class="form-control" style="width: 450px" name="copyright" id="copyright" cols="20" rows="4">{DATA.copyright}</textarea></td>
				</tr>
				<tr>
					<td><strong>{LANG.tags_alias}</strong></td>
					<td><input type="checkbox" value="1" name="tags_alias"{TAGS_ALIAS}/></td>
				</tr>
			</tbody>
		</table>
	</div>
</form>
<script type="text/javascript">
	//<![CDATA[
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