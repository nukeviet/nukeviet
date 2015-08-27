/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC ( contact@vinades.vn )
 * @Copyright ( C ) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 1 - 31 - 2010 5 : 12
 */

function addpass() {
	$("a[href*=edit_password]").click();
	return !1
}

function safe_deactivate_show(a, b) {
	$(b).hide(0);
	$(a).fadeIn();
	return !1
}

function safekeySend(a) {
	$(".safekeySend", a).prop("disabled", !0);
	$.ajax({
		type: $(a).prop("method"),
		cache: !1,
		url: $(a).prop("action"),
		data: $(a).serialize() + '&resend=1',
		dataType: "json",
		success: function(e) {
			"error" == e.status ? ($(".safekeySend", a).prop("disabled", !1), $(".tooltip-current", a).removeClass("tooltip-current"), $("[name=" + e.input + "]", a).addClass("tooltip-current").attr("data-current-mess", $("[name=" + e.input + "]", a).attr("data-mess")), validErrorShow($("[name=" + e.input + "]", a))) : ($(".nv-info", a).html(e.mess).removeClass("error").addClass("success").show(), setTimeout(function() {
				var d = $(".nv-info", a).attr("data-default");
				if (!d) d = $(".nv-info-default", a).html();
				$(".nv-info", a).removeClass("error success").html(d);
				$(".safekeySend", a).prop("disabled", !1);
			}, 6E3))
		}
	});
	return !1
}

function changeAvatar(a) {
	if (nv_safemode) return !1;
	nv_open_browse(a, "NVImg", 650, 430, "resizable=no,scrollbars=1,toolbar=no,location=no,status=no");
	return !1;
}

function deleteAvatar(a, b, c) {
	if (nv_safemode) return !1;
	$(c).prop("disabled", !0);
	$.ajax({
		type: 'POST',
		cache: !1,
		url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=users&' + nv_fc_variable + '=avatar/del',
		data: 'checkss=' + b + '&del=1',
		dataType: 'json',
		success: function(e) {
			$(a).attr("src", $(a).attr("data-default"));
		}
	});
	return !1
}

function datepickerShow(a) {
	if ("object" == typeof $.datepicker) {
		$(a).datepicker({
			dateFormat: "dd/mm/yy",
			changeMonth: !0,
			changeYear: !0,
			showOtherMonths: !0,
			showOn: "focus",
			yearRange: "-90:+0"
		});
		$(a).css("z-index", "1000").datepicker('show');
	}
}

function button_datepickerShow(a) {
	var b = $(a).parent();
	datepickerShow($(".datepicker", b))
}

function verkeySend(a) {
	$(".has-error", a).removeClass("has-error");
	var d = 0;
	$(a).find("input.required,textarea.required,select.required,div.required").each(function() {
		var b = $(this).prop("tagName");
		"INPUT" != b && "TEXTAREA" != b || "password" == $(a).prop("type") || "radio" == $(a).prop("type") || "checkbox" == $(a).prop("type") || $(this).val(trim(strip_tags($(this).val())));
		if (!validCheck(this)) return d++, $(".tooltip-current", a).removeClass("tooltip-current"), $(this).addClass("tooltip-current").attr("data-current-mess", $(this).attr("data-mess")), validErrorShow(this), !1
	});
	d || ($("[name=vsend]", a).val("1"), $("[type=submit]", a).click());
	return !1
}

function showQlist(a) {
	var b = $(".qlist", $(a).parent().parent());
	if ("no" == b.attr("data-show")) b.attr("data-show", "yes").show();
	else b.attr("data-show", "no").hide();
	return !1
}

function addQuestion(a) {
	var b = $(a).parent().parent().parent().parent();
	$("[name=your_question]", b).val($(a).text());
	$(".qlist", b).attr("data-show", "no").hide();
	return !1
}

function usageTermsShow(t) {
	$.ajax({
		type: 'POST',
		cache: !0,
		url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=users&' + nv_fc_variable + '=register',
		data: 'get_usage_terms=1',
		dataType: 'html',
		success: function(e) {
			modalShow(t, e)
		}
	});
	return !1
}

