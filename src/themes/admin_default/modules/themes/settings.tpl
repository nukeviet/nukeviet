<!-- BEGIN: main -->
<form method="post" action="{FORM_ACTION}">
    <input type="hidden" name="tokend" value="{TOKEND}">
    <div class="panel panel-default">
        <div class="panel-heading">
            <p><strong>{LANG.settings_utheme}</strong></p>
            <small>{LANG.settings_utheme_help}</small>
        </div>
        <div class="panel-body form-horizontal">
            <div class="row">
                <div class="col-sm-16 col-md-18 col-lg-14 col-sm-offset-8 col-md-offset-6">
                    <div class="alert alert-info">
                        <a href="{LINK_SET_CONFIG}">{LANG.settings_utheme_note}</a>. {LANG_MESSAGE}.
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-8 col-md-6 control-label"><strong>{LANG.settings_utheme_choose}:</strong></label>
                <div class="col-sm-16 col-md-18 col-lg-14">
                    <!-- BEGIN: loop_theme -->
                    <div class="checkbox">
                        <label><input type="checkbox" name="user_allowed_theme[]" value="{USER_ALLOWED_THEME.key}"{USER_ALLOWED_THEME.checked}{USER_ALLOWED_THEME.disabled}> {USER_ALLOWED_THEME.title}</label><br />
                    </div>
                    <!-- END: loop_theme -->
                </div>
            </div>
        </div>
    </div>
    <div class="text-center">
        <button type="submit" class="btn btn-primary">{GLANG.submit}</button>
    </div>
</form>
<!-- END: main -->
