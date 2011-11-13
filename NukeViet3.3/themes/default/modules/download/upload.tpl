<!-- BEGIN: main -->
<div class="page_title" style="margin-bottom:5px">
    <div class="right2">
        <a href="{DOWNLOAD}">{LANG.download}</a>
    </div>
    {LANG.upload}
</div>
<!-- BEGIN: is_error -->
<div class="is_error">
    {ERROR}
</div>
<!-- END: is_error -->
<form id="uploadForm" name="uploadForm" action="{FORM_ACTION}" method="post" class="upload_form" enctype="multipart/form-data">
    <div class="upload_content">
        <dl class="clearfix">
            <dd class="fl">
                <label>
                    {LANG.file_title}
                </label>
            </dd>
            <dt class="fl">
                <input type="text" class="txt" name="upload_title" id="upload_title" value="{UPLOAD.title}" style="width:300px" maxlength="255" />
            </dt>
        </dl>
        <dl class="clearfix">
            <dd class="fl">
                <label>
                    {LANG.bycat2}
                </label>
            </dd>
            <dt class="fl">
                <select name="upload_catid" id="upload_catid">
                    <!-- BEGIN: catid -->
                    <option value="{LISTCATS.id}"{LISTCATS.selected}>{LISTCATS.name}</option>
                    <!-- END: catid -->
                </select>
            </dt>
        </dl>
        <dl class="clearfix">
            <dd class="fl">
                <label>
                    {LANG.author_name}
                </label>
            </dd>
            <dt class="fl">
                <input type="text" class="txt" name="upload_author_name" id="upload_author_name" value="{UPLOAD.author_name}" style="width:300px" maxlength="100" />
            </dt>
        </dl>
        <dl class="clearfix">
            <dd class="fl">
                <label>
                    {LANG.author_email}
                </label>
            </dd>
            <dt class="fl">
                <input type="text" class="txt" name="upload_author_email" id="upload_author_email_iavim" value="{UPLOAD.author_email}" style="width:300px" maxlength="60" />
            </dt>
        </dl>
        <dl class="clearfix">
            <dd class="fl">
                <label>
                    {LANG.author_url}
                </label>
            </dd>
            <dt class="fl">
                <input type="text" class="txt" name="upload_author_url" id="upload_author_url_iavim" value="{UPLOAD.author_url}" style="width:300px" maxlength="255" />
            </dt>
        </dl>
        <!-- BEGIN: is_upload_allow -->
        <dl class="clearfix">
            <dd class="fl" style="vertical-align:top">
                <label>
                    {LANG.upload_files}<br />
                    (<em>{LANG.upload_valid_ext_info}: {EXT_ALLOWED}</em>)
                </label>
            </dd>
            <dt class="fl">
                <input type="file" class="txt" name="upload_fileupload" id="upload_fileupload" />
            </dt>
        </dl>
        <!-- END: is_upload_allow -->
        <dl class="clearfix">
            <dd class="fl">
                <label>
                    {LANG.file_linkdirect}<br />
                    (<em>{LANG.upload_linkdirect_info}</em>)
                </label>
            </dd>
            <dt class="fl">
                <textarea name="upload_linkdirect" id="upload_linkdirect_iavim">{UPLOAD.linkdirect}</textarea>
            </dt>
        </dl>
        <dl class="clearfix">
            <dd class="fl">
                <label>
                    {LANG.filesize} (byte)
                </label>
            </dd>
            <dt class="fl">
                <input type="text" class="txt" name="upload_filesize" id="upload_filesize_iavim" value="{UPLOAD.filesize}" style="width:100px" maxlength="15" />
            </dt>
        </dl>
        <dl class="clearfix">
            <dd class="fl">
                <label>
                    {LANG.file_version}
                </label>
            </dd>
            <dt class="fl">
                <input type="text" class="txt" name="upload_version" id="upload_version" value="{UPLOAD.version}" style="width:100px" maxlength="20" />
            </dt>
        </dl>
        <dl class="clearfix">
            <dd class="fl">
                <label>
                    {LANG.fileimage}<br />
                    (<em>{LANG.upload_valid_ext_info}: jpg, gif, png</em>)
                </label>
            </dd>
            <dt class="fl">
                <input type="file" class="txt" name="upload_fileimage" id="upload_fileimage" />
            </dt>
        </dl>
        <dl class="clearfix">
            <dd class="fl">
                <label>
                    {LANG.copyright}
                </label>
            </dd>
            <dt class="fl">
                <input type="text" class="txt" name="upload_copyright" id="upload_copyright" value="{UPLOAD.copyright}" style="width:300px;" maxlength="255" />
            </dt>
        </dl>
        <dl class="clearfix">
            <dd class="fl">
                <label>
                    {LANG.file_introtext}
                </label>
            </dd>
            <dt class="fl">
                <textarea name="upload_introtext" id="upload_introtext">{UPLOAD.introtext}</textarea>
            </dt>
        </dl>
        <dl class="clearfix">
            <dd class="fl">
                <label>
                    {LANG.file_description}
                </label>
            </dd>
            <dt class="fl">
                <textarea name="upload_description" id="upload_description">{UPLOAD.description}</textarea>
            </dt>
        </dl>
        <br />
        <dl class="clearfix">
            <dd class="fl">
                <label>
                    {LANG.file_username}
                </label>
            </dd>
            <dt class="fl">
                <input type="text" class="txt" name="upload_user_name" id="upload_user_name" value="{UPLOAD.user_name}"{UPLOAD.disabled} maxlength="100" />
            </dt>
        </dl>
        <dl class="clearfix">
            <dd class="fl">
                {LANG.file_upload_captcha}
            </dd>
            <dt class="fl">
                <img  style="vertical-align: middle" height="22" name="upload_vimg" id="upload_vimg" src="{NV_BASE_SITEURL}index.php?scaptcha=captcha" title="{GLANG.captcha}" alt="{GLANG.captcha}" />
                <img style="vertical-align: middle" alt="{GLANG.captcharefresh}" title="{GLANG.captcharefresh}" src="{NV_BASE_SITEURL}images/refresh.png" width="16" height="16" class="refresh" onclick="nv_change_captcha('upload_vimg','upload_seccode_iavim');" />
            </dt>
        </dl>
        <dl class="clearfix">
            <dd class="fl">
                <label>
                    {LANG.file_upload_captcha2}
                </label>
            </dd>
            <dt class="fl" style="width:200px">
                <input style="width:80px;vertical-align: middle" type="text" value="" name="upload_seccode" id="upload_seccode_iavim" maxlength="{CAPTCHA_MAXLENGTH}" />
            </dt>
        </dl>
    </div>
    <input type="hidden" name="addfile" value="{UPLOAD.addfile}" />
    <input type="submit" name="submit" value="{LANG.upload}" />
</form>

<!-- END: main -->