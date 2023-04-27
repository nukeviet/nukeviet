<!-- BEGIN: main -->
<form action="{FORM_ACTION}" method="post" class="list ajax-submit" id="custom-values-settings">
    <!-- BEGIN: loop -->
    <div class="panel panel-primary item">
        <div class="panel-heading">
            <div class="d-flex justify-content-between align-items-center">
                <strong>{LANG.custom}</strong>
                <div style="margin-left: 5px;margin-top:-3px;margin-bottom:-3px">
                    <button type="button" class="btn btn-primary btn-sm active add-item" title="{GLANG.add}"><em class="fa fa-plus fa-fw"></em></button>
                    <button type="button" class="btn btn-primary btn-sm active del-item" title="{GLANG.delete}"><em class="fa fa-times fa-fw"></em></button>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <div class="row" style="padding-bottom:1px">
                <div class="col-md-8">
                    <div class="form-group">
                        <label><strong>{LANG.config_key}</strong></label>
                        <input type="text" class="form-control required anphanumeric" name="config_key[]" value="{CUSTOM.key}" maxlength="30">
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label><strong>{LANG.config_value}</strong></label>
                        <input type="text" class="form-control required" name="config_value[]" value="{CUSTOM.value}">
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label><strong>{LANG.config_description}</strong></label>
                        <input type="text" class="form-control" name="config_description[]" value="{CUSTOM.description}">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: loop -->
    <div class="text-center mb-lg">
        <input type="hidden" name="checkss" value="{CHECKSS}" />
        <input type="submit" value="{LANG.submit}" class="btn btn-primary" />
    </div>
    <div class="help-block">
        {LANG.custom_configs_note}<br/>
        <strong>{LANG.config_key}</strong>: {LANG.config_key_note}
    </div>
</form>
<!-- END: main -->