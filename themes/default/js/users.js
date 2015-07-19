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
		var ajaxLoaded = false;
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
						" + ( opts.isCaptchaLogin ?
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

	    opts.registerHTML = "\
		<div class=\"modal fade\" id=\"registerModal\"> \
		  <div class=\"modal-dialog\">\
		    <div class=\"modal-content\">\
		      <div class=\"modal-header\">\
		        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"" + opts.lang.close + "\"><span aria-hidden=\"true\">&times;</span></button>\
		        <h4 class=\"modal-title\">" + opts.lang.register + "</h4>\
		      </div>\
		      <div class=\"modal-body\">\
		      	<div class=\"container-fluid\">\
		      		<form id=\"registerForm\" action=\"\" method=\"post\" role=\"form\" class=\"form-horizontal form-tooltip m-bottom\">\
						<div class=\"form-group\">\
							<label for=\"first_name\" class=\"col-sm-8 control-label\">" + opts.lang.firstName + ":</label>\
							<div class=\"col-sm-16\">\
								<input type=\"text\" class=\"form-control\" id=\"first_name\" name=\"first_name\" value=\"\" maxlength=\"255\" />\
							</div>\
						</div>\
						<div class=\"form-group\">\
					        <label for=\"last_name\" class=\"col-sm-8 control-label\">" + opts.lang.lastName + ":</label>\
					        <div class=\"col-sm-16\">\
					            <input type=\"text\" class=\"form-control\" id=\"last_name\" name=\"last_name\" value=\"\" maxlength=\"255\" />\
					        </div>\
					    </div>\
						<div class=\"form-group\">\
							<label for=\"nv_email_iavim\" class=\"col-sm-8 control-label\">" + opts.lang.email + "<span class=\"text-danger\"> (*)</span>:</label>\
							<div class=\"col-sm-16\">\
								<input type=\"email\" class=\"email required form-control\" name=\"email\" value=\"\" id=\"nv_email_iavim\" maxlength=\"100\" />\
							</div>\
						</div>\
						<div class=\"form-group\">\
							<label for=\"nv_username_iavim\" class=\"col-sm-8 control-label\">" + opts.lang.account + "<span class=\"text-danger\"> (*)</span>:</label>\
							<div class=\"col-sm-16\">\
								<input type=\"text\" class=\"required form-control\" name=\"username\" value=\"\" id=\"nv_username_iavim\" maxlength=\"{NICK_MAXLENGTH}\" />\
							</div>\
						</div>\
						<div class=\"form-group\">\
							<label for=\"nv_password_iavim\" class=\"col-sm-8 control-label\">" + opts.lang.password + "<span class=\"text-danger\"> (*)</span>:</label>\
							<div class=\"col-sm-16\">\
								<input class=\"form-control required password\" name=\"password\" value=\"\" id=\"nv_password_iavim\" type=\"password\" maxlength=\"{PASS_MAXLENGTH}\" autocomplete=\"off\"/>\
							</div>\
						</div>\
						<div class=\"form-group\">\
							<label for=\"nv_re_password_iavim\" class=\"col-sm-8 control-label\">" + opts.lang.rePassword + "<span class=\"text-danger\"> (*)</span>:</label>\
							<div class=\"col-sm-16\">\
								<input class=\"form-control required password\" name=\"re_password\" value=\"\" id=\"nv_re_password_iavim\" type=\"password\" maxlength=\"{PASS_MAXLENGTH}\" autocomplete=\"off\"/>\
							</div>\
						</div>\
						<div class=\"form-group\">\
							<label for=\"question\" class=\"col-sm-8 control-label\">" + opts.lang.question + ":</label>\
							<div class=\"col-sm-16\">\
								<select name=\"question\" id=\"question\" class=\"form-control\"></select>\
							</div>\
						</div>\
						<div class=\"form-group\">\
							<label for=\"your_question\" class=\"col-sm-8 control-label\">" + opts.lang.yourQuestion + ":</label>\
							<div class=\"col-sm-16\">\
								<input type=\"text\" class=\"form-control\" name=\"your_question\" id=\"your_question\" value=\"\" />\
							</div>\
						</div>\
						<div class=\"form-group\">\
							<label for=\"answer\" class=\"col-sm-8 control-label\">" + opts.lang.answerYourQuestion + "<span class=\"text-danger\"> (*)</span>:</label>\
							<div class=\"col-sm-16\">\
								<input type=\"text\" class=\"form-control required\" name=\"answer\" id=\"answer\" value=\"\" />\
							</div>\
						</div>\
						" + ( opts.isCaptchaReg ? "\
						<div class=\"form-group\">\
							<label for=\"nv_seccode_iavim\" class=\"col-sm-8 control-label\">" + opts.lang.captcha + "<span class=\"text-danger\"> (*)</span>:</label>\
							<div class=\"col-sm-8\">\
								<input type=\"text\" name=\"nv_seccode\" id=\"nv_seccode_iavim\" class=\"required form-control\" maxlength=\"" + opts.captchaLen + "\" />\
							</div>\
							<div class=\"col-sm-8\">\
								<label class=\"control-label\">\
									<img id=\"vimg\" src=\"" + opts.siteroot + "index.php?scaptcha=captcha&t=" + opts.timeStamp + "\" width=\"" + opts.captchaW + "\" height=\"" + opts.captchaH + "\" />\
									&nbsp;<em class=\"fa fa-pointer fa-refresh fa-lg\" onclick=\"nv_change_captcha('vimg','nv_seccode_iavim');\">&nbsp;</em>\
								</label>\
							</div>\
						</div>\
						" : "" ) + "\
						<div class=\"form-group\">\
							<label for=\"question\" class=\"col-sm-8 control-label\"><a id=\"show-usage-terns\" href=\"\">" + opts.lang.usageTerms + " <i class=\"fa fa-globe\"></i></a>:</label>\
							<div class=\"col-sm-16\">\
								<div class=\"checkbox\">\
									<label>\
										<input class=\"required\" type=\"checkbox\" name=\"agreecheck\" id=\"agreecheck\" value=\"1\"/>\
										" + opts.lang.accept + "\
									</label>\
								</div>\
							</div>\
						</div>\
		      		</div>\
		      </div>\
		      <div class=\"modal-footer\">\
		      	<input type=\"hidden\" name=\"checkss\" id=\"checkss\" value=\"" + opts.checkss + "\" />\
		      	<i id=\"block-register-loading\" class=\"fa fa-circle-o-notch fa-spin hidden\"></i>\
		        <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">" + opts.lang.close + "</button>\
		        <button type=\"button\" class=\"btn btn-primary\" id=\"block-register-submit\">" + opts.lang.register + "</button>\
		      </div>\
		    </div>\
		  </div>\
		</div>";
		
		// Render html
		if(this.length){
			$('body').append(opts.loginHTML);
			
			if( opts.allowreg ){
				$('body').append(opts.registerHTML);
				
				$('#registerModal').on('show.bs.modal', function(e){
					if( ! ajaxLoaded ){
						$('#block-register-submit').attr('disabled', 'disabled');
						$('#block-register-loading').removeClass('hidden');
						
						$.ajax({
							type: 'POST',
							cache: true,
							url: nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=users&' + nv_fc_variable + '=register&nocache=' + new Date().getTime(),
							data: 'get_question=1',
							dataType: 'json',
							success: function(e){
								var html = '';
								$.each(e, function(k, v){
									html += '<option value="' + v.qid + '"' + v.selected + '>' + v.title + '</option>';
								});
								$('select#question').html(html);
								ajaxLoaded = true;
								$('#block-register-loading').addClass('hidden');
								$('#block-register-submit').removeAttr('disabled');
							}
						});
					}
					
					$('#agreecheck').attr('disabled', 'disabled');
				});
				
				$('#show-usage-terns').click(function(e){
					e.preventDefault();
					
					if( $('#usage-terns').length ){
						$('#usage-terns').modal('toggle');
						$('#agreecheck').removeAttr('disabled');
					}else{
						$('body').append(
							"<div id=\"usage-terns\" class=\"modal fade\">\
							  <div class=\"modal-dialog\">\
							    <div class=\"modal-content\">\
							      <div class=\"modal-header\">\
							        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>\
							        <h4 class=\"modal-title\">" + opts.lang.usageTerms + "</h4>\
							      </div>\
							      <div class=\"modal-body\">\
							        <div class=\"text-center\"><i class=\"fa fa-circle-o-notch fa-spin fa-3x\"></i></div>\
							      </div>\
							    </div>\
							  </div>\
							</div>"
						);
						$('#usage-terns').modal('toggle');
						
						$.ajax({
							type: 'POST',
							cache: true,
							url: nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=users&' + nv_fc_variable + '=register&nocache=' + new Date().getTime(),
							data: 'get_usage_terms=1',
							dataType: 'html',
							success: function(e){
								$('#usage-terns').find('.modal-body').html(e);
								$('#agreecheck').removeAttr('disabled');
							}
						});
					}
				});
			}
		}
		
		$('#block-login-submit').click(function(){
			$this = $(this);
			$this.attr('disabled', 'disabled');
			$.post(
				nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=users&' + nv_fc_variable + '=login&nocache=' + new Date().getTime(),
				'nv_login=' + encodeURIComponent($('#block_login_iavim').val()) + '&nv_password=' + encodeURIComponent($('#block_password_iavim').val()) + '&' + ( opts.isCaptchaLogin ? '&nv_seccode=' + encodeURIComponent($('#block_seccode_iavim').val()) : '' ) + '&nv_ajax_login=1',
				function(e) {
					$this.removeAttr('disabled');
					e = parseInt(e);
					if( e == 0 ){
						opts.loginComplete.call(undefined, e, opts);
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
		
		$('#block-register-submit').click(function(){
			var data = {
				first_name		: $('#first_name').val(),
				last_name		: $('#last_name').val(),
				email			: $('#nv_email_iavim').val(),
				username		: $('#nv_username_iavim').val(),
				password		: $('#nv_password_iavim').val(),
				re_password		: $('#nv_re_password_iavim').val(),
				question		: $('#question').val(),
				your_question	: $('#your_question').val(),
				answer			: $('#answer').val(),
				nv_seccode		: $('#nv_seccode_iavim').length ? $('#nv_seccode_iavim').val() : "",
				agreecheck		: $('#agreecheck:checked').length ? 1 : 0,
				checkss			: $('#checkss').val()
			};
			
			$this = $(this);
			$this.attr('disabled', 'disabled');
			$.post(
				nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=users&' + nv_fc_variable + '=register&nocache=' + new Date().getTime(),
				$.param( data ) + '&nv_ajax_register=1',
				function(e) {
					$this.removeAttr('disabled');
					nv_change_captcha('vimg','nv_seccode_iavim');					
					e = e.split('|');
					
					if( e[0] == 'OK' ){
						opts.registerComplete.call(undefined, e[1], opts);
					}else{
						alert( e[1] );
					}
				}
			);
		});
		
		return this.each(function(){
			$(this).find('.login').click(function(e){
				e.preventDefault();
				$('#loginModal').modal('toggle');
			});
			$(this).find('.register').click(function(e){
				e.preventDefault();
				$('#registerModal').modal('toggle');
				$('#registerModal [type="text"]').val('');
				$('#registerModal [type="email"]').val('');
				$('#registerModal [type="password"]').val('');
				$('#registerModal [type="checkbox"]').removeAttr('checked');
				$('#registerModal select option').removeAttr('selected');
			});
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
	isCaptchaLogin: false,
	isCaptchaReg: false,
	captchaW: 120,
	captchaH: 25,
	captchaLen: 6,
	timeStamp: 0,
	siteroot: "/",
	lostpassLink: "/",
	isOpenID: false,
	openIDSV: new Array(),
	allowreg: false,
	checkss: "",
	lang: {
		close: "Close",
		login: "Login",
		loginSubmit: "Login",
		username: "Username",
		password: "Password",
		securitycode: "Security code",
		lostpass: "Lost password",
		openidLogin: "Openid login",
		register: "Register",
		firstName: "First name",
		lastName: "Last name",
		email: "Email",
		account: "Account",
		rePassword: "Repeat password",
		question: "Question",
		yourQuestion: "Your question",
		answerYourQuestion: "Answer your question",
		inGroup: "Group",
		usageTerms: "Usage terms",
		captcha: "Captcha",
		accept: "Accept"
	},
    loginComplete: function(res, opts){
    	window.location.href = window.location.href;
    },
    registerComplete: function(res, opts){
    	alert(res);
    	window.location.href = window.location.href;
    }
};

// Trigger login & register
$(document).ready(function(){
	$('#nv-block-login').user();
});