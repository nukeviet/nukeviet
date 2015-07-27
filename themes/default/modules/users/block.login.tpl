<!-- BEGIN: main -->
<div id="nv-block-login" class="text-center">
	<em class="fa fa-sign-in">&nbsp;</em> <a href="{USER_LOGIN}" class="login">{LANG.loginsubmit}</a> 
	<!-- BEGIN: allowuserreg_link -->
	<em class="fa fa-user-plus">&nbsp;</em> <a href="{USER_REGISTER}" class="register">{LANG.register}</a> 
	<!-- END: allowuserreg_link -->
</div>
<!-- START FORFOOTER -->
<div class="modal fade" id="loginModal">
  <div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="{LANG.cancel}"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title">{LANG.loginsubmit}</h4>
	  </div>
	  <div class="modal-body">
		<div class="container-fluid">
			<form action="" method="post" role="form" class="form-tooltip">
				<div class="form-group">
					<div class="input-group">
						<span class="input-group-addon"><em class="fa fa-user fa-lg"></em></span>
						<input type="text" class="form-control" id="block_login_iavim" name="nv_login" value="" placeholder="{LANG.username}">
					</div>
				</div>
				<div class="form-group">
					<div class="input-group">
						<span class="input-group-addon"><em class="fa fa-key fa-lg fa-fix"></em></span>
						<input type="password" class="form-control" id="block_password_iavim" name="nv_password" value="" placeholder="{LANG.password}">
					</div>
				</div>
				<!-- BEGIN: captcha_login -->
				<div class="form-group text-right">
					<img class="captchaImg" src="{NV_BASE_SITEURL}index.php?scaptcha=captcha&t={NV_CURRENTTIME}" width="{GFX_WIDTH}" height="{GFX_HEIGHT}"/>
					<em class="fa fa-pointer fa-refresh fa-lg" onclick="change_captcha('#block_seccode_iavim');"></em>
				</div>
				<div class="form-group">
					<div class="input-group">
						<span class="input-group-addon"><em class="fa fa-shield fa-lg fa-fix"></em></span>
						<input id="block_seccode_iavim" name="nv_seccode" type="text" class="form-control" maxlength="{GFX_MAXLENGTH}" placeholder="{LANG.securitycode}"/>
					</div>
				</div>
				<!-- END: captcha_login -->
				<div class="form-group">
					<a class="pull-right" title="{LANG.lostpass}" href="{USER_LOSTPASS}">{LANG.lostpass}?</a>
				</div>
				<!-- BEGIN: openid -->
				<div class="clearfix">
					<hr />
					<p class="text-center">
						<i class="fa fa-openid"></i> {LANG.openid_login}
					</p>
					<div class="text-center">
						<!-- BEGIN: server -->
						<a title="{OPENID.title}" href="{OPENID.href}">
					 		<img alt="{OPENID.title}" src="{OPENID.img_src}" width="{OPENID.img_width}" height="{OPENID.img_height}" data-toggle="tooltip" data-placement="top" title="{OPENID.title}"/>
						</a>
						<!-- END: server -->
					</div>
				</div>
				<!-- END: openid -->
			</form>
		</div>
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">{LANG.cancel}</button>
		<button type="button" class="btn btn-primary" id="block-login-submit">{LANG.loginsubmit}</button>
	  </div>
	</div>
  </div>
