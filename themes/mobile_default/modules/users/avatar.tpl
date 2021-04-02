<!-- BEGIN: main -->
<link  type="text/css"href="{NV_STATIC_URL}{NV_ASSETS_DIR}/js/cropper/cropper.min.css" rel="stylesheet" />
<script type="text/javascript" src="{NV_STATIC_URL}{NV_ASSETS_DIR}/js/cropper/cropper.min.js"></script>
<div class="users-av-wraper">
    <form id="upload-form" method="post" enctype="multipart/form-data" action="{NV_AVATAR_UPLOAD}">
        <div class="form-group">
            <div class="users-avupload">
                <div title="{LANG.avatar_select_img}" id="upload_icon" class="upload-button">
                    <div class="text-center">
                        <span><em class="fa fa-upload"></em></span>
        			</div>
                </div>
                <div class="img-area"><img id="preview" style="display: none;"/></div>
            </div>
        </div>
        <div class="form-group">
            <h2 class="margin-bottom-lg">{LANG.change_avatar}</h2>
            <div class="guide" id="guide">
                <div class="margin-bottom"><strong>{LANG.avatar_guide}:</strong></div>
                <div>- {LANG.avatar_chosen}</div>
                <div>- {LANG.avatar_upload}</div>
            </div>
            <div style="display:none" id="uploadInfo" class="margin-bottom">
				<div>- {LANG.avatar_filesize}: <span id="image-size"></span></div>
				<div>- {LANG.avatar_ftype}: <span id="image-type"></span></div>
				<div>- {LANG.avatar_filedimension}: <span id="original-dimension"></span></div>
				<div>- {LANG.avatar_displaydimension}: <span id="display-dimension"></span></div>
				<div class="margin-top-lg">
                    <input id="btn-submit" type="submit" class="btn btn-success btn-sm" value="{LANG.avatar_crop}" />
                    <input id="btn-reset" type="button" class="btn btn-default btn-sm" value="{LANG.avatar_chosen_other}" />
				</div>
 			</div>
            <div class="exit-bt">
                <button class="btn btn-danger btn-sm" onclick=" window.close();return!1">{GLANG.cancel}</button>
            </div>
        </div>
        <input type="hidden" id="crop_x" name="crop_x"/>
        <input type="hidden" id="crop_y" name="crop_y"/>
        <input type="hidden" id="crop_width" name="crop_width"/>
        <input type="hidden" id="crop_height" name="crop_height"/>
        <input type="file" name="image_file" id="image_file" class="hide"/>
    </form>
</div>
<script type="text/javascript">
	UAV.config.maxsize = {NV_UPLOAD_MAX_FILESIZE};
	UAV.config.avatar_width = {NV_AVATAR_WIDTH};
	UAV.config.avatar_height = {NV_AVATAR_HEIGHT};
	UAV.lang.bigsize = '{LANG.avatar_bigsize}';
	UAV.lang.smallsize = '{LANG.avatar_smallsize}';
	UAV.lang.filetype = '{LANG.avatar_filetype}';
	UAV.lang.bigfile = '{LANG.avatar_bigfile}';
	UAV.lang.upload = '{LANG.avatar_upload}';
	$(window).on('load', function() {
		<!-- BEGIN: complete -->
		$("#avatar", opener.document).val('{FILENAME}');
		window.close();
		<!-- END: complete -->
        <!-- BEGIN: complete2 -->
        window.opener.location.href = window.opener.location.href;
		window.close();
		<!-- END: complete2 -->
        <!-- BEGIN: complete3 -->
        $("#myavatar", opener.document).attr('src', '{FILENAME}');
        $("#delavatar", opener.document).prop("disabled",!1);
		window.close();
		<!-- END: complete3 -->
		<!-- BEGIN: init -->UAV.init();<!-- END: init -->
		<!-- BEGIN: error -->alert('{ERROR}');<!-- END: error -->
	});
</script>
<!-- END: main -->