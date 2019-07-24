<div class="card card-border-color card-border-color-primary">
    <div class="card-body">
        <form method="post" action="{$FORM_ACTION}" autocomplete="off">
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="themename">{$LANG->get('autoinstall_method_theme_none')}</label>
                <div class="col-12 col-sm-7 col-md-5 col-lg-4 col-xl-3">
                    <select class="form-control form-control-sm" id="themename" name="themename">
                        <option value="0">{$LANG->get('autoinstall_method_theme_none')}</option>
                        {foreach from=$THEME_LIST key=key item=value}
                        <option value="{$value}">{$value}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right">{$LANG->get('autoinstall_method_module_none')}</label>
                <div class="col-12 col-sm-10 col-lg-8 mt-1">
                    <div class="row">
                        {foreach from=$ARRAY_MODULE item=module_j}
                        <div class="col-12 col-sm-6">
                            <label class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" name="module_file[]" value="{$module_j.module_file}"><span class="custom-control-label">{$module_j.custom_title}</span>
                            </label>
                        </div>
                        {/foreach}
                        {foreach from=$MODULES_LIST item=module_i}
                        {if not in_array($module_i, $ARRAY_MODULE_SEUP)}
                        <div class="col-12 col-sm-6">
                            <label class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" name="module_file[]" value="{$module_i}"><span class="custom-control-label">{$module_i}</span>
                            </label>
                        </div>
                        {/if}
                        {/foreach}
                    </div>
                </div>
            </div>
            <div class="form-group row mb-0 pb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right"></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <button class="btn btn-space btn-primary" type="button" name="continue_ptm" data-checksess="{$NV_CHECK_SESSION}">{$LANG->get('autoinstall_continue')}</button>
                    <div class="mt-4 d-none" data-toggle="resarea">
                        <div class="res-load d-none" data-toggle="resload"><i class="fas fa-spinner fa-pulse"></i> {$LANG->get('autoinstall_package_processing')}</div>
                        <div class="res-html d-none" data-toggle="reshtml"></div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
LANG.error = "{$LANG->get('error')}";
LANG.autoinstall_package_processing = "{$LANG->get('autoinstall_package_processing')}";
LANG.package_noselect_module_theme = "{$LANG->get('package_noselect_module_theme')}";
</script>
