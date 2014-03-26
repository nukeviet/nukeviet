<!-- BEGIN: main -->
<ul class="nav nav-tabs m-bottom">
	<li><a href="{URL_HREF}main">{LANG.user_info}</a></li>
	<li><a href="{URL_HREF}editinfo">{LANG.editinfo}</a></li>
	<li class="active"><a href="{URL_HREF}changepass">{LANG.changepass_title}</a></li>
	<li><a href="{URL_HREF}editinfo&amp;changequestion">{LANG.question2}</a></li>
	<!-- BEGIN: allowopenid --><li><a href="{URL_HREF}openid">{LANG.openid_administrator}</a></li><!-- END: allowopenid -->
	<!-- BEGIN: regroups --><li><a href="{URL_HREF}regroups">{LANG.in_group}</a></li><!-- END: regroups -->
	<!-- BEGIN: logout --><li><a href="{URL_HREF}logout">{LANG.logout_title}</a></li><!-- END: logout -->
</ul>
<h2>{LANG.change_pass}</h2>
<form action="{USER_CHANGEPASS}" method="post" role="form" class="form-horizontal form-tooltip m-bottom">
	<p class="text-info">
		<em class="fa fa-quote-left">&nbsp;</em> 
		{DATA.change_info}
		<em class="fa fa-quote-right">&nbsp;</em> 
	</p>
	<!-- BEGIN: passEmpty -->
	<div class="form-group">
		<label for="nv_password_iavim" class="col-sm-3 control-label">{LANG.pass_old}<span class="text-danger"> (*)</span>:</label>
		<div class="col-sm-9">
			<input type="password" class="form-control required password" name="nv_password" value="{DATA.nv_password}" id="nv_password_iavim" maxlength="{PASS_MAXLENGTH}"  placeholder="{LANG.pass_old}"/>
		</div>
	</div>
	<!-- END: passEmpty -->
	<div class="form-group">
		<label for="new_password_iavim" class="col-sm-3 control-label">{LANG.pass_new}<span class="text-danger"> (*)</span>:</label>
		<div class="col-sm-9">
			<input type="password" class="form-control required password" name="new_password" value="{DATA.new_password}" id="new_password_iavim" maxlength="{PASS_MAXLENGTH}"  placeholder="{LANG.pass_new}"/>
		</div>
	</div>
	<div class="form-group">
		<label for="re_password_iavim" class="col-sm-3 control-label">{LANG.pass_new_re}<span class="text-danger"> (*)</span>:</label>
		<div class="col-sm-9">
			<input type="password" class="form-control required password" name="re_password" value="{DATA.re_password}" id="re_password_iavim" maxlength="{PASS_MAXLENGTH}"  placeholder="{LANG.pass_new_re}"/>
		</div>
	</div>
	<div class="text-center">
		<input type="hidden" name="checkss" value="{DATA.checkss}" />
		<input type="submit" name="submit" value="{LANG.change_pass}" class="btn btn-primary"/>
	</div>
</form>
<!-- END: main -->