function validErrorShow(a) {
	$(a).parent().parent().addClass("has-error");
	$("[data-mess]", $(a).parent().parent().parent()).not(".tooltip-current").tooltip("destroy");
	$(a).tooltip({
	   container: "body",
		placement: "bottom",
		title: function() {
			return "" != $(a).attr("data-current-mess") ? $(a).attr("data-current-mess") : nv_required
		}
	});
	$(a).focus().tooltip("show");
	"DIV" == $(a).prop("tagName") && $("input", a)[0].focus()
}

function validCheck(a) {
	var c = $(a).attr("data-pattern"),
		d = $(a).val(),
		b = $(a).prop("tagName"),
		e = $(a).prop("type");
	if ("INPUT" == b && "email" == e) {
		if (!nv_mailfilter.test(d)) return !1
	} else if ("SELECT" == b) {
		if (!$("option:selected", a).length) return !1
	} else if ("DIV" == b && $(a).is(".radio-box")) {
		if (!$("[type=radio]:checked", a).length) return !1
	} else if ("DIV" == b && $(a).is(".check-box")) {
		if (!$("[type=checkbox]:checked", a).length) return !1
	} else if ("INPUT" == b || "TEXTAREA" == b) if ("undefined" == typeof c || "" == c) {
		if ("" == d) return !1
	} else if (a = c.match(/^\/(.*?)\/([gim]*)$/), !(a ? new RegExp(a[1], a[2]) : new RegExp(c)).test(d)) return !1;
	return !0
}

function validErrorHidden(a, b) {
	if (!b) b = 2;
	b = parseInt(b);
	var c = $(a),
		d = $(a);
	for (var i = 0; i < b; i++) {
		c = c.parent();
		if (i >= 2) d = d.parent()
	}
	d.tooltip("destroy");
	c.removeClass("has-error")
}

function formErrorHidden(a) {
	$(".has-error", a).removeClass("has-error");
	$("[data-mess]", a).tooltip("destroy")
}

function validReset(a) {
	var d = $(".nv-info", a).attr("data-default");
	if (!d) d = $(".nv-info-default", a).html();
	$(".nv-info", a).removeClass("error success").html(d);
	formErrorHidden(a);
	$("input,button,select,textarea", a).prop("disabled", !1);
	$(a)[0].reset()
}

function login_validForm(a) {
	$(".has-error", a).removeClass("has-error");
	var c = 0,
		b = [];
	$(a).find(".required").each(function() {
		"password" == $(a).prop("type") && $(this).val(trim(strip_tags($(this).val())));
		if (!validCheck(this)) return c++, $(".tooltip-current", a).removeClass("tooltip-current"), $(this).addClass("tooltip-current").attr("data-current-mess", $(this).attr("data-mess")), validErrorShow(this), !1
	});
	c || (b.type = $(a).prop("method"), b.url = $(a).prop("action"), b.data = $(a).serialize(), formErrorHidden(a), $(a).find("input,button,select,textarea").prop("disabled", !0), $.ajax({
		type: b.type,
		cache: !1,
		url: b.url,
		data: b.data,
		dataType: "json",
		success: function(d) {
			var b = $("[onclick*='change_captcha']", a);
			b && b.click();
			"error" == d.status ? ($("input,button", a).not("[type=submit]").prop("disabled", !1), $(".tooltip-current", a).removeClass("tooltip-current"), "" != d.input ? $(a).find("[name=" + d.input + "]").each(function() {
				$(this).addClass("tooltip-current").attr("data-current-mess", d.mess);
				validErrorShow(this)
			}) : $(".nv-info", a).html(d.mess).addClass("error").show(), setTimeout(function() {
				$("[type=submit]", a).prop("disabled", !1)
			}, 1E3)) : ($(".nv-info", a).html(d.mess + '<span class="load-bar"></span>').removeClass("error").addClass("success").show(), $(".form-detail", a).hide(), setTimeout(function() {
				window.location.href = "undefined" != typeof d.redirect && "" != d.redirect ? d.redirect : window.location.href
			}, 3E3))
		}
	}));
	return !1
}

