<!-- BEGIN: main -->
<div class="users-avupload">
	<div class="content-box">
		<form id="upload-form" method="post" enctype="multipart/form-data" action="{NV_AVATAR_UPLOAD}">
			<input type="hidden" id="x1" name="x1"/>
			<input type="hidden" id="y1" name="y1"/>
			<input type="hidden" id="x2" name="x2"/>
			<input type="hidden" id="y2" name="y2"/>
			<input type="hidden" id="w" name="w"/>
			<input type="hidden" id="h" name="h"/>
			<input type="file" name="image_file" id="image_file" class="hide"/>
			<h1 id="upload_icon" class="upload-button acenter">
				<img src="{NV_BASE_SITEURL}themes/{TEMPLATE}/images/{MODULE_FILE}/upload.png" alt="{LANG.avata_pagetitle}" width="400"/><br />
				<span>{LANG.avata_select_img}</span>
			</h1>
			<div class="img-area"><img id="preview"/></div>
			<div class="info" id="uploadInfo">
				<div class="acenter">
					<p>
						<span class="icon-stack">{LANG.avata_filesize}: <span id="image-size">&nbsp;</span></span>
						<span class="icon-stack">{LANG.avata_ftype}: <span id="image-type">&nbsp;</span></span>
						<span class="icon-stack">{LANG.avata_filedimension}: <span id="original-dimension">&nbsp;</span></span>
						<span class="icon-stack">{LANG.avata_displaydimension}: <span id="display-dimension">&nbsp;</span></span>
					</p>
					<input id="btn-submit" type="submit" class="button" value="{LANG.avata_crop}" /> 
					<input id="btn-reset" type="button" class="button-2" value="{LANG.avata_chosen_other}" />
				</div>
			</div>
		</form>
	</div>
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
	<!-- BEGIN: init -->UAV.init();<!-- END: init -->
	<!-- BEGIN: error -->alert('{ERROR}');<!-- END: error -->
});
</script>
<!-- END: main -->