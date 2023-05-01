<!-- BEGIN: main -->
<div id="inform" class="inform row">
    <div class="col-md-14">
        <form method="post" action="{FORM_ACTION}" class="form-horizontal configs">
            <input type="hidden" name="save" value="1">
            <div class="form-group">
                <label class="col-xs-18 control-label">{LANG.inform_active}</label>
                <div class="col-xs-6">
                    <input class="form-control" style="margin-top: 7px;" type="checkbox" name="inform_active" value="1" {DATA.inform_active_checked}>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-18 control-label">{LANG.inform_default_exp}</label>
                <div class="col-xs-6">
                    <input type="text" class="form-control" name="inform_default_exp" value="{DATA.inform_default_exp}" maxlength="3">
                    <div class="field-invalid">{LANG.field_required}</div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-18 control-label">{LANG.inform_exp_del}</label>
                <div class="col-xs-6">
                    <input type="text" class="form-control" name="inform_exp_del" value="{DATA.inform_exp_del}" maxlength="3">
                    <div class="field-invalid">{LANG.field_required}</div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-18 control-label">{LANG.inform_refresh_time}</label>
                <div class="col-xs-6">
                    <input type="text" class="form-control" name="inform_refresh_time" value="{DATA.inform_refresh_time}" maxlength="3">
                    <div class="field-invalid">{LANG.field_required}</div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-18 control-label">{LANG.inform_max_characters}</label>
                <div class="col-xs-6">
                    <input type="text" class="form-control" name="inform_max_characters" value="{DATA.inform_max_characters}" maxlength="4">
                    <div class="field-invalid">{LANG.field_required}</div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-18 control-label">{LANG.inform_numrows}</label>
                <div class="col-xs-6">
                    <input type="text" class="form-control" name="inform_numrows" value="{DATA.inform_numrows}" maxlength="3">
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