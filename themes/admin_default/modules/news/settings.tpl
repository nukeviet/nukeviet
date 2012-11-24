<!-- BEGIN: main -->
<form action="{NV_BASE_ADMINURL}index.php" method="post">
	<input type="hidden" name ="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
	<input type="hidden" name ="{NV_OP_VARIABLE}" value="{OP}" />
	<table class="tab1">
		<tfoot>
			<tr>
				<td style="text-align: center;" colspan="2">
				<input type="submit" value="{LANG.save}" name="Submit1" />
				<input type="hidden" value="1" name="savesetting" /></td>
			</tr>        
		</tfoot>
		<tbody>
			<tr>
				<td><strong>{LANG.setting_indexfile}</strong></td>
				<td>
					<select name="indexfile">
						<!-- BEGIN: indexfile -->
						<option value="{INDEXFILE.key}"{INDEXFILE.selected}>{INDEXFILE.title}</option>
						<!-- END: indexfile -->
					</select>
				</td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td><strong>{LANG.setting_homesite}</strong></td>
				<td>
					<input type="text" value="{DATA.homewidth}" style="width: 40px;" name="homewidth" /> x 
					<input type="text" value="{DATA.homeheight}" style="width: 40px;" name="homeheight" />
				</td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td><strong>{LANG.setting_thumbblock}</strong></td>
				<td>
					<input type="text" value="{DATA.blockwidth}" style="width: 40px;" name="blockwidth" /> x 
					<input type="text" value="{DATA.blockheight}" style="width: 40px;" name="blockheight" />
				</td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td><strong>{LANG.setting_imagefull}</strong></td>
				<td>
					<input type="text" value="{DATA.imagefull}" style="width: 50px;" name="imagefull" /> 
				</td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td><strong>{LANG.setting_per_page}</strong></td>
				<td>
					<select name="per_page">
						<!-- BEGIN: per_page -->
						<option value="{PER_PAGE.key}"{PER_PAGE.selected}>{PER_PAGE.title}</option>
						<!-- END: per_page -->
					</select>
				</td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td><strong>{LANG.setting_st_links}</strong></td>
				<td>
					<select name="st_links">
						<!-- BEGIN: st_links -->
						<option value="{ST_LINKS.key}"{ST_LINKS.selected}>{ST_LINKS.title}</option>
						<!-- END: st_links -->
					</select>
				</td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td><strong>{LANG.showhometext}</strong></td>
				<td><input type="checkbox" value="1" name="showhometext"{SHOWHOMETEXT}/></td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td><strong>{LANG.activecomm}</strong></td>
				<td><input type="checkbox" value="1" name="activecomm"{ACTIVECOMM}/></td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td><strong>{LANG.setting_auto_postcomm}</strong></td>
				<td><input type="checkbox" value="1" name="auto_postcomm"{AUTO_POSTCOMM}/></td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td><strong>{LANG.setting_setcomm}</strong></td>
				<td>
					<select name="setcomm">
						<!-- BEGIN: setcomm -->
						<option value="{SETCOMM.key}"{SETCOMM.selected}>{SETCOMM.title}</option>
						<!-- END: setcomm -->
					</select>
				</td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td><strong>{LANG.emailcomm}</strong></td>
				<td><input type="checkbox" value="1" name="emailcomm"{EMAILCOMM}/></td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td><strong>{LANG.structure_image_upload}</strong></td>
				<td>
					<select name="structure_upload">
						<!-- BEGIN: structure_upload -->
						<option value="{STRUCTURE_UPLOAD.key}"{STRUCTURE_UPLOAD.selected}>{STRUCTURE_UPLOAD.title}</option>
						<!-- END: structure_upload -->
					</select>
				</td>
			</tr>
		</tbody>
		<tbody>
			<tr class="second">
				<td><strong>{LANG.module_logo}</strong></td>
				<td>
					<input name="module_logo" id="module_logo" value="{MODULE_LOGO}" style="width:340px;" type="text"/>
					<input style="width:100px;" value="{GLANG.browse_image}" name="selectimg" type="button" />
				</td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td><strong>{LANG.config_source}</strong></td>
				<td>
					<select name="config_source">
						<!-- BEGIN: config_source -->
						<option value="{CONFIG_SOURCE.key}"{CONFIG_SOURCE.selected}>{CONFIG_SOURCE.title}</option>
						<!-- END: config_source -->
					</select>
				</td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td><strong>{LANG.setting_copyright}</strong></td>
				<td><textarea style="width: 450px" name="copyright" id="copyright" cols="20" rows="4">{DATA.copyright}</textarea></td>
			</tr>
		</tbody>
	</table>
</form>
<script type="text/javascript">
//<![CDATA[
$("input[name=selectimg]").click(function(){
	var area = "module_logo";
	var type= "image";
	var path= "{PATH}";
	var currentpath= "{CURRENTPATH}";
	nv_open_browse_file("{NV_BASE_ADMINURL}index.php?" + nv_name_variable + "=upload&popup=1&area=" + area + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", "850", "420","resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
	return false;
});
//]]>
</script>
<!-- BEGIN: admin_config_post -->
<form action="{FORM_ACTION}" method="post">
	<table class="tab1">
		<caption>{LANG.group_content}</caption>
		<thead>
			<tr align="center">
				<td>{GLANG.who_view3}</td>
				<td>{LANG.group_addcontent}</td>
				<td>{LANG.group_postcontent}</td>
				<td>{LANG.group_editcontent}</td>
				<td>{LANG.group_delcontent}</td>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td style="text-align: center;" colspan="5">
					<input type="submit" value="{LANG.save}" name="Submit1" />
					<input type="hidden" value="1" name="savepost" />
				</td>
			</tr>
		</tfoot>
		<!-- BEGIN: loop -->
		<tbody{ROW.class}>
			<tr>
				<td><strong>{ROW.array_post_2}</strong><input type="hidden" value="{ROW.pid}" name="array_pid[]" /></td>
				<td align="center"><input type="checkbox" value="1" name="array_addcontent[{ROW.pid}]"{ROW.addcontent}/></td>
				<td align="center"><input type="checkbox" value="1" name="array_postcontent[{ROW.pid}]"{ROW.postcontent}/></td>
				<td align="center"><input type="checkbox" value="1" name="array_editcontent[{ROW.pid}]"{ROW.editcontent}/></td>
				<td align="center"><input type="checkbox" value="1" name="array_delcontent[{ROW.pid}]"{ROW.delcontent}/></td>
			</tr>
		</tbody>
		<!-- END: loop -->
	</table>
</form>
<!-- END: admin_config_post -->
<!-- END: main -->