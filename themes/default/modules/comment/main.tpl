<!-- BEGIN: main -->
<!-- BEGIN: header -->
<script type="text/javascript" src="{NV_BASE_SITEURL}themes/{TEMPLATE_JS}/js/comment.js"></script>
<link rel="StyleSheet" href="{NV_BASE_SITEURL}themes/{TEMPLATE_CSS}/css/comment.css" type="text/css" />
<!-- END: header -->

<div id="idcomment" class="nv-fullbg">
	<div class="row clearfix margin-bottom-lg">
		<div class="col-xs-12 text-left">
			<p class="comment-title"><em class="fa fa-comments">&nbsp;</em> {LANG.comment}</p>
		</div>
		<div class="col-xs-12 text-right">
			<select id="sort" class="form-control">
				<!-- BEGIN: sortcomm -->
				<option value="{OPTION.key}" {OPTION.selected}>{OPTION.title}</option>
				<!-- END: sortcomm -->
			</select>
		</div>
	</div>
    <!-- BEGIN: showContent -->
    <div class="margin-bottom-lg">
        <button type="button" class="btn btn-primary btn-xs" onclick="nv_show_hidden('showcomment',2);"><em class="fa fa-caret-square-o-down"></em>&nbsp; {LANG.comment_hide_show}</button>
    </div>
	<div id="showcomment" class="margin-bottom-lg">
		{COMMENTCONTENT}
	</div>
	<!-- END: showContent -->
	<div id="formcomment" class="comment-form">
		<!-- BEGIN: allowed_comm -->

		<!-- BEGIN: comment_result -->
		<div class="alert alert-info" id="alert-info">{STATUS_COMMENT}</div>
		<script type="text/javascript">
			$('#alert-info').delay(5000).fadeOut('slow');
		</script>
		<!-- END: comment_result -->

		<form method="post" role="form" target="submitcommentarea" action="{FORM_ACTION}" data-module="{MODULE_COMM}" data-content="{MODULE_DATA}_commentcontent" data-area="{AREA_COMM}" data-id="{ID_COMM}" data-allowed="{ALLOWED_COMM}" data-checkss="{CHECKSS_COMM}" data-gfxnum="{GFX_NUM}" data-editor="{EDITOR_COMM}"<!-- BEGIN: enctype --> enctype="multipart/form-data"<!-- END: enctype -->>
			<input type="hidden" name="module" value="{MODULE_COMM}"/>
			<input type="hidden" name="area" value="{AREA_COMM}"/>
			<input type="hidden" name="id" value="{ID_COMM}"/>
			<input type="hidden" id="commentpid" name="pid" value="0"/>
			<input type="hidden" name="allowed" value="{ALLOWED_COMM}"/>
			<input type="hidden" name="checkss" value="{CHECKSS_COMM}"/>
			<div class="form-group clearfix">
				<div class="row">
					<div class="col-xs-12">
						<input id="commentname" type="text" name="name" value="{NAME}" {DISABLED} class="form-control" placeholder="{LANG.comment_name}"/>
					</div>
					<div class="col-xs-12">
						<input id="commentemail_iavim" type="text" name="email" value="{EMAIL}" {DISABLED} class="form-control" placeholder="{LANG.comment_email}"/>
					</div>
				</div>
			</div>
			<div class="form-group clearfix">
				<textarea class="form-control" style="width: 100%" name="content" id="commentcontent" cols="20" rows="5"></textarea>
				<!-- BEGIN: editor -->
                <script type="text/javascript" src="{NV_BASE_SITEURL}{NV_EDITORSDIR}/ckeditor/ckeditor.js?t={TIMESTAMP}"></script>
                <script type="text/javascript">
                nv_commment_buildeditor();
                </script>
                <!-- END: editor -->
			</div>
            <!-- BEGIN: attach -->
            <div class="form-group">
                <div class="row">
                    <label class="col-xs-12 col-sm-8 col-md-6 control-label">{LANG.attach}</label>
                    <div class="col-xs-12 col-sm-16 col-md-18">
                        <input type="file" name="fileattach"/>
                    </div>
                </div>
            </div>
            <!-- END: attach -->
			<!-- BEGIN: captcha -->
			<div class="form-group clearfix">
				<div class="row">
					<label class="col-xs-24 hidden-xs">{LANG.comment_seccode}</label>
					<div class="col-xs-12 col-sm-8">
						<img class="captchaImg" alt="{N_CAPTCHA}" src="{SRC_CAPTCHA}" width="{GFX_WIDTH}" height="{GFX_HEIGHT}" />
						&nbsp;<em class="fa fa-pointer fa-refresh fa-lg" onclick="change_captcha('#commentseccode_iavim');">&nbsp;</em>
					</div>
					<div class="col-xs-12">
						<input id="commentseccode_iavim" type="text" class="form-control" maxlength="{GFX_NUM}" name="code"/>
					</div>
				</div>
			</div>
			<!-- END: captcha -->
            <!-- BEGIN: recaptcha -->
            <div class="form-group clearfix">
                <div class="nv-recaptcha-default"><div id="{RECAPTCHA_ELEMENT}"></div></div>
                <script type="text/javascript">
                nv_recaptcha_elements.push({
                    id: "{RECAPTCHA_ELEMENT}",
                    btn: $("#buttoncontent", $('#{RECAPTCHA_ELEMENT}').parent().parent().parent())
                })
                </script>
            </div>
            <!-- END: recaptcha -->
			<div class="form-group text-center">
				<input id="reset-cm" type="button" value="{GLANG.reset}" class="btn btn-default" />
				<input id="buttoncontent" type="submit" value="{LANG.comment_submit}" class="btn btn-primary" />
			</div>
		</form>
        <iframe class="hidden" id="submitcommentarea" name="submitcommentarea"></iframe>
		<script type="text/javascript">
		$("#reset-cm").click(function() {
			$("#commentcontent,#commentseccode_iavim").val("");
			$("#commentpid").val(0);
		});
		</script>
		<!-- END: allowed_comm -->
		<!-- BEGIN: form_login-->
		<div class="alert alert-danger fade in">
			<!-- BEGIN: message_login -->
			<a title="{GLANG.loginsubmit}" href="#" onclick="return loginForm('');">{LOGIN_MESSAGE}</a>
			<!-- END: message_login -->

			<!-- BEGIN: message_register_group -->
			{LANG_REG_GROUPS}
			<!-- END: message_register_group -->
		</div>
		<!-- END: form_login -->
	</div>
</div>
<script type="text/javascript">
var nv_url_comm = '{BASE_URL_COMM}';
$("#sort").change(function() {
	$.post(nv_url_comm + '&nocache=' + new Date().getTime(), 'sortcomm=' + $('#sort').val() , function(res) {
		$('#idcomment').html(res);
	});
});
</script>
<!-- END: main -->
