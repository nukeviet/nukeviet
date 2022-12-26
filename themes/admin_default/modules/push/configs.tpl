<!-- BEGIN: main -->
<div id="push" class="push row">
    <div class="col-md-14">
        <form method="post" action="{FORM_ACTION}" class="form-horizontal configs">
            <input type="hidden" name="save" value="1">
            <div class="form-group">
                <label class="col-xs-18 control-label">{LANG.push_active}</label>
                <div class="col-xs-6">
                    <input class="form-control" style="margin-top: 7px;" type="checkbox" name="push_active" value="1" {DATA.push_active_checked}>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-18 control-label">{LANG.push_default_exp}</label>
                <div class="col-xs-6">
                    <input type="text" class="form-control" name="push_default_exp" value="{DATA.push_default_exp}" maxlength="3">
                    <div class="field-invalid">{LANG.field_required}</div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-18 control-label">{LANG.push_exp_del}</label>
                <div class="col-xs-6">
                    <input type="text" class="form-control" name="push_exp_del" value="{DATA.push_exp_del}" maxlength="3">
                    <div class="field-invalid">{LANG.field_required}</div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-18 control-label">{LANG.push_refresh_time}</label>
                <div class="col-xs-6">
                    <input type="text" class="form-control" name="push_refresh_time" value="{DATA.push_refresh_time}" maxlength="3">
                    <div class="field-invalid">{LANG.field_required}</div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-18 control-label">{LANG.push_max_characters}</label>
                <div class="col-xs-6">
                    <input type="text" class="form-control" name="push_max_characters" value="{DATA.push_max_characters}" maxlength="4">
                    <div class="field-invalid">{LANG.field_required}</div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-18 control-label">{LANG.push_numrows}</label>
                <div class="col-xs-6">
                    <input type="text" class="form-control" name="push_numrows" value="{DATA.push_numrows}" maxlength="3">
                    <div class="field-invalid">{LANG.field_required}</div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-offset-18 col-xs-6">
                    <button type="submit" class="btn btn-primary">{GLANG.submit}</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- END: main -->