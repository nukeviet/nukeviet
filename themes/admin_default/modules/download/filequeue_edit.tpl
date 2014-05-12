<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="quote">
	<blockquote class="error">
		<p>
			<span>{ERROR}</span>
		</p></blockquote>
</div>
<!-- END: error -->
<form action="{FORM_ACTION}" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<tbody>
				<tr>
					<td class="w250"> {LANG.file_title} </td>
					<td><input class="w300 form-control" value="{DATA.title}" name="title" id="title" /></td>
				</tr>
				<tr>
					<td> {LANG.category_cat_parent} </td>
					<td>
					<select name="catid" class="form-control w200">
						<!-- BEGIN: catid -->
						<option value="{LISTCATS.id}"{LISTCATS.selected}>{LISTCATS.name}</option>
						<!-- END: catid -->
					</select></td>
				</tr>
				<tr>
					<td> {LANG.file_author_name} </td>
					<td><input class="w300 form-control" value="{DATA.author_name}" name="author_name" id="author_name" maxlength="100" /></td>
				</tr>
				<tr>
					<td> {LANG.file_author_email} </td>
					<td><input class="w300 form-control" value="{DATA.author_email}" name="author_email" id="author_email" maxlength="60" /></td>
				</tr>
				<tr>
					<td> {LANG.file_author_homepage} </td>
					<td><input class="w300 form-control pull-left" value="{DATA.author_url}" name="author_url" id="author_url" maxlength="255" style="margin-right: 5px" /> <input type="button" class="btn btn-info pull-left" style="margin-right: 5px" value="{LANG.file_checkUrl}" id="check_author_url" onclick="nv_checkfile('author_url',0, 'check_author_url');" /><input type="button" class="btn btn-info pull-left" value="{LANG.file_gourl}" id="go_author_url" onclick="nv_gourl('author_url',0, 'go_author_url');" /></td>
				</tr>
				<tr>
					<td style="vertical-align:top"> {LANG.file_myfile} </td>
					<td>
					<!-- BEGIN: fileupload -->
					<input class="w300 form-control pull-left" value="{FILEUPLOAD.value}" name="fileupload[]" id="fileupload{FILEUPLOAD.key}" maxlength="255" disabled="disabled" style="margin-right: 5px" /> <input type="button" style="margin-right: 5px" class="btn btn-info pull-left" value="{LANG.file_checkUrl}" id= "check_fileupload{FILEUPLOAD.key}" onclick="nv_checkfile('fileupload{FILEUPLOAD.key}',1, 'check_fileupload{FILEUPLOAD.key}');" /> <input type="button" class="btn btn-info pull-left" value="{LANG.file_gourl}" id= "go_fileupload{FILEUPLOAD.key}" onclick="nv_gourl('fileupload{FILEUPLOAD.key}',1, 'go_fileupload{FILEUPLOAD.key}');" />
					<br />
					<!-- END: fileupload -->
					<br />
					<div id="fileupload2_items">
						<!-- BEGIN: if_fileupload -->
						<div style="padding-top:10px;">
							{LANG.add_fileupload}:
						</div>
						<!-- END: if_fileupload -->
						<!-- BEGIN: fileupload2 -->
						<input readonly="readonly" class="w300 form-control pull-left" value="{FILEUPLOAD2.value}" name="fileupload2[]" id="fileupload2_{FILEUPLOAD2.key}" maxlength="255" style="margin-right: 5px" />&nbsp; <input type="button" style="margin-right: 5px" class="btn btn-info pull-left" value="{LANG.file_selectfile}" name="selectfile" onclick="nv_open_browse( '{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}=upload&popup=1&area=fileupload2_{FILEUPLOAD2.key}&path={FILES_DIR}&type=file', 'NVImg', 850, 420, 'resizable=no,scrollbars=no,toolbar=no,location=no,status=no' );return false;" />&nbsp; <input type="button" class="btn btn-info pull-left" style="margin-right: 5px" value="{LANG.file_checkUrl}" id= "check_fileupload2_{FILEUPLOAD2.key}" onclick="nv_checkfile('fileupload2_{FILEUPLOAD2.key}',1, 'check_fileupload2_{FILEUPLOAD2.key}');" />&nbsp; <input type="button" class="btn btn-info pull-left" style="margin-right: 5px" value="{LANG.file_gourl}" id= "go_fileupload2_{FILEUPLOAD2.key}" onclick="nv_gourl('fileupload2_{FILEUPLOAD2.key}',1, 'go_fileupload2_{FILEUPLOAD2.key}');" />
						<br />
						<!-- END: fileupload2 -->
					</div>
					<script type="text/javascript">
						var file_items = '{DATA.fileupload2_num}';
						var file_selectfile = '{LANG.file_selectfile}';
						var nv_base_adminurl = '{NV_BASE_ADMINURL}';
						var file_dir = '{FILES_DIR}';
						var file_checkUrl = '{LANG.file_checkUrl}';
						var file_gourl = '{LANG.file_gourl}';
					</script>
					<br /><br /><input type="button" value="{LANG.add_file_items}" onclick="nv_file_additem2();" /> ({LANG.add_file_items_note}) </td>
				</tr>
				<tr>
					<td style="vertical-align:top"> {LANG.file_linkdirect}
					<br />
					(<em>{LANG.file_linkdirect_note}</em>) </td>
					<td>
					<div id="linkdirect_items">
						<!-- BEGIN: linkdirect --><textarea name="linkdirect[]" id="linkdirect{LINKDIRECT.key}" style="width:300px;height:150px" class="form-control pull-left">{LINKDIRECT.value}</textarea>
						&nbsp;<input type="button" class="btn btn-info" value="{LANG.file_checkUrl}" id="check_linkdirect{LINKDIRECT.key}" onclick="nv_checkfile('linkdirect{LINKDIRECT.key}',0, 'check_linkdirect{LINKDIRECT.key}');" />
						<br />
						<!-- END: linkdirect -->
					</div>
					<div class="clearfix">&nbsp;</div>
					<br />
					<script type="text/javascript">
						var linkdirect_items = '{DATA.linkdirect_num}';
					</script><input type="button" value="{LANG.add_linkdirect_items}" onclick="nv_linkdirect_additem();" /> ({LANG.add_linkdirect_items_note}) </td>
				</tr>
				<tr>
					<td> {LANG.file_size} </td>
					<td><input class="w100 form-control" value="{DATA.filesize}" name="filesize" id="filesize" maxlength="11" /> {LANG.config_maxfilebyte} </td>
				</tr>
				<tr>
					<td> {LANG.file_version} </td>
					<td><input class="w300 form-control" value="{DATA.version}" name="version" id="version" maxlength="20" /></td>
				</tr>
				<tr>
					<td style="vertical-align:top"> {LANG.file_image} </td>
					<td>
					<!-- BEGIN: fileimage -->
					<input class="w300 form-control pull-left" value="{DATA.fileimage}" name="fileimage" id="fileimage" maxlength="255" disabled="disabled" /> <input type="button" class="btn btn-info pull-left" value="{LANG.file_checkUrl}" id= "check_fileimage" onclick="nv_checkfile('fileimage',1, 'check_fileimage');" /> <input type="button" value="{LANG.file_gourl}" id= "go_fileimage" onclick="nv_gourl('fileimage',1, 'go_fileimage');" />
					<br />
					<!-- BEGIN: if_fileimage -->
					<div style="padding-top:10px">
						{LANG.add_new_img}:
					</div>
					<!-- END: if_fileimage -->
					<!-- END: fileimage -->
					<input class="w300 form-control pull-left" value="{DATA.fileimage2}" name="fileimage2" id="fileimage2" maxlength="255" style="margin-right: 5px" /> <input type="button" class="btn btn-info" style="margin-right: 5px" value="{LANG.file_selectfile}" name="selectimg" /> <input type="button" class="btn btn-info" style="margin-right: 5px" value="{LANG.file_checkUrl}" id="check_fileimage2" onclick="nv_checkfile('fileimage2',1, 'check_fileimage2');" /> <input type="button" class="btn btn-info" style="margin-right: 5px" value="{LANG.file_gourl}" id= "go_fileimage2" onclick="nv_gourl('fileimage2',1, 'go_fileimage2');" /></td>
				</tr>
				<tr>
					<td> {LANG.intro_title} </td>
					<td><textarea name="introtext" class="form-control" style="width:300px;height:150px">{DATA.introtext}</textarea></td>
				</tr>
				<tr>
					<td> {LANG.file_copyright} </td>
					<td><input class="w300 form-control" value="{DATA.copyright}" name="copyright" id="copyright" maxlength="20" /></td>
				</tr>
				<tr>
					<td> {LANG.file_whocomment} </td>
					<td>
					<!-- BEGIN: groups_comment -->
					<input name="groups_comment[]" value="{GROUPS_COMMENT.key}" type="checkbox"{GROUPS_COMMENT.checked} /> {GROUPS_COMMENT.title}
					<br />
					<!-- END: groups_comment -->
					</td>
				</tr>
				<tr>
					<td style="vertical-align:top"> {LANG.groups_view} </td>
					<td>
					<!-- BEGIN: groups_view -->
					<input name="groups_view[]" value="{GROUPS_VIEW.key}" type="checkbox"{GROUPS_VIEW.checked} /> {GROUPS_VIEW.title}
					<br />
					<!-- END: groups_view -->
					</td>
				</tr>
				<tr>
					<td style="vertical-align:top"> {LANG.groups_download} </td>
					<td>
					<!-- BEGIN: groups_download -->
					<input name="groups_download[]" value="{GROUPS_DOWNLOAD.key}" type="checkbox"{GROUPS_DOWNLOAD.checked} /> {GROUPS_DOWNLOAD.title}
					<br />
					<!-- END: groups_download -->
					</td>
				</tr>
			</tbody>
		</table>
	</div>

	<div style="textarea-align:center;padding-top:15px">
		{LANG.file_description}
		<br />
		{DATA.description}
	</div>

	<div style="textarea-align:center;padding-top:15px">
		<input type="submit" name="submit" value="{LANG.confirm}" class="btn btn-primary" />
		<input type="button" value="{LANG.download_filequeue_del}" name="delfile" class="btn btn-danger" />
	</div>
</form>
<script type="text/javascript">
	$("input[name=selectimg]").click(function() {
		nv_open_browse("{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}=upload&popup=1&area=fileimage2&path={IMG_DIR}&type=image", "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
		return false;
	});
	$("input[name=delfile]").click(function() {
		nv_filequeue_del('{DATA.id}');
	});
</script>
<!-- END: main -->