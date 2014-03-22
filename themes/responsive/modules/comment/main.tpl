<!-- BEGIN: main -->
<div id="idcomment" class="nv-fullbg">
	<div class="row">
		<hr />
		<div class="col-xs-6 text-left">
			<p class="comment-title"><em class="fa fa-comments">&nbsp;</em> {LANG.comment}</p>
		</div>
		<div class="col-xs-6 text-right">
			<div class="btn-group">
				<button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
					{LANG.sortcomm}
					<span class="caret">&nbsp;</span>
				</button>
				<ul class="dropdown-menu text-left sortcomm">
					<!-- BEGIN: sortcomm -->
					<li><a href="#"<!-- BEGIN: active --> class="active"<!-- END: active --> onclick="window.location='{BASE_URL_COMM}&sortcomm={OPTION.key}';">{OPTION.title}</a></li>
					<!-- END: sortcomm -->
				</ul>
			</div>
			<span class="showhiecom"><em class="fa fa-caret-square-o-down">&nbsp;</em> <a onclick="nv_show_hidden('showcomment',2);" href="javascript:void(0);" id="show-comments">{LANG.comment_send}</a></span>
		</div>
	</div>
	<div id="showcomment">
		{COMMENTCONTENT}
	</div>
	<hr />
	<div id="formcomment" class="comment-form">
		<!-- BEGIN: allowed_comm -->
		<form method="post" role="form" onsubmit="return false;">
			<input type="hidden" id="commentpid" value="0"/>
			<div class="form-group clearfix">
				<div class="col-xs-6">
					<input id="commentname" type="text" value="{NAME}" {DISABLED} class="form-control" placeholder="{LANG.comment_name}"/>
				</div>
				<div class="col-xs-6">
					<input id="commentemail_iavim" type="text" value="{EMAIL}" {DISABLED} class="form-control" placeholder="{LANG.comment_email}"/>
				</div>
			</div>
			<div class="form-group clearfix">
				<div class="col-xs-12">
					<textarea id="commentcontent" class="form-control" cols="60" rows="3" placeholder="{LANG.comment_content}"></textarea>
				</div>
			</div>
			<div class="form-group clearfix">
				<label class="col-xs-3">{LANG.comment_seccode}</label>
				<div class="col-xs-4">
					<img id="vimg" alt="{N_CAPTCHA}" src="{SRC_CAPTCHA}" width="{GFX_WIDTH}" height="{GFX_HEIGHT}" />
					&nbsp;<em class="fa fa-pointer fa-refresh fa-lg" onclick="nv_change_captcha('vimg','commentseccode_iavim');">&nbsp;</em>
				</div>
				<div class="col-xs-5">
					<input id="commentseccode_iavim" type="text" class="form-control" maxlength="{GFX_NUM}"/>
				</div>
			</div>
			<div class="form-group text-center">
				<input id="buttoncontent" type="submit" value="{LANG.comment_submit}" onclick="sendcommment('{MODULE_COMM}', '{AREA_COMM}', '{ID_COMM}', '{ALLOWED_COMM}', '{CHECKSS_COMM}', '{GFX_NUM}');" class="btn btn-primary" />
				<input id="reset-cm" type="reset" value="RESET" class="btn btn-default" />
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
			<em data-dismiss="alert" aria-hidden="true" class="fa fa-times fa-fix fa-pointer pull-right fa-lg">&nbsp;</em>
			{COMMENT_LOGIN}
		</div>
		<!-- END: form_login -->
	</div>
</div>
<!-- END: main -->