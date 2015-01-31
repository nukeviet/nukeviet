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
			<input type="password" class="form-control" id="password" name="password"/>
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
$(function(){
	$('#login-form').submit(function(e){
		e.preventDefault();
		var username = $('#username').val();
		var password = $('#password').val();
		$('#login-result').html('');
		
		if( username == '' ){
			$('#login-result').html('<div class="alert alert-danger">{GLANG.username_empty}</div>');
		}else if( password == '' ){
			$('#login-result').html('<div class="alert alert-danger">{GLANG.password_empty}</div>');
		}else{
			$('#login-form input, #login-form button').attr('disabled', 'disabled');
			$('#login-result').html('<div class="text-center"><em class="fa fa-spin fa-spinner fa-2x m-bottom wt-icon-loading"></em></div>');
			
			$.post(
				script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=login&nocache=' + new Date().getTime(), 
				'username=' + username + '&password=' + password + '&redirect=' + $('[name="redirect"]').val(), 
				function(res) {
					$('#login-form input, #login-form button').removeAttr('disabled');
					$('#login-result').html( res );
				}
			);
		}
	});
});
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