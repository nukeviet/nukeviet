<!-- BEGIN: main -->
<div class="page_title">
	<div class="right2">
		<a href="{DOWNLOAD}">{LANG.download}</a>
	</div>
	{LANG.upload}
</div>
<!-- BEGIN: is_error -->
<div class="alert alert-danger">
	{ERROR}
</div>
<!-- END: is_error -->

<form id="uploadForm" name="uploadForm" action="{FORM_ACTION}" method="post" enctype="multipart/form-data" class="form-horizontal" role="form">
	<div class="form-group">
		<label class="col-sm-2 control-label">{LANG.file_title}</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" name="upload_title" id="upload_title" value="{UPLOAD.title}">
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-sm-2 control-label">{LANG.bycat2}</label>
		<div class="col-sm-10">
			<select name="upload_catid" id="upload_catid" class="form-control">
				<!-- BEGIN: catid -->
				<option value="{LISTCATS.id}"{LISTCATS.selected}>{LISTCATS.name}</option>
				<!-- END: catid -->
			</select>
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-sm-2 control-label">{LANG.author_name}</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" name="upload_author_name" id="upload_author_name" value="{UPLOAD.author_name}" maxlength="100">
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-sm-2 control-label">{LANG.author_email}</label>
		<div class="col-sm-10">
			<input type="email" class="form-control" name="upload_author_email" id="upload_author_email_iavim" value="{UPLOAD.author_email}" maxlength="60">
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-sm-2 control-label">{LANG.author_url}</label>
		<div class="col-sm-10">
			<input type="url" class="form-control" name="upload_author_url" id="upload_author_url_iavim" value="{UPLOAD.author_url}" maxlength="255">
		</div>
	</div>

	<!-- BEGIN: is_upload_allow -->
	<div class="form-group">
		<label class="col-sm-2 control-label">{LANG.upload_files}</label>
		<div class="col-lg-6">
			<div class="input-group">
				<input type="text" class="form-control" id="file_name" disabled>
				<span class="input-group-btn">
				<button class="btn btn-default" onclick="$('#upload_fileupload').click();" type="button"><em class="fa fa-folder-open-o fa-fix">&nbsp;</em> {LANG.file_selectfile}</button>
				</span>
			</div>
			<p class="help-block">{LANG.upload_valid_ext_info}: {EXT_ALLOWED}</p>
			<input type="file" name="upload_fileupload" id="upload_fileupload" style="visibility: hidden;" />
  		</div>
	</div>
	<!-- END: is_upload_allow -->
	
	<div class="form-group">
		<label class="col-sm-2 control-label">{LANG.file_linkdirect}</label>
		<div class="col-sm-10">
			<textarea name="upload_linkdirect" id="upload_linkdirect_iavim" class="form-control" rows="3">{UPLOAD.linkdirect}</textarea>
			<p class="help-block">{LANG.upload_linkdirect_info}</p>
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-sm-2 control-label">{LANG.filesize} (byte)</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" name="upload_filesize" id="upload_filesize_iavim" value="{UPLOAD.filesize}" maxlength="15">
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-sm-2 control-label">{LANG.file_version}</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" name="upload_version" id="upload_version" value="{UPLOAD.version}" maxlength="20">
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-sm-2 control-label">{LANG.fileimage}</label>
		<div class="col-lg-6">
			<div class="input-group">
				<input type="text" class="form-control" id="photo_name" disabled>
				<span class="input-group-btn">
				<button class="btn btn-default" onclick="$('#upload_fileimage').click();" type="button"><em class="fa fa-folder-open-o fa-fix">&nbsp;</em> {LANG.file_selectfile}</button>
				</span>
			</div>
			<p class="help-block">{LANG.upload_valid_ext_info}: jpg, gif, png</p>
			<input type="file" name="upload_fileimage" id="upload_fileimage" style="visibility: hidden;" />
  		</div>
	</div>
	
	<div class="form-group">
		<label class="col-sm-2 control-label">{LANG.copyright}</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" name="upload_copyright" id="upload_copyright" value="{UPLOAD.copyright}" maxlength="255">
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-sm-2 control-label">{LANG.file_introtext}</label>
		<div class="col-sm-10">
			<textarea name="upload_introtext" id="upload_introtext" class="form-control" rows="3">{UPLOAD.introtext}</textarea>
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-sm-2 control-label">{LANG.file_description}</label>
		<div class="col-sm-10">
			<textarea name="upload_description" id="upload_description" class="form-control" rows="3">{UPLOAD.description}</textarea>
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-sm-2 control-label">{LANG.file_username}</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" name="upload_user_name" id="upload_user_name" value="{UPLOAD.user_name}" {UPLOAD.disabled} maxlength="100">
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-sm-2 control-label">{LANG.file_upload_captcha}</label>
		<div class="col-sm-10">
			<img style="vertical-align: middle" height="22" name="upload_vimg" id="upload_vimg" src="{NV_BASE_SITEURL}index.php?scaptcha=captcha" alt="{GLANG.captcha}" />
			<img style="vertical-align: middle" alt="{GLANG.captcharefresh}" src="{NV_BASE_SITEURL}images/refresh.png" width="16" height="16" class="refresh" onclick="nv_change_captcha('upload_vimg','upload_seccode_iavim');" />
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-sm-2 control-label">{LANG.file_upload_captcha2}</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" name="upload_seccode" id="upload_seccode_iavim" value="" maxlength="{CAPTCHA_MAXLENGTH}">
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-sm-2 control-label">&nbsp;</label>
		<div class="col-sm-10">
			<input type="hidden" name="addfile" value="{UPLOAD.addfile}" />
			<input class="btn btn-primary" type="submit" name="submit" value="{LANG.upload}" />
		</div>
	</div>
</form>

<script type="text/javascript">
	$('#upload_fileupload').change(function(){
	     $('#file_name').val($(this).val().match(/[-_\w]+[.][\w]+$/i)[0]);
	});
	
	$('#upload_fileimage').change(function(){
	     $('#photo_name').val($(this).val().match(/[-_\w]+[.][\w]+$/i)[0]);
	});
</script>
<!-- END: main -->