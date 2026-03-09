<script src="{$smarty.const.ASSETS_STATIC_URL}/js/select2/select2.min.js"></script>
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/select2/i18n/{$smarty.const.NV_LANG_INTERFACE}.js"></script>
<link type="text/css" href="{$smarty.const.ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet">
<script type="text/javascript" src="{$smarty.const.ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{$smarty.const.ASSETS_STATIC_URL}/js/language/jquery.ui.datepicker-{$smarty.const.NV_LANG_INTERFACE}.js"></script>
{if $smarty.const.NV_IS_GODADMIN}
<div class="card mb-3 bg-transparent">
    <div class="card-header bg-{empty($DATA.closed_site) ? 'success' : 'danger'}-subtle rounded-2">
        <div class="hstack gap-2 justify-content-between align-items-center">
            <div class="d-inline-flex gap-2 align-items-center text-{empty($DATA.closed_site) ? 'success' : 'danger'}">
                <div><i class="{empty($DATA.closed_site) ? 'fa-regular fa-circle-check' : 'fa-solid fa-ban'} fa-3x"></i></div>
                <div>
                    {$CLOSED_SITE_MODES[$DATA.closed_site]}
                    {if not empty($DATA.site_reopening_time)}
                    <div>
                        <small>{$LANG->getModule('closed_site_reopening_time')}: {$DATA.site_reopening_time|ddatetime}</small>
                    </div>
                    {/if}
                </div>
            </div>
            <button class="btn btn-{empty($DATA.closed_site) ? 'success' : 'danger'} collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-closesite" aria-expanded="false" aria-controls="collapse-closesite">
                <i class="fa-solid fa-gears"></i> {$LANG->getModule('closed_site')}
            </button>
        </div>
    </div>
    <div class="collapse" id="collapse-closesite">
        <div class="card-body bg-body rounded-2">
            <form method="post" class="ajax-submit" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" novalidate>
                <div class="row mb-3">
                    <label for="element_closed_site" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('closed_site')}</label>
                    <div class="col-sm-8 col-lg-6 col-xxl-5">
                        <select id="element_closed_site" name="closed_site" class="form-select">
                            {foreach from=$CLOSED_SITE_MODES key=key item=value}
                            <option value="{$key}"{if $key eq $DATA.closed_site} selected{/if}>{$value}</option>
                            {/foreach}
                        </select>
                    </div>
                </div>
                <div id="reopening_time"{if empty($DATA.closed_site)} class="d-none"{/if}>
                    <div class="row pb-3">
                        <label for="reopening_date" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('closed_site_reopening_time')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-5">
                            <div class="d-flex gap-1">
                                <input class="form-control flex-grow-1 flex-shrink-1 datepicker" name="reopening_date" id="reopening_date" value="{$DATA.reopening_date}" maxlength="10" type="text" autocomplete="off">
                                <select class="form-select fw-75" name="reopening_hour">
                                    {for $hour=0 to 23}
                                    <option value="{$hour}"{if $hour eq $DATA.reopening_hour} selected{/if}>{str_pad($hour, 2, 0, STR_PAD_LEFT)}</option>
                                    {/for}
                                </select>
                                <select class="form-select fw-75" name="reopening_min">
                                    {for $min=0 to 59}
                                    <option value="{$min}"{if $min eq $DATA.reopening_min} selected{/if}>{str_pad($min, 2, 0, STR_PAD_LEFT)}</option>
                                    {/for}
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-8 offset-sm-3">
                        <input type="hidden" name="site_mode" value="1">
                        <input type="hidden" name="checkss" value="{$smarty.const.NV_CHECK_SESSION}">
                        <button type="submit" class="btn btn-primary">{$LANG->getModule('submit')}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
{/if}
<form method="post" id="system-settings" class="row g-3 ajax-submit" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" novalidate>
    <input type="hidden" name="checkss" value="{$DATA.checkss}">
    <div class="col-12">
        <div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0">
            <div class="card-header py-2" role="button" data-bs-toggle="collapse" data-bs-target="#collapse-general" aria-expanded="true" aria-controls="collapse-general">
                <div class="hstack gap-2 align-items-center justify-content-between">
                    <div class="fw-medium fs-5">{$LANG->getModule('general_settings')}</div>
                    <div class="collapse-button"></div>
                </div>
            </div>
            <div class="collapse show" id="collapse-general">
                <div class="card card-body">
                    <div class="row mb-3">
                        <label for="element_site_email" class="col-sm-3 col-form-label text-sm-end">{$LANG->getGlobal('site_email')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-5">
                            <input type="email" class="form-control" id="element_site_email" name="site_email" value="{$DATA.site_email}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="element_site_phone" class="col-sm-3 col-form-label text-sm-end">{$LANG->getGlobal('site_phone')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-5">
                            <div class="input-group">
                                <input type="text" class="form-control" id="element_site_phone" name="site_phone" value="{$DATA.site_phone}" aria-describedby="element_site_phone_btn">
                                <button type="button" class="btn btn-secondary" id="element_site_phone_btn" aria-label="{$LANG->getGlobal('phone_note_title')}" data-bs-toggle="modal" data-bs-target="#mdPhoneNote"><i class="fa-solid fa-circle-question"></i></button>
                            </div>
                        </div>
                    </div>
                    {if $smarty.const.NV_IS_GODADMIN}
                    <div class="row mb-3">
                        <label for="element_my_domains" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('my_domains')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-5">
                            <input type="text" class="form-control" id="element_my_domains" name="my_domains" value="{$GDATA.my_domains}">
                            <div class="form-text">{$LANG->getModule('params_info')}</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="element_site_timezone" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('site_timezone')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-5">
                            <select id="element_site_timezone" name="site_timezone" class="form-select select2">
                                <option value="">{$LANG->getModule('timezoneAuto')}</option>
                                <option value="byCountry"{if 'byCountry' eq $GDATA.site_timezone} selected{/if}>{$LANG->getModule('timezoneByCountry')}</option>
                                {foreach from=$TIMEZONES item=timezone}
                                <option value="{$timezone}"{if $timezone eq $GDATA.site_timezone} selected{/if}>{$timezone}</option>
                                {/foreach}
                            </select>
                            <div class="form-text">{$LANG->getModule('current_time', $smarty.const.NV_CURRENTTIME|ddatetime:1:0)}</div>
                        </div>
                    </div>
                    {/if}
                    <div class="row mb-3">
                        <label for="element_ssl_https" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('ssl_https')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-5">
                            <select id="element_ssl_https" name="ssl_https" class="form-select" data-val="{$DATA.ssl_https}" data-confirm="{$LANG->getModule('note_ssl')}">
                                {for $ssl_https=0 to 2}
                                <option value="{$ssl_https}"{if $ssl_https eq $DATA.ssl_https} selected{/if}>{$LANG->getModule("ssl_https_`$ssl_https`")}</option>
                                {/for}
                            </select>
                        </div>
                    </div>
                    {if $smarty.const.NV_IS_GODADMIN}
                    <div class="row mb-3">
                        <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="gzip_method" value="1"{if $GDATA.gzip_method} checked{/if} role="switch" id="element_gzip_method">
                                <label class="form-check-label" for="element_gzip_method">{$LANG->getModule('gzip_method')}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="element_resource_preload" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('resource_preload')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-5">
                            <select id="element_resource_preload" name="resource_preload" class="form-select">
                                {foreach from=$PRELOAD_OPTS key=key item=value}
                                <option value="{$key}"{if $key eq $GDATA.resource_preload} selected{/if}>{$value}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="blank_operation" value="1"{if $GDATA.blank_operation} checked{/if} role="switch" id="element_blank_operation">
                                <label class="form-check-label" for="element_blank_operation">{$LANG->getModule('blank_operation')}</label>
                            </div>
                            <div class="form-text">{$LANG->getModule('blank_operation_help')}</div>
                        </div>
                    </div>
                    {/if}
                    <div class="row">
                        <div class="col-sm-8 offset-sm-3">
                            <button type="submit" class="btn btn-primary">{$LANG->getModule('submit')}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {if $smarty.const.NV_IS_GODADMIN}
    <div class="col-12">
        <div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0">
            <div class="card-header py-2" role="button" data-bs-toggle="collapse" data-bs-target="#collapse-rewrite" aria-expanded="true" aria-controls="collapse-rewrite">
                <div class="hstack gap-2 align-items-center justify-content-between">
                    <div class="fw-medium fs-5">{$LANG->getModule('lang_rewrite_settings')}</div>
                    <div class="collapse-button"></div>
                </div>
            </div>
            <div class="collapse show" id="collapse-rewrite">
                <div class="card card-body">
                    <div class="row mb-3">
                        <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="lang_multi" value="1"{if $GDATA.lang_multi} checked{/if} role="switch" id="element_lang_multi" data-toggle="controlrw">
                                <label class="form-check-label" for="element_lang_multi">{$LANG->getModule('lang_multi')}</label>
                            </div>
                        </div>
                    </div>
                    {if count($DATA.allow_sitelangs) gt 1}
                    <div class="row mb-3">
                        <label for="element_site_lang" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('site_lang')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-5">
                            <select id="element_site_lang" name="site_lang" class="form-select">
                                {foreach from=$ALLOW_SITELANGS item=sitelang}
                                <option value="{$sitelang}"{if $sitelang eq $GDATA.site_lang} selected{/if}>{$LANGUAGE_ARRAY[$sitelang].name}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                    <div id="lang-geo"{if empty($GDATA.lang_multi)} class="d-none"{/if}>
                        <div class="row pb-3">
                            <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="lang_geo" value="1"{if $GDATA.lang_geo} checked{/if} role="switch" id="element_lang_geo">
                                    <label class="form-check-label" for="element_lang_geo">{$LANG->getModule('lang_geo')}</label>
                                </div>
                                <div class="mt-1"><a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}=language&amp;{$smarty.const.NV_OP_VARIABLE}=countries">{$LANG->getModule('lang_geo_config')}</a></div>
                            </div>
                        </div>
                    </div>
                    {/if}
                    <div class="row mb-3">
                        <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="rewrite_enable" value="1"{if $GDATA.rewrite_enable} checked{/if} role="switch" id="element_rewrite_enable" data-toggle="controlrw">
                                <label class="form-check-label" for="element_rewrite_enable">{$LANG->getModule('rewrite')}</label>
                            </div>
                        </div>
                    </div>
                    <div id="ctn_rewrite_optional"{if not empty($GDATA.lang_multi) empty($GDATA.rewrite_enable)} class="d-none"{/if}>
                        <div class="row pb-3">
                            <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="rewrite_optional" value="1"{if $GDATA.rewrite_optional} checked{/if} role="switch" id="element_rewrite_optional" data-toggle="controlrw1">
                                    <label class="form-check-label" for="element_rewrite_optional">{$LANG->getModule('rewrite_optional')}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="ctn_rewrite_op_mod"{if empty($GDATA.rewrite_optional)} class="d-none"{/if}>
                        <div class="row pb-3">
                            <label for="element_rewrite_op_mod" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('rewrite_op_mod')}</label>
                            <div class="col-sm-8 col-lg-6 col-xxl-5">
                                <select id="element_rewrite_op_mod" name="rewrite_op_mod" class="form-select">
                                    <option value="">----</option>
                                    {foreach from=$SITE_MODS key=mod item=modinfo}
                                    <option value="{$mod}"{if $mod eq $GDATA.rewrite_op_mod} selected{/if}>{$modinfo.custom_title}</option>
                                    {/foreach}
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="static_noquerystring" value="1"{if $GDATA.static_noquerystring} checked{/if} role="switch" id="element_static_noquerystring">
                                <label class="form-check-label" for="element_static_noquerystring">{$LANG->getModule('static_noquerystring')}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="admin_rewrite" value="1"{if $GDATA.admin_rewrite} checked{/if} role="switch" id="element_admin_rewrite">
                                <label class="form-check-label" for="element_admin_rewrite">{$LANG->getModule('rewrite_admin')}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8 offset-sm-3">
                            <button type="submit" class="btn btn-primary">{$LANG->getModule('submit')}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0">
            <div class="card-header py-2" role="button" data-bs-toggle="collapse" data-bs-target="#collapse-error" aria-expanded="true" aria-controls="collapse-error">
                <div class="hstack gap-2 align-items-center justify-content-between">
                    <div class="fw-medium fs-5">{$LANG->getModule('error_handler_settings')}</div>
                    <div class="collapse-button"></div>
                </div>
            </div>
            <div class="collapse show" id="collapse-error">
                <div class="card card-body">
                    <div class="row mb-3">
                        <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="error_set_logs" value="1"{if $GDATA.error_set_logs} checked{/if} role="switch" id="element_error_set_logs">
                                <label class="form-check-label" for="element_error_set_logs">{$LANG->getModule('error_set_logs')}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="nv_debug" value="1"{if $DDATA.nv_debug} checked{/if} role="switch" id="element_nv_debug">
                                <label class="form-check-label" for="element_nv_debug">{$LANG->getModule('nv_debug')}</label>
                            </div>
                            <div class="form-text">{$LANG->getModule('nv_debug_help')}</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="element_error_send_email" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('error_send_email')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-5">
                            <input type="email" class="form-control" id="element_error_send_email" name="error_send_email" value="{$DATA.error_send_email}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8 offset-sm-3">
                            <button type="submit" class="btn btn-primary">{$LANG->getModule('submit')}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {/if}
    <div class="col-12">
        <div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0">
            <div class="card-header py-2" role="button" data-bs-toggle="collapse" data-bs-target="#collapse-search" aria-expanded="true" aria-controls="collapse-search">
                <div class="hstack gap-2 align-items-center justify-content-between">
                    <div class="fw-medium fs-5">{$LANG->getModule('search_settings')}</div>
                    <div class="collapse-button"></div>
                </div>
            </div>
            <div class="collapse show" id="collapse-search">
                <div class="card card-body">
                    {if $smarty.const.NV_IS_GODADMIN}
                    <div class="row mb-3">
                        <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="unsign_vietwords" value="1"{if $GDATA.unsign_vietwords} checked{/if} role="switch" id="element_unsign_vietwords">
                                <label class="form-check-label" for="element_unsign_vietwords">{$LANG->getModule('unsign_vietwords')}</label>
                            </div>
                            <div class="form-text">{$LANG->getModule('unsign_vietwords_note')}</div>
                        </div>
                    </div>
                    {/if}
                    <div class="row mb-3">
                        <label for="element_searchEngineUniqueID" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('searchEngineUniqueID')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-5">
                            <input type="text" class="form-control" id="element_searchEngineUniqueID" name="searchEngineUniqueID" value="{$DATA.searchEngineUniqueID}">
                            <div class="form-text">{$LANG->getModule('searchEngineUniqueID_note')}</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8 offset-sm-3">
                            <button type="submit" class="btn btn-primary">{$LANG->getModule('submit')}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0">
            <div class="card-header py-2" role="button" data-bs-toggle="collapse" data-bs-target="#collapse-acp" aria-expanded="true" aria-controls="collapse-acp">
                <div class="hstack gap-2 align-items-center justify-content-between">
                    <div class="fw-medium fs-5">{$LANG->getModule('acp_settings')}</div>
                    <div class="collapse-button"></div>
                </div>
            </div>
            <div class="collapse show" id="collapse-acp">
                <div class="card card-body">
                    <div class="row pb-3">
                        <label for="element_admin_theme" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('themeadmin')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-5">
                            <select id="element_admin_theme" name="admin_theme" class="form-select">
                                {foreach from=$ADMINTHEMES item=theme}
                                <option value="{$theme}"{if $theme eq $DATA.admin_theme} selected{/if}>{$theme}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                    {if $smarty.const.NV_IS_GODADMIN}
                    <div class="row mb-3">
                        <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="notification_active" value="1"{if $DATA.notification_active} checked{/if} role="switch" id="element_notification_active">
                                <label class="form-check-label" for="element_notification_active">{$LANG->getModule('notification_active')}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="element_notification_autodel" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('notification_autodel')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-5">
                            <div class="input-group">
                                <input type="number" min="0" max="9999" class="form-control" id="element_notification_autodel" name="notification_autodel" value="{$DATA.notification_autodel}" aria-describedby="element_notification_autodel_lbl">
                                <span class="input-group-text" id="element_notification_autodel_lbl">{$LANG->getModule('notification_day')}</span>
                            </div>
                            <div class="form-text">{$LANG->getModule('notification_autodel_note')}</div>
                        </div>
                    </div>
                    {/if}
                    <div class="row">
                        <div class="col-sm-8 offset-sm-3">
                            <button type="submit" class="btn btn-primary">{$LANG->getModule('submit')}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {if $smarty.const.NV_IS_GODADMIN}
    <div class="col-12">
        <div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0">
            <div class="card-header py-2" role="button" data-bs-toggle="collapse" data-bs-target="#collapse-cache" aria-expanded="true" aria-controls="collapse-cache">
                <div class="hstack gap-2 align-items-center justify-content-between">
                    <div class="fw-medium fs-5">{$LANG->getModule('cache_settings')}</div>
                    <div class="collapse-button"></div>
                </div>
            </div>
            <div class="collapse show" id="collapse-cache">
                <div class="card card-body">
                    <div class="row pb-3">
                        <label for="element_cache_system" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('cache_use')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-5">
                            <select id="element_cache_system" name="cached" class="form-select">
                                {assign var="CACHE_OPTIONS" value=['files'=>$LANG->getModule('files_cached'),'memcached'=>'Memcached','redis'=>'Redis']}
                                {foreach $CACHE_OPTIONS as $k_cache => $item}
                                <option value="{$k_cache}"{if $k_cache eq $DATA.cached} selected{/if}>{$item}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3 memcached-settings">
                        <label for="element_memcached_host" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('memcached_host')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-5">
                            <input type="text" class="form-control" id="element_memcached_host" name="memcached_host" value="{$DATA.memcached_host}">
                        </div>
                    </div>
                    <div class="row mb-3 memcached-settings">
                        <label for="element_memcached_port" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('memcached_port')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-5">
                            <input type="number" class="form-control" id="element_memcached_port" name="memcached_port" value="{$DATA.memcached_port}">
                        </div>
                    </div>
                    <div class="row mb-3 redis-settings">
                        <label for="element_redis_host" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('redis_host')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-5">
                            <input type="text" class="form-control" id="element_redis_host" name="redis_host" value="{$DATA.redis_host}">
                        </div>
                    </div>
                    <div class="row mb-3 redis-settings">
                        <label for="element_redis_port" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('redis_port')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-5">
                            <input type="number" class="form-control" id="element_redis_port" name="redis_port" value="{$DATA.redis_port}">
                        </div>
                    </div>
                    <div class="row mb-3 redis-settings">
                        <label for="element_redis_password" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('redis_password')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-5">
                            <input type="password" class="form-control" id="element_redis_password" name="redis_password" value="{$DATA.redis_password}">
                            <div class="form-text">{$LANG->getModule('redis_pass_note')}</div>
                        </div>
                    </div>
                    <div class="row mb-3 redis-settings">
                        <label for="element_redis_db_index" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('redis_db_index')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-5">
                            <input type="number" class="form-control" id="element_redis_db_index" name="redis_db_index" value="{$DATA.redis_db_index}">
                        </div>
                    </div>
                    <div class="row mb-3 redis-settings">
                        <label for="element_redis_timeout" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('redis_timeout')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-5">
                            <div class="input-group">
                                <input type="number" class="form-control" id="element_redis_timeout" name="redis_timeout" value="{$DATA.redis_timeout}">
                                <span class="input-group-text" id="element_redis_timeout_lbl">{$LANG->getModule('redis_timeout_unit')}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="element_cache_prefix" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('cache_prefix')}</label>
                        <div class="col-sm-8 col-lg-6 col-xxl-5">
                            <input type="text" class="form-control" id="element_cache_prefix" name="cache_prefix" value="{$DATA.cache_prefix}">
                            <div class="form-text">{$LANG->getModule('cache_prefix_note')}</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8 offset-sm-3">
                            <button type="submit" class="btn btn-primary">{$LANG->getModule('submit')}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {/if}
</form>
<div class="modal fade" id="mdPhoneNote" tabindex="-1" aria-labelledby="mdPhoneNoteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title fw-medium fs-5" id="mdPhoneNoteLabel">{$LANG->getGlobal('phone_note_title')}</div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="modal-body">
                {$LANG->getGlobal('phone_note_content2')}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark text-danger"></i> {$LANG->getGlobal('close')}</button>
            </div>
        </div>
    </div>
</div>
