<!-- BEGIN: main -->
<script type="text/javascript">
var UAV = {};
UAV.config = {
	inputFile: 'image_file',
	uploadIcon: 'upload_icon',
	pattern: /^(image\/jpeg|image\/png)$/i,
	maxsize: {NV_UPLOAD_MAX_FILESIZE},
	avatar_width: {NV_AVATAR_WIDTH},
	avatar_height: {NV_AVATAR_HEIGHT},
	max_width: {NV_MAX_WIDTH},
	max_height: {NV_MAX_HEIGHT},
	target: 'preview',
	uploadInfo: 'uploadInfo',
	x1: 'x1',
	y1: 'y1',
	x2: 'x2',
	y2: 'y2',
	w: 'w',
	h: 'h',
	originalDimension: 'original-dimension',
	displayDimension: 'display-dimension',
	imageType: 'image-type',
	imageSize: 'image-size',
	btnSubmit: 'btn-submit',
	btnReset: 'btn-reset',
	uploadForm: 'upload-form',
};
UAV.data = {
	error: false,
	busy: false,
	jcropApi: null,
}
UAV.tool = {
	bytes2Size: function( bytes ){
		var sizes = ['Bytes', 'KB', 'MB'];
		if( bytes == 0 ) return 'n/a';
		var i = parseInt( Math.floor( Math.log(bytes) / Math.log(1024) ) );
		return ( bytes / Math.pow(1024, i) ).toFixed(1) + ' ' + sizes[i];
	},
	update: function(e){
		$('#' + UAV.config.x1).val(e.x);
		$('#' + UAV.config.y1).val(e.y);
		$('#' + UAV.config.x2).val(e.x2);
		$('#' + UAV.config.y2).val(e.y2);
	},
	clear: function(e){
		$('#' + UAV.config.x1).val(0);
		$('#' + UAV.config.y1).val(0);
		$('#' + UAV.config.x2).val(0);
		$('#' + UAV.config.y2).val(0);
	},
};
UAV.common = {
	read: function(file){
		var fRead = new FileReader();
		fRead.onload = function(e){
			$('#' + UAV.config.target).show();
			$('#' + UAV.config.target).attr('src', e.target.result);
			$('#' + UAV.config.target).load(function(){
				var img = document.getElementById(UAV.config.target);
				
				if( img.naturalWidth > UAV.config.max_width || img.naturalHeight > UAV.config.max_height ){
					UAV.common.error('{LANG.avata_bigsize}');
					UAV.data.error = true;
					return false;
				}
				
				if( img.naturalWidth < UAV.config.avatar_width || img.naturalHeight < UAV.config.avatar_height ){
					UAV.common.error('{LANG.avata_smallsize}');
					UAV.data.error = true;
					return false;
				}
				
				if( ! UAV.data.error ){
					// Hide and show data
					$('#' + UAV.config.uploadIcon).hide();
					$('#' + UAV.config.uploadInfo).show();
					
					$('#' + UAV.config.imageType).html( file.type );
					$('#' + UAV.config.imageSize).html( UAV.tool.bytes2Size( file.size ) );
					$('#' + UAV.config.originalDimension).html( img.naturalWidth + ' x ' + img.naturalHeight );
						
					$('#' + UAV.config.target).Jcrop({
						minSize: [UAV.config.avatar_width, UAV.config.avatar_height],
						aspectRatio : 1,
						bgFade: true,
						bgOpacity: .3,
						onChange: function(e){ UAV.tool.update(e); },
						onSelect: function(e){ UAV.tool.update(e); },
						onRelease: function(e){ UAV.tool.clear(e); },
					}, function(){
						var bounds = this.getBounds();
						$('#' + UAV.config.w).val(bounds[0]);
						$('#' + UAV.config.h).val(bounds[1]);
						$('#' + UAV.config.displayDimension).html( bounds[0] + ' + ' + bounds[1] );
						UAV.data.jcropApi = this;
					});
				}
			});
		}
		
		fRead.readAsDataURL(file);
	},
	init: function(){
		UAV.data.error = false;
		
		if( $('#' + UAV.config.inputFile).val() == '' ){
			UAV.data.error = true;
		}
		
		var image = $('#' + UAV.config.inputFile)[0].files[0];
		
		// Check ext
		if( ! UAV.config.pattern.test( image.type ) ){
			UAV.common.error('{LANG.avata_filetype}');
			UAV.data.error = true;
		}
		
		// Check size
		if( image.size > UAV.config.maxsize){
			UAV.common.error('{LANG.avata_bigfile}');
			UAV.data.error = true;
		}
		
		if( ! UAV.data.error ){
			// Read image
			UAV.common.read(image);	
		}
	},
	error: function(e){
		UAV.common.reset();
		alert(e);
	},
	reset: function(){
		if( UAV.data.jcropApi != null ){
			UAV.data.jcropApi.destroy();
		}
		UAV.data.error = false;
		UAV.data.busy = false;
		UAV.tool.clear();
		$('#' + UAV.config.target).removeAttr('src').removeAttr('style').hide();
		$('#' + UAV.config.uploadIcon).show();
		$('#' + UAV.config.uploadInfo).hide();
		$('#' + UAV.config.imageType).html('');
		$('#' + UAV.config.imageSize).html('');
		$('#' + UAV.config.originalDimension).html('');
		$('#' + UAV.config.w).val('');
		$('#' + UAV.config.h).val('');
		$('#' + UAV.config.displayDimension).html('');
	},
	submit: function(){
		if( ! UAV.data.busy ){
			if( $('#' + UAV.config.x2).val() == '' || $('#' + UAV.config.x2).val() == '0' ){
				alert('{LANG.avata_upload}');
				return false;
			}
			UAV.data.busy = true;
			return true;
		}
		return false;
	}
};
UAV.init = function(){
	$('#' + UAV.config.uploadIcon).click(function(){
		$('#' + UAV.config.inputFile).trigger('click');
	});	
	$('#' + UAV.config.inputFile).change(function(){
		UAV.common.init();
	});
	$('#' + UAV.config.btnReset).click(function(){
		if( ! UAV.data.busy ){
			UAV.common.reset();
		}
	});
	$('#' + UAV.config.uploadForm).submit(function(){
		return UAV.common.submit();
	});
}
</script>
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
				<i class="fa fa-upload fa-5x">&nbsp;</i><br />
				<span>{LANG.avata_select_img}</span>
			</h1>
			<div class="img-area"><img id="preview"/></div>
			<div class="info" id="uploadInfo">
				<div class="text-center">
					<p>
						<span class="icon-stack" data-toggle="tooltip" data-placement="bottom" title="{LANG.avata_filesize}"><i class="fa fa-asterisk">&nbsp;</i> <span id="image-size">&nbsp;</span></span>
						<span class="icon-stack" data-toggle="tooltip" data-placement="bottom" title="{LANG.avata_ftype}"><i class="fa fa-bolt">&nbsp;</i> <span id="image-type">&nbsp;</span></span>
						<span class="icon-stack" data-toggle="tooltip" data-placement="bottom" title="{LANG.avata_filedimension}"><i class="fa fa-bullseye">&nbsp;</i> <span id="original-dimension">&nbsp;</span></span>
						<span class="icon-stack" data-toggle="tooltip" data-placement="bottom" title="{LANG.avata_displaydimension}"><i class="fa fa-circle-o">&nbsp;</i> <span id="display-dimension">&nbsp;</span></span>
					</p>
					<input id="btn-submit" type="submit" class="btn btn-primary btn-sm" value="{LANG.avata_crop}" /> 
					<input id="btn-reset" type="button" class="btn btn-primary btn-sm" value="{LANG.avata_chosen_other}" />
				</div>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript">
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