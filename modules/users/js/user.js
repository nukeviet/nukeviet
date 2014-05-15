/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC ( contact@vinades.vn )
 * @Copyright ( C ) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 1 - 31 - 2010 5 : 12
 */

var UAV = {};

// Default config, replace it with your own
UAV.config = {
	inputFile: 'image_file',
	uploadIcon: 'upload_icon',
	pattern: /^(image\/jpeg|image\/png)$/i,
	maxsize: 2097152,
	avatar_width: 80,
	avatar_height: 80,
	max_width: 1500,
	max_height: 1500,
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

// Default language, replace it with your own
UAV.lang = {
	bigsize: 'File too large',
	smallsize: 'File too small',
	filetype: 'Only accept jmage file tyle',
	bigfile: 'File too big',
	upload: 'Please upload and drag to crop',
};

UAV.data = {
	error: false,
	busy: false,
	jcropApi: null,
};

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

// Please use this package with Jcrop http://deepliquid.com/content/Jcrop.html
UAV.common = {
	read: function(file){
		var fRead = new FileReader();
		fRead.onload = function(e){
			$('#' + UAV.config.target).show();
			$('#' + UAV.config.target).attr('src', e.target.result);
			$('#' + UAV.config.target).load(function(){
				var img = document.getElementById(UAV.config.target);

				if( img.naturalWidth > UAV.config.max_width || img.naturalHeight > UAV.config.max_height ){
					UAV.common.error(UAV.lang.bigsize);
					UAV.data.error = true;
					return false;
				}

				if( img.naturalWidth < UAV.config.avatar_width || img.naturalHeight < UAV.config.avatar_height ){
					UAV.common.error(UAV.lang.smallsize);
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
		};

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
			UAV.common.error(UAV.lang.filetype);
			UAV.data.error = true;
		}

		// Check size
		if( image.size > UAV.config.maxsize){
			UAV.common.error(UAV.lang.bigfile);
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
				alert(UAV.lang.upload);
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
};