</div>
<!-- BEGIN: allowuserreg_html -->
<div class="modal fade" id="registerModal">
  <div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="{LANG.cancel}"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title">{LANG.register}</h4>
	  </div>
	  <div class="modal-body">
		<div class="container-fluid">
			<form id="registerForm" action="" method="post" role="form" class="form-horizontal form-tooltip m-bottom">
				<div class="form-group">
					<label for="first_name" class="col-sm-8 control-label">{LANG.first_name}:</label>
					<div class="col-sm-16">
						<input type="text" class="form-control" id="first_name" name="first_name" value="" maxlength="255" />
					</div>
				</div>
				<div class="form-group">
					<label for="last_name" class="col-sm-8 control-label">{LANG.last_name}:</label>
					<div class="col-sm-16">
						<input type="text" class="form-control" id="last_name" name="last_name" value="" maxlength="255" />
					</div>
				</div>
				<div class="form-group">
					<label for="nv_email_iavim" class="col-sm-8 control-label">{LANG.email}<span class="text-danger"> (*)</span>:</label>
					<div class="col-sm-16">
						<input type="email" class="email required form-control" name="email" value="" id="nv_email_iavim" maxlength="100" />
					</div>
				</div>
				<div class="form-group">
					<label for="nv_username_iavim" class="col-sm-8 control-label">{LANG.username}<span class="text-danger"> (*)</span>:</label>
					<div class="col-sm-16">
						<input type="text" class="required form-control" name="username" value="" id="nv_username_iavim" maxlength="{NICK_MAXLENGTH}" />
					</div>
				</div>
				<div class="form-group">
					<label for="nv_password_iavim" class="col-sm-8 control-label">{LANG.password}<span class="text-danger"> (*)</span>:</label>
					<div class="col-sm-16">
						<input class="form-control required password" name="password" value="" id="nv_password_iavim" type="password" maxlength="{PASS_MAXLENGTH}" autocomplete="off"/>
					</div>
				</div>
				<div class="form-group">
					<label for="nv_re_password_iavim" class="col-sm-8 control-label">{LANG.password2}<span class="text-danger"> (*)</span>:</label>
					<div class="col-sm-16">
						<input class="form-control required password" name="re_password" value="" id="nv_re_password_iavim" type="password" maxlength="{PASS_MAXLENGTH}" autocomplete="off"/>
					</div>
				</div>
				<div class="form-group">
					<label for="question" class="col-sm-8 control-label">{LANG.question}:</label>
					<div class="col-sm-16">
						<select name="question" id="question" class="form-control"></select>
					</div>
				</div>
				<div class="form-group">
					<label for="your_question" class="col-sm-8 control-label">{LANG.your_question}:</label>
					<div class="col-sm-16">
						<input type="text" class="form-control" name="your_question" id="your_question" value="" />
					</div>
				</div>
				<div class="form-group">
					<label for="answer" class="col-sm-8 control-label">{LANG.answer_your_question}<span class="text-danger"> (*)</span>:</label>
					<div class="col-sm-16">
						<input type="text" class="form-control required" name="answer" id="answer" value="" />
					</div>
				</div>
				<!-- BEGIN: captcha -->
				<div class="form-group">
					<label for="nv_seccode_iavim" class="col-sm-8 control-label">{LANG.securitycode}<span class="text-danger"> (*)</span>:</label>
					<div class="col-sm-8">
						<input type="text" name="nv_seccode" id="nv_seccode_iavim" class="required form-control" maxlength="{GFX_MAXLENGTH}" />
					</div>
					<div class="col-sm-8">
						<label class="control-label">
							<img class="captchaImg" src="{NV_BASE_SITEURL}index.php?scaptcha=captcha&t={NV_CURRENTTIME}" width="{GFX_WIDTH}" height="{GFX_HEIGHT}" />
							&nbsp;<em class="fa fa-pointer fa-refresh fa-lg" onclick="change_captcha('#nv_seccode_iavim');">&nbsp;</em>
						</label>
					</div>
				</div>
				<!-- END: captcha -->
				<div class="form-group">
					<label for="question" class="col-sm-8 control-label"><a id="show-usage-terns" href="">{LANG.usage_terms} <i class="fa fa-globe"></i></a>:</label>
					<div class="col-sm-16">
						<div class="checkbox">
							<label>
								<input class="required" type="checkbox" name="agreecheck" id="agreecheck" value="1"/>
								{LANG.accept}
							</label>
						</div>
					</div>
				</div>
			</div>
	  </div>
	  <div class="modal-footer">
		<input type="hidden" name="checkss" id="checkss" value="{CHECKSESS}" />
		<i id="block-register-loading" class="fa fa-circle-o-notch fa-spin hidden"></i>
		<button type="button" class="btn btn-default" data-dismiss="modal">{LANG.cancel}</button>
		<button type="button" class="btn btn-primary" id="block-register-submit">{LANG.register}</button>
	  </div>
	</div>
  </div>
</div>
<div id="usage-terns" class="modal fade nocontent">
  <div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title">{LANG.usage_terms}</h4>
	  </div>
	  <div class="modal-body">
		<div class="text-center"><i class="fa fa-circle-o-notch fa-spin fa-3x"></i></div>
	  </div>
	</div>
  </div>
</div>
<!-- END: allowuserreg_html -->
<!-- END FORFOOTER -->
<!-- END: main -->
<!-- BEGIN: signed -->
<div class="content signed clearfix">
	<p class="text-center">{LANG.wellcome}:
	    <strong>
	           {USER.full_name}
	    </strong>
	</p>
	<hr />
	<div class="row">
		<div class="col-xs-8 text-center">
			<a title="{LANG.edituser}" href="{CHANGE_INFO}"><img src="{AVATA}" alt="{USER.full_name}" class="img-thumbnail" /></a>
		</div>
		<div class="col-xs-16">
		    <ul class="nv-list-item sm">
		    	<li class="active"><a href="{URL_MODULE}">{LANG.user_info}</a></li>
		    	<li><a href="{URL_HREF}editinfo">{LANG.editinfo}</a></li>
		    	<li><a href="{URL_HREF}changepass">{LANG.changepass_title}</a></li>
		    	<li><a href="{URL_HREF}changequestion">{LANG.question2}</a></li>
		    	<!-- BEGIN: allowopenid --><li><a href="{URL_HREF}openid">{LANG.openid_administrator}</a></li><!-- END: allowopenid -->
		    	<!-- BEGIN: regroups --><li><a href="{URL_HREF}regroups">{LANG.in_group}</a></li><!-- END: regroups -->
		    	<li><a href="{URL_HREF}logout">{LANG.logout_title}</a></li>
		    </ul>
		</div>
	</div>
</div>
<!-- END: signed -->