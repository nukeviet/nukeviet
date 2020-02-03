{if not empty($ERROR)}
<div role="alert" class="alert alert-danger alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="far fa-times-circle"></i></div>
    <div class="message">{$ERROR}</div>
</div>
{/if}
<form method="post" action="{$FORM_ACTION}" autocomplete="off">
    <input type="hidden" name="submit" value="1">
    <div class="card card-border-color card-border-color-primary">
        <div class="card-body">
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="closed_site">{$LANG->get('closed_site')}</label>
                <div class="col-12 col-sm-7 col-md-5 col-lg-4 col-xl-3">
                    <select class="form-control form-control-sm" id="closed_site" name="closed_site">
                        {foreach from=$CLOSED_SITE_MODES key=key item=value}
                        <option value="{$key}"{if $key eq $DATA['closed_site']} selected="selected"{/if}>{$value}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="site_email">{$LANG->get('site_email')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="email" class="form-control form-control-sm" id="site_email" name="site_email" value="{$DATA['site_email']}">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="site_phone">{$LANG->get('site_phone')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="text" class="form-control form-control-sm" id="site_phone" name="site_phone" value="{$DATA['site_phone']}">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="admin_theme">{$LANG->get('themeadmin')}</label>
                <div class="col-12 col-sm-7 col-md-5 col-lg-4 col-xl-3">
                    <select class="form-control form-control-sm" id="admin_theme" name="admin_theme">
                        {foreach from=$ADMINTHEMES item=value}
                        <option value="{$value}"{if $value eq $DATA['admin_theme']} selected="selected"{/if}>{$value}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="date_pattern">{$LANG->get('date_pattern')}</label>
                <div class="col-12 col-sm-7 col-md-5 col-lg-4 col-xl-3">
                    <input type="text" class="form-control form-control-sm" id="date_pattern" name="date_pattern" value="{$DATA['date_pattern']}">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="time_pattern">{$LANG->get('time_pattern')}</label>
                <div class="col-12 col-sm-7 col-md-5 col-lg-4 col-xl-3">
                    <input type="text" class="form-control form-control-sm" id="time_pattern" name="time_pattern" value="{$DATA['time_pattern']}">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="ssl_https">{$LANG->get('ssl_https')}</label>
                <div class="col-12 col-sm-7 col-md-5 col-lg-4 col-xl-3">
                    <select class="form-control form-control-sm" id="ssl_https" name="ssl_https" data-val="{$DATA['ssl_https']}" data-message="{$LANG->get('note_ssl')}">
                        {for $value=0 to 2}
                        <option value="{$value}"{if $value eq $DATA['ssl_https']} selected="selected"{/if}>{$LANG->get("ssl_https_`$value`")}</option>
                        {/for}
                    </select>
                </div>
            </div>
            {if $NV_IS_GODADMIN}
            <div class="form-group row py-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right">{$LANG->get('lang_multi')}</label>
                <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                    <label class="custom-control custom-checkbox custom-control-inline mb-1">
                        <input class="custom-control-input" type="checkbox" name="lang_multi" value="1"{if $CONFIG['lang_multi']} checked="checked"{/if} data-toggle="controlrw"><span class="custom-control-label"></span>
                    </label>
                </div>
            </div>
            {* Hiển thị các tùy chọn sau khi đã cài nhiều hơn 1 ngôn ngữ *}
            {if sizeof($DATA['allow_sitelangs']) gt 1}
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="site_lang">{$LANG->get('site_lang')}</label>
                <div class="col-12 col-sm-7 col-md-5 col-lg-4 col-xl-3">
                    <select class="form-control form-control-sm" id="site_lang" name="site_lang">
                        {foreach from=$ALLOW_SITELANGS item=value}
                        <option value="{$value}"{if $value eq $CONFIG['site_lang']} selected="selected"{/if}>{$LANGUAGE_ARRAY[$value]['name']}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            {if $CONFIG['lang_multi']}
            {* Cấu hình sau khi cho phép đa ngôn ngữ *}
            <div class="form-group row py-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right">{$LANG->get('lang_geo')}</label>
                <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                    <div class="d-flex flex-wrap align-items-center pt-1">
                        <div class="flex-grow-0 flex-shrink-0">
                            <label class="custom-control custom-checkbox custom-control-inline my-0">
                                <input class="custom-control-input" type="checkbox" name="lang_geo" value="1"{if $CONFIG['lang_geo']} checked="checked"{/if}><span class="custom-control-label"></span>
                            </label>
                        </div>
                        <div class="flex-grow-0 flex-shrink-1">
                            <div class="pb-1">(<a href="{$CONFIG_LANG_GEO}">{$LANG->get('lang_geo_config')}</a>)</div>
                        </div>
                    </div>
                </div>
            </div>
            {/if}
            {/if}
            <div class="form-group row py-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="rewrite_enable">{$LANG->get('rewrite')}</label>
                <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                    <label class="custom-control custom-checkbox custom-control-inline mb-1">
                        <input class="custom-control-input" type="checkbox" id="rewrite_enable" name="rewrite_enable" value="1"{if $CONFIG['rewrite_enable']} checked="checked"{/if} data-toggle="controlrw"><span class="custom-control-label"></span>
                    </label>
                </div>
            </div>
            <div class="form-group row py-0" id="tr_rewrite_optional"{if $CONFIG['lang_multi'] or not $CONFIG['rewrite_enable']} style="display:none"{/if}>
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="rewrite_optional">{$LANG->get('rewrite_optional')}</label>
                <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                    <label class="custom-control custom-checkbox custom-control-inline mb-1">
                        <input class="custom-control-input" type="checkbox" id="rewrite_optional" name="rewrite_optional" value="1"{if $CONFIG['rewrite_optional']} checked="checked"{/if} data-toggle="controlrw1"><span class="custom-control-label"></span>
                    </label>
                </div>
            </div>
            <div class="form-group row" id="tr_rewrite_op_mod"{if not $CONFIG['rewrite_optional']} style="display:none"{/if}>
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="rewrite_op_mod">{$LANG->get('rewrite_op_mod')}</label>
                <div class="col-12 col-sm-7 col-md-5 col-lg-4 col-xl-3">
                    <select class="form-control form-control-sm" id="rewrite_op_mod" name="rewrite_op_mod">
                        {foreach from=$SITE_MODS key=modname item=modinfo}
                        <option value="{$modname}"{if $modname eq $CONFIG['rewrite_op_mod']} selected="selected"{/if}>{$modinfo.custom_title}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="site_timezone">{$LANG->get('site_timezone')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <select class="select2" id="site_timezone" name="site_timezone">
                        <option value="">{$LANG->get('timezoneAuto')}</option>
                        <option value="byCountry"{if 'byCountry' eq $CONFIG['site_timezone']} selected="selected"{/if}>{$LANG->get('timezoneByCountry')}</option>
                        {foreach from=$TIMEZONES item=value}
                        <option value="{$value}"{if $value eq $CONFIG['site_timezone']} selected="selected"{/if}>{$value}</option>
                        {/foreach}
                    </select>
                    <span class="form-text text-muted">{$CURRENT_TIME}</span>
                </div>
            </div>
            <div class="form-group row py-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="gzip_method">{$LANG->get('gzip_method')}</label>
                <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                    <label class="custom-control custom-checkbox custom-control-inline mb-1">
                        <input class="custom-control-input" type="checkbox" id="gzip_method" name="gzip_method" value="1"{if $CONFIG['gzip_method']} checked="checked"{/if}><span class="custom-control-label"></span>
                    </label>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="my_domains">{$LANG->get('my_domains')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="text" class="form-control form-control-sm" id="my_domains" name="my_domains" value="{$MY_DOMAINS}">
                    <span class="form-text text-muted">{$LANG->get('my_domains_help')}</span>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="cdn_url">{$LANG->get('cdn_url')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <div class="d-flex flex-wrap align-items-center">
                        <div class="flex-grow-1 pr-2">
                            <input type="text" class="form-control form-control-sm" id="cdn_url" name="cdn_url" value="{$DATA['cdn_url']}">
                        </div>
                        <div class="flex-grow-0">
                            <button class="btn btn-secondary btn-input-sm" type="button" id="cdn_download" data-cdndl="{$NV_CHECK_SESSION}"><i class="icon icon-left fas fa-download"></i> {$LANG->get('cdn_download')}</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row py-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="error_set_logs">{$LANG->get('error_set_logs')}</label>
                <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                    <label class="custom-control custom-checkbox custom-control-inline mb-1">
                        <input class="custom-control-input" type="checkbox" id="error_set_logs" name="error_set_logs" value="1"{if $CONFIG['error_set_logs']} checked="checked"{/if}><span class="custom-control-label"></span>
                    </label>
                </div>
            </div>
            <div class="form-group row py-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="nv_debug">{$LANG->get('nv_debug')}</label>
                <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                    <div class="d-flex flex-wrap align-items-center pt-1">
                        <div class="flex-grow-0 flex-shrink-0">
                            <label class="custom-control custom-checkbox custom-control-inline my-0">
                                <input class="custom-control-input" type="checkbox" id="nv_debug" name="nv_debug" value="1"{if $CFG_DEFINE['nv_debug']} checked="checked"{/if}><span class="custom-control-label"></span>
                            </label>
                        </div>
                        <div class="flex-grow-0 flex-shrink-1">
                            <div class="pb-1">{$LANG->get('nv_debug_help')}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="error_send_email">{$LANG->get('error_send_email')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="email" class="form-control form-control-sm" id="error_send_email" name="error_send_email" value="{$DATA['error_send_email']}">
                </div>
            </div>
            <div class="form-group row py-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="remote_api_access">{$LANG->get('remote_api_access')}</label>
                <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                    <div class="d-flex flex-wrap align-items-center pt-1">
                        <div class="flex-grow-0 flex-shrink-0">
                            <label class="custom-control custom-checkbox custom-control-inline my-0">
                                <input class="custom-control-input" type="checkbox" id="remote_api_access" name="remote_api_access" value="1"{if $CONFIG['remote_api_access']} checked="checked"{/if}><span class="custom-control-label"></span>
                            </label>
                        </div>
                        <div class="flex-grow-0 flex-shrink-1">
                            <div class="pb-1">{$LANG->get('remote_api_access_help')}</div>
                        </div>
                    </div>
                </div>
            </div>
            {/if}
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="searchEngineUniqueID">{$LANG->get('searchEngineUniqueID')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="text" class="form-control form-control-sm" id="searchEngineUniqueID" name="searchEngineUniqueID" value="{$DATA['searchEngineUniqueID']}">
                    <span class="form-text text-muted">{$LANG->get('searchEngineUniqueID_help')}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-8 col-lg-6 offset-sm-3">
                    <h4 class="mb-0">{$LANG->get('notification_config')}</h4>
                </div>
            </div>
            <div class="card-divider"></div>
            <div class="form-group row py-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right d-none d-sm-block"></label>
                <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                    <label class="custom-control custom-checkbox custom-control-inline mb-1">
                        <input class="custom-control-input" type="checkbox" id="notification_active" name="notification_active" value="1"{if $CONFIG['notification_active']} checked="checked"{/if}><span class="custom-control-label">{$LANG->get('notification_active')}</span>
                    </label>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="notification_autodel">{$LANG->get('notification_autodel')}</label>
                <div class="col-12 col-sm-7 col-md-6 col-lg-5 col-xl-4">
                    <div class="d-flex flex-wrap align-items-center">
                        <div class="flex-grow-1 pr-2">
                            <input type="text" class="form-control form-control-sm" id="notification_autodel" name="notification_autodel" value="{$DATA['notification_autodel']}">
                        </div>
                        <div class="flex-grow-0">
                            {$LANG->get('day')}
                        </div>
                    </div>
                    <span class="form-text text-muted">{$LANG->get('notification_autodel_note')}</span>
                </div>
            </div>
            <div class="form-group row mb-0 pb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right"></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <button class="btn btn-space btn-primary" type="submit">{$LANG->get('submit')}</button>
                </div>
            </div>
        </div>
    </div>
</form>

<link data-offset="0" rel="stylesheet" href="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/select2/select2.min.css">

<script src="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script src="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/select2/i18n/{$NV_LANG_INTERFACE}.js"></script>
<script>
$(document).ready(function() {
    $(".select2").select2({
        width: "100%",
        containerCssClass: "select2-sm"
    });
});
</script>