function reg_validForm(a) {
	$(".has-error", a).removeClass("has-error");
	var d = 0,
		c = [];
	$(a).find("input.required,textarea.required,select.required,div.required").each(function() {
		var b = $(this).prop("tagName");
		"INPUT" != b && "TEXTAREA" != b || "password" == $(a).prop("type") || "radio" == $(a).prop("type") || "checkbox" == $(a).prop("type") || $(this).val(trim(strip_tags($(this).val())));
		if (!validCheck(this)) return d++, $(".tooltip-current", a).removeClass("tooltip-current"), $(this).addClass("tooltip-current").attr("data-current-mess", $(this).attr("data-mess")), validErrorShow(this), !1
	});
	d || (c.type = $(a).prop("method"), c.url = $(a).prop("action"), c.data = $(a).serialize(), formErrorHidden(a), $(a).find("input,button,select,textarea").prop("disabled", !0), $.ajax({
		type: c.type,
		cache: !1,
		url: c.url,
		data: c.data,
		dataType: "json",
		success: function(b) {
			var c = $("[onclick*='change_captcha']", a);
			c && c.click();
			"error" == b.status ? ($("input,button,select,textarea", a).prop("disabled", !1), $(".tooltip-current", a).removeClass("tooltip-current"), "" != b.input ? $(a).find("[name=" + b.input + "]").each(function() {
				$(this).addClass("tooltip-current").attr("data-current-mess", b.mess);
				validErrorShow(this)
			}) : ($(".nv-info", a).html(b.mess).addClass("error").show(), $("html, body").animate({
				scrollTop: $(".nv-info", a).offset().top
			}, 800))) : ($(".nv-info", a).html(b.mess + '<span class="load-bar"></span>').removeClass("error").addClass("success").show(), ("ok" == b.input ? setTimeout(function() {
				$(".nv-info", a).fadeOut();
				$("input,button,select,textarea", a).prop("disabled", !1);
				$("[onclick*=validReset]", a).click()
			}, 6E3) : $("html, body").animate({
				scrollTop: $(".nv-info", a).offset().top
			}, 800, function() {
				$(".form-detail", a).hide();
				setTimeout(function() {
					window.location.href = "" != b.input ? b.input : window.location.href
				}, 6E3)
			})))
		}
	}));
	return !1
}

function lostpass_validForm(a) {
	$(".has-error", a).removeClass("has-error");
	var d = 0,
		c = [];
	$(a).find("input.required,textarea.required,select.required,div.required").each(function() {
		var b = $(this).prop("tagName");
		"INPUT" != b && "TEXTAREA" != b || "password" == $(a).prop("type") || "radio" == $(a).prop("type") || "checkbox" == $(a).prop("type") || $(this).val(trim(strip_tags($(this).val())));
		if (!validCheck(this)) return d++, $(".tooltip-current", a).removeClass("tooltip-current"), $(this).addClass("tooltip-current").attr("data-current-mess", $(this).attr("data-mess")), validErrorShow(this), !1
	});
	d || (c.type = $(a).prop("method"), c.url = $(a).prop("action"), c.data = $(a).serialize(), formErrorHidden(a), $(a).find("input,button,select,textarea").prop("disabled", !0), $.ajax({
		type: c.type,
		cache: !1,
		url: c.url,
		data: c.data,
		dataType: "json",
		success: function(b) {
			if (b.status == "error") {
			    $("[name=step]",a).val(b.step);
			     if(b.step == 'step1') $("[onclick*='change_captcha']", a).click();
                 if("undefined" != typeof b.info && "" != b.info) $(".nv-info",a).removeClass('error success').text(b.info);
				$("input,button", a).prop("disabled", !1);
                $(".required",a).removeClass("required");
				$(".tooltip-current", a).removeClass("tooltip-current");
				$("[class*=step]", a).hide();
                $("." + b.step + " input", a).addClass("required");
				$("." + b.step, a).show();
				$(a).find("[name=" + b.input + "]").each(function() {
					$(this).addClass("tooltip-current").attr("data-current-mess", b.mess);
					validErrorShow(this)
				})
			} else {
			     $(".nv-info", a).html(b.mess + '<span class="load-bar"></span>').removeClass("error").addClass("success").show();
                 setTimeout(function() {
				window.location.href = b.input
				}, 6E3)
			}
		}
	}));
	return !1
}

