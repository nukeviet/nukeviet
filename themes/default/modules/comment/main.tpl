<!-- BEGIN: main -->
<!-- BEGIN: header -->
<!-- BEGIN: jsfile -->
<script type="text/javascript" src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/comment.js"></script>
<!-- END: jsfile -->
<!-- BEGIN: cssfile -->
<link rel="StyleSheet" href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/comment.css" type="text/css" />
<!-- END: cssfile -->
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

		<form method="post" role="form" onsubmit="return false;">
			<input type="hidden" id="commentpid" value="0"/>
			<div class="form-group clearfix">
				<div class="col-xs-12">
					<input id="commentname" type="text" value="{NAME}" {DISABLED} class="form-control" placeholder="{LANG.comment_name}"/>
				</div>
				<div class="col-xs-12">
					<input id="commentemail_iavim" type="text" value="{EMAIL}" {DISABLED} class="form-control" placeholder="{LANG.comment_email}"/>
				</div>
			</div>
			<div class="form-group clearfix">
				<div class="col-xs-24">
					<textarea class="form-control" style="width: 100%" name="commentcontent" id="commentcontent" cols="20" rows="5"></textarea>
				</div>
			</div>
			<!-- BEGIN: captcha -->
			<div class="form-group clearfix">
				<label class="col-xs-6">{LANG.comment_seccode}</label>
				<div class="col-xs-8">
					<img class="captchaImg" alt="{N_CAPTCHA}" src="{SRC_CAPTCHA}" width="{GFX_WIDTH}" height="{GFX_HEIGHT}" />
					&nbsp;<em class="fa fa-pointer fa-refresh fa-lg" onclick="change_captcha('#commentseccode_iavim');">&nbsp;</em>
				</div>
				<div class="col-xs-10">
					<input id="commentseccode_iavim" type="text" class="form-control" maxlength="{GFX_NUM}"/>
				</div>
			</div>
			<!-- END: captcha -->
			<div class="form-group text-center">
				<input id="reset-cm" type="button" value="{GLANG.reset}" class="btn btn-default" />
				<input id="buttoncontent" type="button" value="{LANG.comment_submit}" onclick="sendcommment('{MODULE_COMM}', '{MODULE_DATA}_commentcontent', '{AREA_COMM}', '{ID_COMM}', '{ALLOWED_COMM}', '{CHECKSS_COMM}', {GFX_NUM});" class="btn btn-primary" />
			</div>
		</form>
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
			<a title="{GLANG.loginsubmit}" href="#" onclick="return loginForm();">{LANG.comment_login}</a>
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
