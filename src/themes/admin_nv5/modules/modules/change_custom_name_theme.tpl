<div class="card card-border-color card-border-color-primary">
    <div class="card-header card-header-divider">
        {$CAPTION}
    </div>
    <div class="card-body">
        <div class="form-group row">
            <label class="col-12 col-sm-3 col-form-label text-sm-right" for="func_custom_name">{$LANG->get('funcs_custom_title')} <i class="text-danger">(*)</i></label>
            <div class="col-12 col-sm-8 col-lg-6">
                <input type="text" class="form-control form-control-sm" id="func_custom_name" name="func_custom_name" value="{$FUNC_CUSTOM_NAME}" maxlength="250">
            </div>
        </div>
        <div class="form-group row mb-0 pb-0">
            <label class="col-12 col-sm-3 col-form-label text-sm-right"></label>
            <div class="col-12 col-sm-8 col-lg-6">
                <a class="btn btn-space btn-primary" href="javascript:void(0);" onclick="nv_change_custom_name_submit({$FUN_ID}, 'func_custom_name');">{$LANG->get('submit')}</a>
                <a class="btn btn-space btn-secondary" href="javascript:void(0);" onclick="nv_action_cancel('show_funcs_action');">{$LANG->get('cancel')}</a>
            </div>
        </div>
    </div>
</div>
