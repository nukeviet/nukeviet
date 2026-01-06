<form id="site-settings" class="row g-4 ajax-submit" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" novalidate>
    <input type="hidden" name="checkss" value="{$DATA.checkss}">
    <div class="col-xxl-6">
        <div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0">
            <div class="card-header py-2" role="button" data-bs-toggle="collapse" data-bs-target="#collapse-general" aria-expanded="true" aria-controls="collapse-general">
                <div class="hstack gap-2 align-items-center justify-content-between">
                    <div class="fw-medium fs-5">{$LANG->getModule('general_settings')}</div>
                    <div class="collapse-button"></div>
                </div>
            </div>
            <div class="collapse show" id="collapse-general">
                <div class="card card-body">
                    {if count($GCONFIG.my_domains) gt 1}
                    <div class="row mb-3">
                        <label for="element_site_domain" class="col-sm-3 col-xxl-4 col-form-label text-sm-end">{$LANG->getModule('site_domain')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-7">
                            <select class="form-select" name="site_domain" id="element_site_domain">
                                {foreach from=$GCONFIG.my_domains item=domain}
                                <option value="{$domain}"{if $GCONFIG.site_domain eq $domain} selected{/if}>{$domain}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                    {/if}
                    <div class="row mb-3">
                        <label for="element_site_name" class="col-sm-3 col-xxl-4 col-form-label text-sm-end">{$LANG->getModule('sitename')} <span class="text-danger">(*)</span></label>
                        <div class="col-sm-8 col-lg-6 col-xxl-7">
                            <input type="text" class="form-control" id="element_site_name" name="site_name" value="{$DATA.sitename}">
                            <div class="invalid-feedback">{$LANG->getGlobal('required_invalid')}</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="element_site_description" class="col-sm-3 col-xxl-4 col-form-label text-sm-end">{$LANG->getModule('description')} <span class="text-danger">(*)</span></label>
                        <div class="col-sm-8 col-lg-6 col-xxl-7">
                            <input type="text" class="form-control" id="element_site_description" name="site_description" value="{$DATA.description}">
                            <div class="invalid-feedback">{$LANG->getGlobal('required_invalid')}</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="element_site_keywords" class="col-sm-3 col-xxl-4 col-form-label text-sm-end">{$LANG->getModule('site_keywords')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-7">
                            <input type="text" class="form-control" id="element_site_keywords" name="site_keywords" value="{$DATA.site_keywords}">
                            <div class="form-text">{$LANG->getModule('params_info')}</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="element_site_logo" class="col-sm-3 col-xxl-4 col-form-label text-sm-end">{$LANG->getModule('site_logo')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-7">
                            <div class="input-group">
                                <input type="text" class="form-control" id="element_site_logo" name="site_logo" value="{$DATA.site_logo}" aria-describedby="element_site_logo_btn">
                                <button type="button" class="btn btn-secondary" id="element_site_logo_btn" aria-label="{$LANG->getGlobal('browse_image')}" data-toggle="selectfile" data-target="element_site_logo" data-type="image" title="{$LANG->getGlobal('browse_image')}"><i class="fa-solid fa-folder-open"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="element_site_banner" class="col-sm-3 col-xxl-4 col-form-label text-sm-end">{$LANG->getModule('site_banner')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-7">
                            <div class="input-group">
                                <input type="text" class="form-control" id="element_site_banner" name="site_banner" value="{$DATA.site_banner}" aria-describedby="element_site_banner_btn">
                                <button type="button" class="btn btn-secondary" id="element_site_banner_btn" aria-label="{$LANG->getGlobal('browse_image')}" data-toggle="selectfile" data-target="element_site_banner" data-type="image" title="{$LANG->getGlobal('browse_image')}"><i class="fa-solid fa-folder-open"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="element_site_favicon" class="col-sm-3 col-xxl-4 col-form-label text-sm-end">{$LANG->getModule('site_favicon')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-7">
                            <div class="input-group">
                                <input type="text" class="form-control" id="element_site_favicon" name="site_favicon" value="{$DATA.site_favicon}" aria-describedby="element_site_favicon_btn">
                                <button type="button" class="btn btn-secondary" id="element_site_favicon_btn" aria-label="{$LANG->getGlobal('browse_image')}" data-toggle="selectfile" data-target="element_site_favicon" data-type="image" title="{$LANG->getGlobal('browse_image')}"><i class="fa-solid fa-folder-open"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="element_site_home_module" class="col-sm-3 col-xxl-4 col-form-label text-sm-end">{$LANG->getModule('default_module')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-7">
                            <select class="form-select" name="site_home_module" id="element_site_home_module">
                                {foreach from=$MODS item=minfo}
                                <option value="{$minfo.title}"{if $GCONFIG.site_home_module eq $minfo.title} selected{/if}>{$minfo.custom_title}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8 offset-sm-3 offset-xxl-4">
                            <button type="submit" class="btn btn-primary">{$LANG->getModule('submit')}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-6">
        <div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0">
            <div class="card-header py-2" role="button" data-bs-toggle="collapse" data-bs-target="#collapse-theme" aria-expanded="true" aria-controls="collapse-theme">
                <div class="hstack gap-2 align-items-center justify-content-between">
                    <div class="fw-medium fs-5">{$LANG->getModule('theme_settings')}</div>
                    <div class="collapse-button"></div>
                </div>
            </div>
            <div class="collapse show" id="collapse-theme">
                <div class="card card-body">
                    <div class="row mb-3">
                        <div class="col-sm-3 col-xxl-4 col-form-label text-sm-end pt-0">{$LANG->getModule('allow_theme_type')}</div>
                        <div class="col-sm-8 col-lg-6 col-xxl-7">
                            {foreach from=$THEME_TYPES item=theme_type}
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="theme_type_{$theme_type}" name="theme_type[]" value="{$theme_type}"{if in_array($theme_type, $GCONFIG.array_theme_type)} checked{/if}>
                                <label class="form-check-label" for="theme_type_{$theme_type}">{$LANG->getGlobal("theme_type_`$theme_type`")}</label>
                            </div>
                            {/foreach}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="element_site_theme" class="col-sm-3 col-xxl-4 col-form-label text-sm-end">{$LANG->getModule('theme')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-7">
                            <select class="form-select" name="site_theme" id="element_site_theme">
                                {foreach from=$THEME_ARRAY item=theme}
                                <option value="{$theme}"{if $GCONFIG.site_theme eq $theme} selected{/if}>{$theme}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3 mobile_theme-wrap{if empty($MOBILE_THEME_ARRAY) or not in_array('m', $GCONFIG.array_theme_type)} d-none{/if}">
                        <label for="element_mobile_theme" class="col-sm-3 col-xxl-4 col-form-label text-sm-end">{$LANG->getModule('mobile_theme')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-7">
                            <select class="form-select" name="mobile_theme" id="element_mobile_theme">
                                <option value="">{$LANG->getModule('theme')}</option>
                                {foreach from=$MOBILE_THEME_ARRAY item=theme}
                                <option value="{$theme}"{if $GCONFIG.mobile_theme eq $theme} selected{/if}>{$theme}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3 switch_mobi_des-wrap{if empty($MOBILE_THEME_ARRAY) or not in_array('m', $GCONFIG.array_theme_type) or empty($GCONFIG.mobile_theme)} d-none{/if}">
                        <div class="col-sm-8 offset-sm-3 offset-xxl-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="switch_mobi_des" value="1"{if $DATA.switch_mobi_des} checked{/if} role="switch" id="element_switch_mobi_des">
                                <label class="form-check-label" for="element_switch_mobi_des">{$LANG->getModule('allow_switch_mobi_des')}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8 offset-sm-3 offset-xxl-4">
                            <button type="submit" class="btn btn-primary">{$LANG->getModule('submit')}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-6">
        <div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0">
            <div class="card-header py-2" role="button" data-bs-toggle="collapse" data-bs-target="#collapse-disabledct" aria-expanded="true" aria-controls="collapse-disabledct">
                <div class="hstack gap-2 align-items-center justify-content-between">
                    <div class="fw-medium fs-5">{$LANG->getModule('disable_content')}</div>
                    <div class="collapse-button"></div>
                </div>
            </div>
            <div class="collapse show" id="collapse-disabledct">
                <div class="card card-body">
                    <div class="mb-3">{$DISABLE_SITE_CONTENT}</div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">{$LANG->getModule('submit')}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-6">
        <div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0">
            <div class="card-header py-2" role="button" data-bs-toggle="collapse" data-bs-target="#collapse-submittingdt" aria-expanded="true" aria-controls="collapse-submittingdt">
                <div class="hstack gap-2 align-items-center justify-content-between">
                    <div class="fw-medium fs-5">{$LANG->getModule('submitting_data_warning')}</div>
                    <div class="collapse-button"></div>
                </div>
            </div>
            <div class="collapse show" id="collapse-submittingdt">
                <div class="card card-body">
                    <div class="alert alert-info" role="alert">{$LANG->getModule('submitting_data_warning_note')}</div>
                    <div class="row mb-3">
                        <div class="col-sm-8 offset-sm-3 offset-xxl-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="data_warning" value="1"{if $GCONFIG.data_warning} checked{/if} role="switch" id="element_data_warning">
                                <label class="form-check-label" for="element_data_warning">{$LANG->getModule('data_usage_permission_warning')}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="element_data_warning_content" class="col-sm-3 col-xxl-4 col-form-label text-sm-end">{$LANG->getModule('data_usage_permission_content')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-7">
                            <textarea class="form-control nonewline" id="element_data_warning_content" name="data_warning_content" rows="3">{$DATA.data_warning_content}</textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-8 offset-sm-3 offset-xxl-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="antispam_warning" value="1"{if $GCONFIG.antispam_warning} checked{/if} role="switch" id="element_antispam_warning">
                                <label class="form-check-label" for="element_antispam_warning">{$LANG->getModule('antispam_law_warning')}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="element_antispam_warning_content" class="col-sm-3 col-xxl-4 col-form-label text-sm-end">{$LANG->getModule('antispam_law_content')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-7">
                            <textarea class="form-control nonewline" id="element_antispam_warning_content" name="antispam_warning_content" rows="3">{$DATA.antispam_warning_content}</textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8 offset-sm-3 offset-xxl-4">
                            <button type="submit" class="btn btn-primary">{$LANG->getModule('submit')}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
