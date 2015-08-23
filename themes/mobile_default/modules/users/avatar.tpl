<!-- BEGIN: main -->
<script src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery/jquery.Jcrop.min.js" type="text/javascript"></script>
<link href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery/jquery.Jcrop.min.css" rel="stylesheet" type="text/css" />
<div class="users-av-wraper">
    <form id="upload-form" method="post" enctype="multipart/form-data" action="{NV_AVATAR_UPLOAD}">
        <div class="col-xs-16">
            <div class="users-avupload">
                <div title="{LANG.avata_select_img}" id="upload_icon" class="upload-button">
                    <div class="text-center">
                        <span><em class="fa fa-upload"></em></span>
        			</div>
                </div>
                <div class="img-area"><img id="preview" style="display: none;"/></div>
            </div>
        </div>
        <div class="col-xs-8">
            <h2 class="margin-bottom-lg">{LANG.change_avatar}</h2>
            <div class="guide" id="guide">
                <div class="margin-bottom"><strong>{LANG.avata_guide}:</strong></div>
                <div>- {LANG.avata_chosen}</div>
                <div>- {LANG.avata_upload}</div>
            </div>
            <div style="display:none" id="uploadInfo">
				<div>- {LANG.avata_filesize}: <span id="image-size"></span></div>
				<div>- {LANG.avata_ftype}: <span id="image-type"></span></div>
				<div>- {LANG.avata_filedimension}: <span id="original-dimension"></span></div>
				<div>- {LANG.avata_displaydimension}: <span id="display-dimension"></span></div>
				<div class="margin-top-lg">
                    <input id="btn-submit" type="submit" class="btn btn-primary btn-sm" value="{LANG.avata_crop}" />
                    <input id="btn-reset" type="button" class="btn btn-primary btn-sm" value="{LANG.avata_chosen_other}" />
				</div>
 			</div>
            <div class="exit-bt">
                <button class="btn btn-primary btn-sm" onclick=" window.close();return!1">{GLANG.cancel}</button>
            </div>
        </div>
        <input type="hidden" id="x1" name="x1"/>
        <input type="hidden" id="y1" name="y1"/>
        <input type="hidden" id="x2" name="x2"/>
        <input type="hidden" id="y2" name="y2"/>
        <input type="hidden" id="w" name="w"/>
        <input type="hidden" id="h" name="h"/>
        <input type="file" name="image_file" id="image_file" class="hide"/>
    </form>
</div>
<script type="text/javascript">
	UAV.config.maxsize = {NV_UPLOAD_MAX_FILESIZE};
	UAV.config.avatar_width = {NV_AVATAR_WIDTH};
	UAV.config.avatar_height = {NV_AVATAR_HEIGHT};
	UAV.config.max_width = {NV_MAX_WIDTH};
	UAV.config.max_height = {NV_MAX_HEIGHT};
	UAV.lang.bigsize = '{LANG.avata_bigsize}';
	UAV.lang.smallsize = '{LANG.avata_smallsize}';
	UAV.lang.filetype = '{LANG.avata_filetype}';
	UAV.lang.bigfile = '{LANG.avata_bigfile}';
	UAV.lang.upload = '{LANG.avata_upload}';
	$(document).ready(function() {
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