<!-- BEGIN: main -->
<div class="download-header m-bottom">
	<h4>{LANG.upload}</h4>
</div>
<!-- BEGIN: is_error -->
<div class="is_error">
	{ERROR}
</div>
<!-- END: is_error -->
<form id="uploadForm" name="uploadForm" action="{FORM_ACTION}" method="post" class="box-border content-box bgray" enctype="multipart/form-data">
	<div class="content-box upload-form box-border m-bottom">
		<dl class="clearfix">
			<dt class="fl">
				<label> {LANG.file_title} </label>
			</dt>
			<dd class="fl">
				<input type="text" class="input" name="upload_title" id="upload_title" value="{UPLOAD.title}" maxlength="255" />
			</dd>
		</dl>
		<dl class="clearfix">
			<dt class="fl">
				<label> {LANG.bycat2} </label>
			</dt>
			<dd class="fl">
				<select name="upload_catid" id="upload_catid">
					<!-- BEGIN: catid -->
					<option value="{LISTCATS.id}"{LISTCATS.selected}>{LISTCATS.name}</option>
					<!-- END: catid -->
				</select>
			</dd>
		</dl>
		<dl class="clearfix">
			<dt class="fl">
				<label> {LANG.author_name} </label>
			</dt>
			<dd class="fl">
				<input type="text" class="input" name="upload_author_name" id="upload_author_name" value="{UPLOAD.author_name}" style="width:300px" maxlength="100" />
			</dd>
		</dl>
		<dl class="clearfix">
			<dt class="fl">
				<label> {LANG.author_email} </label>
			</dt>
			<dd class="fl">
				<input type="text" class="input" name="upload_author_email" id="upload_author_email_iavim" value="{UPLOAD.author_email}" style="width:300px" maxlength="60" />
			</dd>
		</dl>
		<dl class="clearfix">
			<dt class="fl">
				<label> {LANG.author_url} </label>
			</dt>
			<dd class="fl">
				<input type="text" class="input" name="upload_author_url" id="upload_author_url_iavim" value="{UPLOAD.author_url}" style="width:300px" maxlength="255" />
			</dd>
		</dl>
		<!-- BEGIN: is_upload_allow -->
		<dl class="clearfix">
			<dt class="fl" style="vertical-align:top">
				<label> {LANG.upload_files}
					<br />
					(<em>{LANG.upload_valid_ext_info}: {EXT_ALLOWED}</em>) </label>
			</dt>
			<dd class="fl">
				<input type="file" class="txt" name="upload_fileupload" id="upload_fileupload" />
			</dd>
		</dl>
		<!-- END: is_upload_allow -->
		<dl class="clearfix">
			<dt class="fl">
				<label> {LANG.file_linkdirect}
					<br />
					(<em>{LANG.upload_linkdirect_info}</em>) </label>
			</dt>
			<dd class="fl"><textarea class="input" name="upload_linkdirect" id="upload_linkdirect_iavim">{UPLOAD.linkdirect}</textarea>
			</dd>
		</dl>
		<dl class="clearfix">
			<dt class="fl">
				<label> {LANG.filesize} (byte) </label>
			</dt>
			<dd class="fl">
				<input type="text" class="input" name="upload_filesize" id="upload_filesize_iavim" value="{UPLOAD.filesize}" style="width:100px" maxlength="15" />
			</dd>
		</dl>
		<dl class="clearfix">
			<dt class="fl">
				<label> {LANG.file_version} </label>
			</dt>
			<dd class="fl">
				<input type="text" class="input" name="upload_version" id="upload_version" value="{UPLOAD.version}" style="width:100px" maxlength="20" />
			</dd>
		</dl>
		<dl class="clearfix">
			<dt class="fl">
				<label> {LANG.fileimage}
					<br />
					(<em>{LANG.upload_valid_ext_info}: jpg, gif, png</em>) </label>
			</dt>
			<dd class="fl">
				<input type="file" class="txt" name="upload_fileimage" id="upload_fileimage" />
			</dd>
		</dl>
		<dl class="clearfix">
			<dt class="fl">
				<label> {LANG.copyright} </label>
			</dt>
			<dd class="fl">
				<input type="text" class="input" name="upload_copyright" id="upload_copyright" value="{UPLOAD.copyright}" style="width:300px;" maxlength="255" />
			</dd>
		</dl>
		<dl class="clearfix">
			<dt class="fl">
				<label> {LANG.file_introtext} </label>
			</dt>
			<dd class="fl"><textarea class="input" name="upload_introtext" id="upload_introtext">{UPLOAD.introtext}</textarea>
			</dd>
		</dl>
		<dl class="clearfix">
			<dt class="fl">
				<label> {LANG.file_description} </label>
			</dt>
			<dd class="fl"><textarea class="input" name="upload_description" id="upload_description">{UPLOAD.description}</textarea>
			</dd>
		</dl>
		<br />
		<dl class="clearfix">
			<dt class="fl">
				<label> {LANG.file_username} </label>
			</dt>
			<dd class="fl">
				<input type="text" class="input" name="upload_user_name" id="upload_user_name" value="{UPLOAD.user_name}"{UPLOAD.disabled} maxlength="100" />
			</dd>
		</dl>
		<dl class="clearfix">
			<dt class="fl">
				{LANG.file_upload_captcha}
			</dt>
			<dd class="fl">
				<img  style="vertical-align: middle" height="22" name="upload_vimg" id="upload_vimg" src="{NV_BASE_SITEURL}index.php?scaptcha=captcha" alt="{GLANG.captcha}" />
				<img style="vertical-align: middle" alt="{GLANG.captcharefresh}" src="{NV_BASE_SITEURL}images/refresh.png" width="16" height="16" class="refresh" onclick="nv_change_captcha('upload_vimg','upload_seccode_iavim');" />
			</dd>
		</dl>
		<dl class="clearfix">
			<dt class="fl">
				<label> {LANG.file_upload_captcha2} </label>
			</dt>
			<dd class="fl" style="width:200px">
				<input style="width:80px;vertical-align: middle" type="text" value="" name="upload_seccode" id="upload_seccode_iavim" maxlength="{CAPTCHA_MAXLENGTH}" />
			</dd>
		</dl>
	</div>
	<div class="aright">
		<input type="hidden" name="addfile" value="{UPLOAD.addfile}" />
		<input type="submit" name="submit" value="{LANG.upload}" class="button" />
	</div>
</form>

<!-- END: main -->