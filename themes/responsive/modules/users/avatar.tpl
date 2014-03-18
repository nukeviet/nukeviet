<!-- BEGIN: main -->
<div class="users-avupload form-tooltip">
	<div class="panel-body">
		<form id="upload-form" method="post" enctype="multipart/form-data" action="{NV_AVATAR_UPLOAD}">
			<input type="hidden" id="x1" name="x1"/>
			<input type="hidden" id="y1" name="y1"/>
			<input type="hidden" id="x2" name="x2"/>
			<input type="hidden" id="y2" name="y2"/>
			<input type="hidden" id="w" name="w"/>
			<input type="hidden" id="h" name="h"/>
			<input type="file" name="image_file" id="image_file" class="hide"/>
			<h1 id="upload_icon" class="upload-button text-center">
				<em class="fa fa-upload fa-5x">&nbsp;</em><br />
				<span>{LANG.avata_select_img}</span>
			</h1>
			<div class="img-area"><img id="preview"/></div>
			<div class="info" id="uploadInfo">
				<div class="text-center">
					<p>
						<span class="icon-stack" data-toggle="tooltip" data-placement="bottom" title="{LANG.avata_filesize}"><em class="fa fa-asterisk">&nbsp;</em> <span id="image-size">&nbsp;</span></span>
						<span class="icon-stack" data-toggle="tooltip" data-placement="bottom" title="{LANG.avata_ftype}"><em class="fa fa-bolt">&nbsp;</em> <span id="image-type">&nbsp;</span></span>
						<span class="icon-stack" data-toggle="tooltip" data-placement="bottom" title="{LANG.avata_filedimension}"><em class="fa fa-bullseye">&nbsp;</em> <span id="original-dimension">&nbsp;</span></span>
						<span class="icon-stack" data-toggle="tooltip" data-placement="bottom" title="{LANG.avata_displaydimension}"><em class="fa fa-circle-o">&nbsp;</em> <span id="display-dimension">&nbsp;</span></span>
					</p>
					<input id="btn-submit" type="submit" class="btn btn-primary btn-sm" value="{LANG.avata_crop}" /> 
					<input id="btn-reset" type="button" class="btn btn-primary btn-sm" value="{LANG.avata_chosen_other}" />
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