function changemail_validForm(a) {
	$(".has-error", a).removeClass("has-error");
	var d = 0,
		c = [];
	$(a).find("input.required,textarea.required,select.required,div.required").each(function() {
		var b = $(this).prop("tagName");
		"INPUT" != b && "TEXTAREA" != b || "password" == $(a).prop("type") || "radio" == $(a).prop("type") || "checkbox" == $(a).prop("type") || $(this).val(trim(strip_tags($(this).val())));
		if (!validCheck(this)) return d++, $(".tooltip-current", a).removeClass("tooltip-current"), $(this).addClass("tooltip-current").attr("data-current-mess", $(this).attr("data-mess")), validErrorShow(this), !1
	});
	d || (c.type = $(a).prop("method"), c.url = $(a).prop("action"), c.data = $(a).serialize(), formErrorHidden(a), $(a).find("input,button,select,textarea").prop("disabled", !0), $.ajax({
		type: c.type,
		cache: !1,
		url: c.url,
		data: c.data,
		dataType: "json",
		success: function(b) {
			$("[name=vsend]", a).val("0");
			"error" == b.status ? ($("input,button,select,textarea", a).prop("disabled", !1), $(".tooltip-current", a).removeClass("tooltip-current"), $(a).find("[name=" + b.input + "]").each(function() {
				$(this).addClass("tooltip-current").attr("data-current-mess", b.mess);
				validErrorShow(this)
			})) : ($(".nv-info", a).html(b.mess + '<span class="load-bar"></span>').removeClass("error").addClass("success").show(), ("ok" == b.input ? setTimeout(function() {
				$(".nv-info", a).fadeOut();
				$("input,button,select,textarea", a).prop("disabled", !1)
			}, 6E3) : $("html, body").animate({
				scrollTop: $(".nv-info", a).offset().top
			}, 800, function() {
				$(".form-detail", a).hide();
				setTimeout(function() {
					window.location.href = "" != b.input ? b.input : window.location.href
				}, 6E3)
			})))
		}
	}));
	return !1
}

