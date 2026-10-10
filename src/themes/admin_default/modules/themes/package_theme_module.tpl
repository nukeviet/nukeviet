<form id="pkgThemeMod" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" method="post" data-checkss="{$CHECKSS}" data-error="{$LANG->getModule('package_noselect_module_theme')}">
    <div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0">
        <div class="card-body pt-4">
            <div class="row mb-3">
                <label for="element_themename" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('autoinstall_method_theme_none')}</label>
                <div class="col-sm-4 col-lg-6 col-xxl-5">
                    <select class="form-select w-auto mw-100" id="element_themename" name="themename">
                        <option value="0">{$LANG->getModule('autoinstall_method_theme_none')}</option>
                        {foreach from=$ARRAY_THEMES item=theme}
                        <option value="{$theme}">{$theme}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('autoinstall_method_module_none')}</div>
                <div class="col-sm-4 col-lg-6 col-xxl-5">
                    {foreach from=$ARRAY_MODULES key=key item=module}
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="mfile_{$key}" name="module_file[]" value="{$module.module_file}">
                        <label class="form-check-label" for="mfile_{$key}">{$module.custom_title}</label>
                    </div>
                    {/foreach}
                </div>
            </div>
            <div class="row">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                    <input type="hidden" name="checkss" value="{$CHECKSS}">
                    <button type="submit" class="btn btn-primary">{$LANG->getGlobal('submit')}</button>
                </div>
            </div>
            <div class="row mt-3 d-none" id="pkgThemeModLoader">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                    <i class="fa-solid fa-spinner fa-spin-pulse"></i> <span data-toggle="pkgLoad">{$LANG->getGlobal('wait_page_load')}</span>
                </div>
            </div>
            <div class="row mt-3 d-none" id="pkgThemeModResult">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-3 text-break" data-toggle="pkgRes">
                </div>
            </div>
        </div>
    </div>
</form>
