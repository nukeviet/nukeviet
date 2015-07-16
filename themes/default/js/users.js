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
	uploadForm: 'upload-form'
};

// Default language, replace it with your own
UAV.lang = {
	bigsize: 'File too large',
	smallsize: 'File too small',
	filetype: 'Only accept jmage file tyle',
	bigfile: 'File too big',
	upload: 'Please upload and drag to crop'
};

UAV.data = {
	error: false,
	busy: false,
	jcropApi: null
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
	}
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
						onRelease: function(e){ UAV.tool.clear(e); }
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

// User login and register
(function($){
	$.fn.user = function( options ){
		var opts = $.extend( {}, $.fn.user.defaults, options );
		var openID = "";
		
		if( opts.isOpenID && opts.openIDSV.length ){
			for( i = 0, j = opts.openIDSV.length; i < j; i ++ ){
				openID += "\
					<a title=\"" + opts.openIDSV[i].title + "\" href=\"" + opts.openIDSV[i].href + "\">\
				 		<img alt=\"" + opts.openIDSV[i].title + "\" src=\"" + opts.openIDSV[i].imgSRC + "\" width=\"" + opts.openIDSV[i].imgW + "\" height=\"" + opts.openIDSV[i].imgH + "\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" + opts.openIDSV[i].title + "\"/>\
					</a>\
				";
			}
		}
		
	    opts.loginHTML = "\
		<div class=\"modal fade\" id=\"loginModal\"> \
		  <div class=\"modal-dialog\">\
		    <div class=\"modal-content\">\
		      <div class=\"modal-header\">\
		        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"" + opts.lang.close + "\"><span aria-hidden=\"true\">&times;</span></button>\
		        <h4 class=\"modal-title\">" + opts.lang.login + "</h4>\
		      </div>\
		      <div class=\"modal-body\">\
		      	<div class=\"container-fluid\">\
					<form action=\"\" method=\"post\" role=\"form\" class=\"form-tooltip\">\
						<div class=\"form-group\">\
							<div class=\"input-group\">\
								<span class=\"input-group-addon\"><em class=\"fa fa-user fa-lg\"></em></span>\
								<input type=\"text\" class=\"form-control\" id=\"block_login_iavim\" name=\"nv_login\" value=\"\" placeholder=\"" + opts.lang.username + "\">\
							</div>\
						</div>\
						<div class=\"form-group\">\
							<div class=\"input-group\">\
								<span class=\"input-group-addon\"><em class=\"fa fa-key fa-lg fa-fix\"></em></span>\
								<input type=\"password\" class=\"form-control\" id=\"block_password_iavim\" name=\"nv_password\" value=\"\" placeholder=\"" + opts.lang.password + "\">\
							</div>\
						</div>\
						" + ( opts.isCaptcha ?
						"<div class=\"form-group text-right\">\
							<img id=\"block_vimg\" src=\"" + opts.siteroot + "index.php?scaptcha=captcha&t=" + opts.timeStamp + "\" width=\"" + opts.captchaW + "\" height=\"" + opts.captchaH + "\"/>\
							<em class=\"fa fa-pointer fa-refresh fa-lg\" onclick=\"nv_change_captcha('block_vimg','block_seccode_iavim');\"></em>\
						</div>\
						<div class=\"form-group\">\
							<div class=\"input-group\">\
								<span class=\"input-group-addon\"><em class=\"fa fa-shield fa-lg fa-fix\"></em></span>\
								<input id=\"block_seccode_iavim\" name=\"nv_seccode\" type=\"text\" class=\"form-control\" maxlength=\"" + opts.captchaLen + "\" placeholder=\"" + opts.lang.securitycode + "\"/>\
							</div>\
						</div>" : "" ) + "\
						<div class=\"form-group\">\
							<a class=\"pull-right\" title=\"" + opts.lang.lostpass + "\" href=\"" + opts.lostpassLink + "\">" + opts.lang.lostpass + "?</a>\
						</div>\
						" + ( opts.isOpenID ? "\
						<div class=\"clearfix\">\
							<hr />\
							<p class=\"text-center\">\
						 		<i class=\"fa fa-openid\"></i> " + opts.lang.openidLogin + "\
							</p>\
							<div class=\"text-center\">\
								" + openID + "\
							</div>\
						</div>" : "" ) + "\
						<!-- END: openid -->\
					</form>\
		      	</div>\
		      </div>\
		      <div class=\"modal-footer\">\
		        <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">" + opts.lang.close + "</button>\
		        <button type=\"button\" class=\"btn btn-primary\" id=\"block-login-submit\">" + opts.lang.loginSubmit + "</button>\
		      </div>\
		    </div>\
		  </div>\
		</div>";

	    opts.registerHTML = "";
		
		// Render html
		if(this.length){
			$('body').append(opts.loginHTML);
		}
		
		$('#block-login-submit').click(function(){
			$this = $(this);
			$this.attr('disabled', 'disabled');
			$.post(
				nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=users&' + nv_fc_variable + '=login&nocache=' + new Date().getTime(),
				'nv_login=' + encodeURIComponent($('#block_login_iavim').val()) + '&nv_password=' + encodeURIComponent($('#block_password_iavim').val()) + '&' + ( opts.isCaptcha ? '&nv_seccode=' + encodeURIComponent($('#block_seccode_iavim').val()) : '' ) + '&nv_ajax_login=1',
				function(e) {
					$this.removeAttr('disabled');
					e = parseInt(e);
					if( e == 0 ){
						opts.loginComplete.call(e, opts);
					}else if( e == 1 ){
						$('#block_login_iavim').parent().parent().addClass('has-error');
					}else if( e == 2 ){
						$('#block_password_iavim').parent().parent().addClass('has-error');
					}else if( e == 3 ){
						$('#block_seccode_iavim').parent().parent().addClass('has-error');
					}
				}
			);
		});		
		
		return this.each(function(){
			$(this).find('.login').click(function(e){
				e.preventDefault();
				$('#loginModal').modal('toggle');
			});
//			$(this).find('.register').click(function(e){
//				e.preventDefault();
//				$('#loginModal').modal('toggle');
//			});
		});
	};
	
	// Debug
	function debug(msg){
        if( window.console && window.console.log ){
            window.console.log( msg );
        }
    };
}(jQuery));

$.fn.user.defaults = {
	isCaptcha: false,
	captchaW: 120,
	captchaH: 25,
	captchaLen: 6,
	timeStamp: 0,
	siteroot: "/",
	lostpassLink: "/",
	isOpenID: false,
	openIDSV: new Array(),
	lang: {
		close: "Close",
		login: "Login",
		loginSubmit: "Login",
		username: "Username",
		password: "Password",
		securitycode: "Security code",
		lostpass: "Lost Password",
		openidLogin: "Openid Login",
	},
    loginComplete: function(){
    	window.location.href = window.location.href;
    },
    registerComplete: function(){
    	window.location.href = window.location.href;
    }
};

// Trigger login & register
$(document).ready(function(){
	$('#nv-block-login').user();
});