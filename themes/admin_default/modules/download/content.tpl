<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<form action="{FORM_ACTION}" method="post" class="confirm-reload">
	<div class="row">
		<div class="col-sm-12 col-md-9">
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover">
					<tbody>
						<tr>
							<td class="w250"> {LANG.file_title} </td>
							<td><input class="w300 form-control" type="text" value="{DATA.title}" name="title" id="title"/></td>
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
							<td><input class="w300 form-control" type="text" value="{DATA.author_name}" name="author_name" id="author_name" maxlength="100" /></td>
						</tr>
						<tr>
							<td> {LANG.file_author_email} </td>
							<td><input class="w300 form-control" type="text" value="{DATA.author_email}" name="author_email" id="author_email" maxlength="60" /></td>
						</tr>
						<tr>
							<td> {LANG.file_author_homepage} </td>
							<td>
								<input class="w300 form-control pull-left" style="margin-right: 5px" type="text" value="{DATA.author_url}" name="author_url" id="author_url" maxlength="255" />
								<input class="btn btn-info pull-left" style="margin-right: 5px" type="button" value="{LANG.file_checkUrl}" id="check_author_url" onclick="nv_checkfile('author_url',0, 'check_author_url');" />
								<input class="btn btn-info pull-left" type="button" value="{LANG.file_gourl}" id="go_author_url" onclick="nv_gourl('author_url',0, 'go_author_url');" /></td>
						</tr>
						<tr>
							<td> {LANG.file_image} </td>
							<td>
								<input class="w300 form-control pull-left" type="text" style="margin-right: 5px" value="{DATA.fileimage}" name="fileimage" id="fileimage" maxlength="255" />
								<input type="button" class="btn btn-info pull-left" style="margin-right: 5px" value="{LANG.file_selectfile}" name="selectimg" />
								<input type="button" class="btn btn-info pull-left" style="margin-right: 5px" value="{LANG.file_checkUrl}" id="check_fileimage" onclick="nv_checkfile('fileimage',1, 'check_fileimage');" />
								<input type="button" class="btn btn-info pull-left" value="{LANG.file_gourl}" id= "go_fileimage" onclick="nv_gourl('fileimage',1, 'go_fileimage');" />
							</td>
						</tr>
						<tr>
							<td class="top"> {LANG.intro_title} </td>
							<td><textarea name="introtext" style="width:500px;height:100px" class="form-control">{DATA.introtext}</textarea></td>
						</tr>
						<tr>
							<td colspan="2">
								{LANG.file_description}
								<br />
								{DATA.description}
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="col-sm-12 col-md-3">
			<div class="row">
				<div class="col-sm-6 col-md-12">
					<label>{LANG.groups_view}</label>
					<br />
					<!-- BEGIN: groups_view -->
					<input name="groups_view[]" value="{GROUPS_VIEW.key}" type="checkbox"{GROUPS_VIEW.checked} /> {GROUPS_VIEW.title}
					<br />
					<!-- END: groups_view -->

					<br />
					<label>{LANG.groups_download}</label>
					<br />
					<!-- BEGIN: groups_download -->
					<input name="groups_download[]" value="{GROUPS_DOWNLOAD.key}" type="checkbox"{GROUPS_DOWNLOAD.checked} /> {GROUPS_DOWNLOAD.title}
					<br />
					<!-- END: groups_download -->
					<br />
				</div>
				<div class="col-sm-6 col-md-12">
					<label>{LANG.file_whocomment}</label>
					<br />
					<!-- BEGIN: groups_comment -->
					<input name="groups_comment[]" value="{GROUPS_COMMENT.key}" type="checkbox"{GROUPS_COMMENT.checked} /> {GROUPS_COMMENT.title}
					<br />
					<!-- END: groups_comment -->
				</div>
			</div>
		</div>
	</div>
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<tbody>
				<tr>
					<td class="w250" style="vertical-align:top"> {LANG.file_myfile} </td>
					<td>
						<div id="fileupload_items">
							<!-- BEGIN: fileupload -->
							<div id="fileupload_item_{FILEUPLOAD.key}">
								<input readonly="readonly" class="w300 form-control pull-left" type="text" value="{FILEUPLOAD.value}" name="fileupload[]" id="fileupload{FILEUPLOAD.key}" maxlength="255" />
								&nbsp; <input class="btn btn-info" type="button" value="{LANG.file_selectfile}" name="selectfile" onclick="nv_open_browse( '{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}=upload&popup=1&area=fileupload{FILEUPLOAD.key}&path={FILES_DIR}&type=file', 'NVImg', 850, 420, 'resizable=no,scrollbars=no,toolbar=no,location=no,status=no' );return false;" />
								&nbsp;<input class="btn btn-info" type="button" value="{LANG.file_checkUrl}" id= "check_fileupload{FILEUPLOAD.key}" onclick="nv_checkfile('fileupload{FILEUPLOAD.key}',1, 'check_fileupload{FILEUPLOAD.key}');" />
								&nbsp; <input class="btn btn-info" type="button" value="{LANG.file_gourl}" id= "go_fileupload{FILEUPLOAD.key}" onclick="nv_gourl('fileupload{FILEUPLOAD.key}', 1, 'go_fileupload{FILEUPLOAD.key}');" />
								&nbsp;<input class="btn btn-info" type="button" onclick="nv_delurl( {DATA.id}, {FILEUPLOAD.key} ); " value="{LANG.file_delurl}">
							</div>
							<!-- END: fileupload -->
						</div>
						<script type="text/javascript">
							var file_items = '{DATA.fileupload_num}';
							var file_selectfile = '{LANG.file_selectfile}';
							var nv_base_adminurl = '{NV_BASE_ADMINURL}';
							var file_dir = '{FILES_DIR}';
							var file_checkUrl = '{LANG.file_checkUrl}';
							var file_gourl = '{LANG.file_gourl}';
							var file_delurl = '{LANG.file_delurl}';
						</script>
						<p style="margin-top: 10px"><input class="btn btn-default" type="button" value="{LANG.add_file_items}" onclick="nv_file_additem({DATA.id});" /> ({LANG.add_file_items_note})</p>
					</td>
				</tr>
				<tr>
					<td style="vertical-align:top"> {LANG.file_linkdirect}
					<br />
					(<em>{LANG.file_linkdirect_note}</em>) </td>
					<td>
						<div id="linkdirect_items">
							<!-- BEGIN: linkdirect --><textarea name="linkdirect[]" id="linkdirect{LINKDIRECT.key}" style="width:500px;height:100px" class="form-control pull-left">{LINKDIRECT.value}</textarea>
							&nbsp; &nbsp;<input type="button" class="btn btn-info pull-left" value="{LANG.file_checkUrl}" id="check_linkdirect{LINKDIRECT.key}" onclick="nv_checkfile('linkdirect{LINKDIRECT.key}',0, 'check_linkdirect{LINKDIRECT.key}');" />
							<!-- END: linkdirect -->
						</div>
						<script type="text/javascript">
							var linkdirect_items = '{DATA.linkdirect_num}';
						</script>
						<div class="clearfix">&nbsp;</div>
						<p style="margin-top: 10px"><input type="button" class="btn btn-default" value="{LANG.add_linkdirect_items}" onclick="nv_linkdirect_additem();" /> ({LANG.add_linkdirect_items_note})</p>
					</td>
				</tr>
				<tr>
					<td> {LANG.file_size} </td>
					<td><input type="text" class="w100 form-control pull-left" value="{DATA.filesize}" name="filesize" id="filesize" maxlength="11" /><span class="text-middle"> {LANG.config_maxfilemb} </span></td>
				</tr>
				<tr>
					<td> {LANG.file_version} </td>
					<td><input class="w300 form-control" type="text" value="{DATA.version}" name="version" id="version" maxlength="20" /></td>
				</tr>
				<tr>
					<td> {LANG.file_copyright} </td>
					<td><input class="w300 form-control" type="text" value="{DATA.copyright}" name="copyright" id="copyright" maxlength="20" /></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div style="text-align:center;padding-top:15px">
		<!-- BEGIN: is_del_report -->
		<input name="is_del_report" value="1" type="checkbox"{DATA.is_del_report} /> {LANG.report_delete} &nbsp;&nbsp;
		<!-- END: is_del_report -->
		<input type="submit" name="submit" value="{LANG.confirm}" class="btn btn-primary" />
	</div>
</form>
<script type="text/javascript">
	$("input[name=selectimg]").click(function() {
		nv_open_browse("{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}=upload&popup=1&area=fileimage&path={IMG_DIR}&type=image", "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
		return false;
	});
</script>
<!-- END: main -->