function bt_logout(a) {
	$(a).prop("disabled", !0);
	$.ajax({
		type: 'POST',
		cache: !1,
		url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=users&' + nv_fc_variable + '=logout&nocache=' + new Date().getTime(),
		data: 'nv_ajax_login=1',
		dataType: 'html',
		success: function(e) {
			$('.userBlock', $(a).parent().parent().parent().parent()).hide();
			$('.nv-info', $(a).parent().parent().parent().parent()).addClass("text-center success").html(e).show();
			setTimeout(function() {
				window.location.href = window.location.href
			}, 2E3)
		}
	});
	return !1
}
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
	uploadGuide: 'guide',
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
	bytes2Size: function(bytes) {
		var sizes = ['Bytes', 'KB', 'MB'];
		if (bytes == 0) return 'n/a';
		var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
		return (bytes / Math.pow(1024, i)).toFixed(1) + ' ' + sizes[i];
	},
	update: function(e) {
		$('#' + UAV.config.x1).val(e.x);
		$('#' + UAV.config.y1).val(e.y);
		$('#' + UAV.config.x2).val(e.x2);
		$('#' + UAV.config.y2).val(e.y2);
	},
	clear: function(e) {
		$('#' + UAV.config.x1).val(0);
		$('#' + UAV.config.y1).val(0);
		$('#' + UAV.config.x2).val(0);
		$('#' + UAV.config.y2).val(0);
	}
};
// Please use this package with Jcrop http://deepliquid.com/content/Jcrop.html
UAV.common = {
	read: function(file) {
		$('#' + UAV.config.uploadIcon).hide();
		var fRead = new FileReader();
		fRead.onload = function(e) {
			$('#' + UAV.config.target).show();
			$('#' + UAV.config.target).attr('src', e.target.result);
			$('#' + UAV.config.target).load(function() {
				var img = document.getElementById(UAV.config.target);
				if (img.naturalWidth > UAV.config.max_width || img.naturalHeight > UAV.config.max_height) {
					UAV.common.error(UAV.lang.bigsize);
					UAV.data.error = true;
					return false;
				}
				if (img.naturalWidth < UAV.config.avatar_width || img.naturalHeight < UAV.config.avatar_height) {
					UAV.common.error(UAV.lang.smallsize);
					UAV.data.error = true;
					return false;
				}
				if (!UAV.data.error) {
					// Hide and show data
					$('#' + UAV.config.uploadGuide).hide();
					$('#' + UAV.config.uploadInfo).show();
					$('#' + UAV.config.imageType).html(file.type);
					$('#' + UAV.config.imageSize).html(UAV.tool.bytes2Size(file.size));
					$('#' + UAV.config.originalDimension).html(img.naturalWidth + ' x ' + img.naturalHeight);
					$('#' + UAV.config.target).Jcrop({
						minSize: [UAV.config.avatar_width, UAV.config.avatar_height],
						setSelect: [300, 300, 55, 55],
						aspectRatio: 1,
						bgFade: true,
						bgOpacity: .3,
						onChange: function(e) {
							UAV.tool.update(e);
						},
						onSelect: function(e) {
							UAV.tool.update(e);
						},
						onRelease: function(e) {
							UAV.tool.clear(e);
						}
					}, function() {
						var bounds = this.getBounds();
						$('#' + UAV.config.w).val(bounds[0]);
						$('#' + UAV.config.h).val(bounds[1]);
						$('#' + UAV.config.displayDimension).html(bounds[0] + ' x ' + bounds[1]);
						UAV.data.jcropApi = this;
					});
				} else {
					$('#' + UAV.config.uploadIcon).show();
				}
			});
		};
		fRead.readAsDataURL(file);
	},
	init: function() {
		UAV.data.error = false;
		if ($('#' + UAV.config.inputFile).val() == '') {
			UAV.data.error = true;
		}
		var image = $('#' + UAV.config.inputFile)[0].files[0];
		// Check ext
		if (!UAV.config.pattern.test(image.type)) {
			UAV.common.error(UAV.lang.filetype);
			UAV.data.error = true;
		}
		// Check size
		if (image.size > UAV.config.maxsize) {
			UAV.common.error(UAV.lang.bigfile);
			UAV.data.error = true;
		}
		if (!UAV.data.error) {
			// Read image
			UAV.common.read(image);
		}
	},
	error: function(e) {
		UAV.common.reset();
		alert(e);
	},
	reset: function() {
		if (UAV.data.jcropApi != null) {
			UAV.data.jcropApi.destroy();
		}
		UAV.data.error = false;
		UAV.data.busy = false;
		UAV.tool.clear();
		$('#' + UAV.config.target).removeAttr('src').removeAttr('style').hide();
		$('#' + UAV.config.uploadIcon).show();
		$('#' + UAV.config.uploadInfo).hide();
		$('#' + UAV.config.uploadGuide).show();
		$('#' + UAV.config.imageType).html('');
		$('#' + UAV.config.imageSize).html('');
		$('#' + UAV.config.originalDimension).html('');
		$('#' + UAV.config.w).val('');
		$('#' + UAV.config.h).val('');
		$('#' + UAV.config.displayDimension).html('');
	},
	submit: function() {
		if (!UAV.data.busy) {
			if ($('#' + UAV.config.x2).val() == '' || $('#' + UAV.config.x2).val() == '0') {
				alert(UAV.lang.upload);
				return false;
			}
			UAV.data.busy = true;
			return true;
		}
		return false;
	}
};
UAV.init = function() {
	$('#' + UAV.config.uploadIcon).click(function() {
		$('#' + UAV.config.inputFile).trigger('click');
	});
	$('#' + UAV.config.inputFile).change(function() {
		UAV.common.init();
	});
	$('#' + UAV.config.btnReset).click(function() {
		if (!UAV.data.busy) {
			UAV.common.reset();
			$('#' + UAV.config.uploadIcon).trigger('click');
		}
	});
	$('#' + UAV.config.uploadForm).submit(function() {
		return UAV.common.submit();
	});
};