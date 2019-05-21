<div role="alert" class="alert alert-primary alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="fas fa-info-circle"></i></div>
    <div class="message">{$LANG->get('login_creat_merchant')}</div>
</div>
<div class="card card-border-color card-border-color-primary">
    <div class="card-body">
        <form id="login-form" method="post" action="{$NV_BASE_ADMINURL}index.php" autocomplete="off">
            <input type="hidden" name="{$NV_LANG_VARIABLE}" value="{$NV_LANG_DATA}">
            <input type="hidden" name="{$NV_NAME_VARIABLE}" value="{$MODULE_NAME}">
            <input type="hidden" name="redirect" value="{$REQUEST.redirect}">
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="username">{$LANG->get('username')}</label>
                <div class="col-12 col-sm-8 col-lg-4">
                    <input type="text" class="form-control form-control-sm" id="username" name="username" value="" autocomplete="new-password">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="password">{$LANG->get('password')}</label>
                <div class="col-12 col-sm-8 col-lg-4">
                    <input type="password" class="form-control form-control-sm" id="password" name="password" value="" autocomplete="new-password">
                </div>
            </div>
            <div class="form-group row mb-0 pb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right"></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <button class="btn btn-space btn-primary" type="submit"><span class="load d-none"><i class="fa fa-spin fa-spinner"></i> </span>{$LANG->get('loginsubmit')}</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
var LANG = [];
LANG.username_empty = '{$LANG->get('username_empty')}';
LANG.password_empty = '{$LANG->get('password_empty')}';
LANG.error = '{$LANG->get('error')}';
</script>

{*
<!-- BEGIN: main -->
<blockquote>
    <p>{LANG.login_creat_merchant}</p>
</blockquote>
<form class="form-horizontal m-bottom" role="form" action="" method="post">
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
*}
