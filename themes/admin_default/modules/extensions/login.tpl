<!-- BEGIN: main -->
<blockquote>
	<p>{LANG.login_creat_merchant}</p>
</blockquote>
<form id="login-form" class="form-horizontal m-bottom" role="form" action="{NV_BASE_ADMINURL}index.php" method="post">
	<input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}"/>
	<input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}"/>
	<input type="hidden" name="redirect" value="{REQUEST.redirect}"/>
	<div class="form-group">
		<label for="username" class="col-sm-4 control-label">{GLANG.username}</label>
		<div class="col-sm-20 col-lg-4">
			<input type="text" class="form-control" id="username" name="username"/>
		</div>
	</div>
	<div class="form-group">
		<label for="password" class="col-sm-4 control-label">{GLANG.password}</label>
		<div class="col-sm-20 col-lg-4">
			<input type="password" autocomplete="off" class="form-control" id="password" name="password"/>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-4 col-sm-20">
			<button type="submit" name="submit" class="btn btn-primary">{GLANG.loginsubmit}</button>
		</div>
	</div>
</form>
<div id="login-result"></div>
<script type="text/javascript">
var LANG = [];
LANG.username_empty = '{GLANG.username_empty}';
LANG.password_empty = '{GLANG.password_empty}';
</script>
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<!-- BEGIN: ok -->
<div class="alert alert-success">
	{LANG.login_success}
</div>
<script type="text/javascript">
setTimeout( function(){
	window.location = '{REDIRECT_LINK}';
}, 2000 );
</script>
<!-- END: ok -->
<!-- END: main -->