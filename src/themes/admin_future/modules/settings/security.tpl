<link type="text/css" href="{$smarty.const.ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet">
<script type="text/javascript" src="{$smarty.const.ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{$smarty.const.ASSETS_LANG_STATIC_URL}/js/language/jquery.ui.datepicker-{$smarty.const.NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{$smarty.const.ASSETS_STATIC_URL}/js/clipboard/clipboard.min.js"></script>
<div class="row g-3">
    <div class="col-md-3 order-md-2">
        <div class="dropdown d-grid d-md-none" id="settingSelect">
            {assign var="tabTitle" value=[
                0 => $LANG->getModule('general_settings'),
                1 => $LANG->getModule('flood_blocker'),
                2 => $LANG->getModule('captcha'),
                3 => $LANG->getModule('banip'),
                4 => $LANG->getModule('cors'),
                5 => $LANG->getModule('csp'),
                6 => $LANG->getModule('rp'),
                7 => $LANG->getModule('pp')
            ] nocache}
            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="fw-medium" data-toggle="dropdown-value">{$tabTitle[$SELECTEDTAB]}</span>
            </button>
            <ul class="dropdown-menu border-0">
                {if $smarty.const.NV_IS_GODADMIN}
                <li><a class="dropdown-item{if $SELECTEDTAB eq 0} active{/if}" href="#" data-tab="settingBasic">{$LANG->getModule('general_settings')}</a></li>
                <li><a class="dropdown-item{if $SELECTEDTAB eq 1} active{/if}" href="#" data-tab="settingFlood">{$LANG->getModule('flood_blocker')}</a></li>
                <li><a class="dropdown-item{if $SELECTEDTAB eq 2} active{/if}" href="#" data-tab="settingCaptcha">{$LANG->getModule('captcha')}</a></li>
                <li><a class="dropdown-item{if $SELECTEDTAB eq 3} active{/if}" href="#" data-tab="settingIp">{$LANG->getModule('banip')}</a></li>
                <li><a class="dropdown-item{if $SELECTEDTAB eq 4} active{/if}" href="#" data-tab="settingCORS">{$LANG->getModule('cors')}</a></li>
                {/if}
                <li><a class="dropdown-item{if $SELECTEDTAB eq 5} active{/if}" href="#" data-tab="settingCSP">{$LANG->getModule('csp')}</a></li>
                <li><a class="dropdown-item{if $SELECTEDTAB eq 6} active{/if}" href="#" data-tab="settingRP">{$LANG->getModule('rp')}</a></li>
                <li><a class="dropdown-item{if $SELECTEDTAB eq 7} active{/if}" href="#" data-tab="settingPP">{$LANG->getModule('pp')}</a></li>
            </ul>
        </div>
        <ul class="sticky-top d-none d-md-flex nav nav-pills nav-stacked" role="tablist" id="settingTabs">
            {if $smarty.const.NV_IS_GODADMIN}
            <li class="nav-item w-100 mw-100"><a class="nav-link{if 0 eq $SELECTEDTAB} active{/if}" href="#settingBasic" aria-controls="settingBasic" aria-offsets="0" role="tab" data-bs-toggle="pill" data-location="{$FORM_ACTION}&amp;selectedtab=0"><i class="fa-solid fa-caret-right"></i> {$LANG->getModule('general_settings')}</a></li>
            <li class="nav-item w-100 mw-100"><a class="nav-link{if 1 eq $SELECTEDTAB} active{/if}" href="#settingFlood" aria-controls="settingFlood" aria-offsets="1" role="tab" data-bs-toggle="pill" data-location="{$FORM_ACTION}&amp;selectedtab=1" data-loaded="false" data-type="1" data-load-url="{$FORM_ACTION}&amp;action=fiplist"><i class="fa-solid fa-caret-right"></i> {$LANG->getModule('flood_blocker')}</a></li>
            <li class="nav-item w-100 mw-100"><a class="nav-link{if 2 eq $SELECTEDTAB} active{/if}" href="#settingCaptcha" aria-controls="settingCaptcha" aria-offsets="2" role="tab" data-bs-toggle="pill" data-location="{$FORM_ACTION}&amp;selectedtab=2"><i class="fa-solid fa-caret-right"></i> {$LANG->getModule('captcha')}</a></li>
            <li class="nav-item w-100 mw-100"><a class="nav-link{if 3 eq $SELECTEDTAB} active{/if}" href="#settingIp" aria-controls="settingIp" aria-offsets="3" role="tab" data-bs-toggle="pill" data-location="{$FORM_ACTION}&amp;selectedtab=3" data-loaded="false" data-type="0" data-load-url="{$FORM_ACTION}&amp;action=biplist"><i class="fa-solid fa-caret-right"></i> {$LANG->getModule('banip')}</a></li>
            <li class="nav-item w-100 mw-100"><a class="nav-link{if 4 eq $SELECTEDTAB} active{/if}" href="#settingCORS" aria-controls="settingCORS" aria-offsets="4" role="tab" data-bs-toggle="pill" data-location="{$FORM_ACTION}&amp;selectedtab=4"><i class="fa-solid fa-caret-right"></i> {$LANG->getModule('cors')}</a></li>
            {/if}
            <li class="nav-item w-100 mw-100"><a class="nav-link{if 5 eq $SELECTEDTAB} active{/if}" href="#settingCSP" aria-controls="settingCSP" aria-offsets="5" role="tab" data-bs-toggle="pill" data-location="{$FORM_ACTION}&amp;selectedtab=5"><i class="fa-solid fa-caret-right"></i> {$LANG->getModule('csp')}</a></li>
            <li class="nav-item w-100 mw-100"><a class="nav-link{if 6 eq $SELECTEDTAB} active{/if}" href="#settingRP" aria-controls="settingRP" aria-offsets="6" role="tab" data-bs-toggle="pill" data-location="{$FORM_ACTION}&amp;selectedtab=6"><i class="fa-solid fa-caret-right"></i> {$LANG->getModule('rp')}</a></li>
            <li class="nav-item w-100 mw-100"><a class="nav-link{if 7 eq $SELECTEDTAB} active{/if}" href="#settingPP" aria-controls="settingPP" aria-offsets="7" role="tab" data-bs-toggle="pill" data-location="{$FORM_ACTION}&amp;selectedtab=7"><i class="fa-solid fa-caret-right"></i> {$LANG->getModule('pp')}</a></li>
        </ul>
    </div>
    <div class="col-md-9 order-md-1">
        <div class="tab-content">
            {if $smarty.const.NV_IS_GODADMIN}
            {* Các thiết lập chính *}
            <div role="tabpanel" class="tab-pane{if 0 eq $SELECTEDTAB} active{/if}" id="settingBasic">
                <form action="{$FORM_ACTION}" method="post" class="ajax-submit" id="secForm">
                    <ul class="list-group list-group-striped">
                        <li class="list-group-item active text-bg-primary">
                            <strong>{$LANG->getModule('general_settings')}</strong>
                        </li>

                        <li class="list-group-item">
                            <div class="row g-2">
                                <div class="col-sm-6 col-xxl-5 offset-sm-5">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" value="1" id="is_login_blocker" name="is_login_blocker"{if $GDATA.is_login_blocker} checked{/if}>
                                        <label class="form-check-label" for="is_login_blocker">{$LANG->getModule('is_login_blocker')}</label>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="row g-2">
                                <label for="login_number_tracking" class="col-sm-5 col-form-label text-sm-end py-only-sm-0"><strong>{$LANG->getModule('login_number_tracking')}</strong></label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input type="number" value="{$GDATA.login_number_tracking}" id="login_number_tracking" name="login_number_tracking" class="form-control text-center maxw-75" aria-describedby="login_number_tracking_desc">
                                        <span class="input-group-text" id="login_number_tracking_desc">{$LANG->getModule('login_time_tracking')}</span>
                                        <input type="number" value="{$GDATA.login_time_tracking}" name="login_time_tracking" class="required form-control text-center maxw-75" aria-describedby="login_time_tracking_desc">
                                        <span class="input-group-text" id="login_time_tracking_desc">{$LANG->getGlobal('min')}</span>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="row g-2">
                                <label for="login_time_ban" class="col-sm-5 col-form-label text-sm-end py-only-sm-0"><strong>{$LANG->getModule('login_time_ban')}</strong></label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input type="number" min="0" value="{$GDATA.login_time_ban}" id="login_time_ban" name="login_time_ban" class="form-control text-center maxw-75" aria-describedby="login_time_ban_desc">
                                        <span class="input-group-text" id="login_time_ban_desc">{$LANG->getGlobal('min')}</span>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="row g-2">
                                <label for="passshow_button" class="col-sm-5 col-form-label text-sm-end py-only-sm-0"><strong>{$LANG->getModule('passshow_button')}</strong></label>
                                <div class="col-sm-7">
                                    <select class="form-select w-auto mw-100" name="passshow_button" id="passshow_button">
                                        {foreach from=$PASSSHOW_BUTTON_OPTS key=key item=value}
                                        <option value="{$key}"{if $key eq $GDATA.passshow_button} selected{/if}>{$value}</option>
                                        {/foreach}
                                    </select>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="row g-2">
                                <label for="two_step_verification" class="col-sm-5 col-form-label text-sm-end py-only-sm-0"><strong>{$LANG->getModule('two_step_verification')}</strong></label>
                                <div class="col-sm-7">
                                    <select name="two_step_verification" id="two_step_verification" class="form-select w-auto mw-100">
                                        {for $i=0 to 3}
                                        <option value="{$i}"{if $i eq $GDATA.two_step_verification} selected{/if}>{$LANG->getModule("two_step_verification`$i`")}</option>
                                        {/for}
                                    </select>
                                    <div class="form-text">{$LANG->getModule('two_step_verification_note')}</div>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="row g-2">
                                <div class="col-sm-5 col-form-label text-sm-end py-only-sm-0 pt-0"><strong>{$LANG->getModule('admin_2step_opt')}</strong></div>
                                <div class="col-sm-7">
                                    {foreach from=$ADMIN_2STEP_PROVIDERS item=admin_2step}
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="admin_2step_opt_{$admin_2step}" name="admin_2step_opt[]" value="{$admin_2step}"{if in_array($admin_2step, $GDATA.admin_2step_opt)} checked{/if}>
                                        <label class="form-check-label" for="admin_2step_opt_{$admin_2step}">
                                            {$LANG->getGlobal("admin_2step_opt_`$admin_2step`")}
                                            {if $admin_2step eq 'facebook' or $admin_2step eq 'google'}
                                            (<a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}=users&amp;{$smarty.const.NV_OP_VARIABLE}=config&amp;oauth_config={$admin_2step}" target="_blank">{$LANG->getModule('admin_2step_appconfig')}</a>)
                                            {elseif $admin_2step eq 'zalo'}
                                            (<a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}=zalo&amp;{$smarty.const.NV_OP_VARIABLE}=settings" target="_blank">{$LANG->getModule('admin_2step_appconfig')}</a>)
                                            {/if}
                                        </label>
                                    </div>
                                    {/foreach}
                                    <div class="mt-1 d-none" role="alert" data-toggle="2step-check-lev2"><i class="text-danger fa-solid fa-triangle-exclamation"></i> {$LANG->getModule('admin_2step_error')}</div>
                                    <div class="mt-1 d-none" role="alert" data-toggle="2step-check-lev1"><i class="text-warning fa-solid fa-circle-exclamation"></i> {$LANG->getModule('admin_2step_warning')}</div>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="row g-2">
                                <label for="admin_2step_default" class="col-sm-5 col-form-label text-sm-end py-only-sm-0"><strong>{$LANG->getModule('admin_2step_default')}</strong></label>
                                <div class="col-sm-7">
                                    <select name="admin_2step_default" id="admin_2step_default" class="form-select w-auto mw-100">
                                        {foreach from=$ADMIN_2STEP_PROVIDERS item=admin_2step}
                                        {if $admin_2step neq 'key'}
                                        <option value="{$admin_2step}"{if $admin_2step eq $GDATA.admin_2step_default} selected{/if}>{$LANG->getGlobal("admin_2step_opt_`$admin_2step`")}</option>
                                        {/if}
                                        {/foreach}
                                    </select>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="row g-2">
                                <div class="col-sm-6 col-xxl-5 offset-sm-5">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" role="switch" value="1" id="nv_anti_agent" name="nv_anti_agent"{if $DDATA.nv_anti_agent} checked{/if}>
                                        <label class="form-check-label" for="nv_anti_agent">{$LANG->getModule('nv_anti_agent')}</label>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="row g-2">
                                <label for="proxy_blocker" class="col-sm-5 col-form-label text-sm-end py-only-sm-0"><strong>{$LANG->getModule('proxy_blocker')}</strong></label>
                                <div class="col-sm-7">
                                    <select name="proxy_blocker" id="proxy_blocker" class="form-select w-auto mw-100">
                                        {foreach from=$PROXY_BLOCKER_LIST key=key item=value}
                                        <option value="{$key}"{if $GDATA.proxy_blocker eq $key} selected{/if}>{$value}</option>
                                        {/foreach}
                                    </select>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="row g-2">
                                <div class="col-sm-6 col-xxl-5 offset-sm-5">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" role="switch" value="1" id="str_referer_blocker" name="str_referer_blocker"{if $GDATA.str_referer_blocker} checked{/if}>
                                        <label class="form-check-label" for="str_referer_blocker">{$LANG->getModule('str_referer_blocker')}</label>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="row g-2">
                                <div class="col-sm-6 col-xxl-5 offset-sm-5">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" role="switch" value="1" id="nv_anti_iframe" name="nv_anti_iframe"{if $DDATA.nv_anti_iframe} checked{/if}>
                                        <label class="form-check-label" for="nv_anti_iframe">{$LANG->getModule('nv_anti_iframe')}</label>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="row g-2">
                                <label for="nv_allowed_html_tags" class="col-sm-5 col-form-label text-sm-end py-only-sm-0"><strong>{$LANG->getModule('nv_allowed_html_tags')}</strong></label>
                                <div class="col-sm-7">
                                    <textarea id="nv_allowed_html_tags" name="nv_allowed_html_tags" class="form-control" rows="5">{$DDATA.nv_allowed_html_tags}</textarea>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="row g-2">
                                <div class="col-sm-6 col-xxl-5 offset-sm-5">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" id="domains_restrict" role="switch" name="domains_restrict" value="1"{if $GDATA.domains_restrict} checked{/if}>
                                        <label class="form-check-label" for="domains_restrict">{$LANG->getModule('domains_restrict')}</label>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="row g-2">
                                <label for="domains_whitelist" class="col-sm-5 col-form-label text-sm-end py-only-sm-0"><strong>{$LANG->getModule('domains_whitelist')}</strong></label>
                                <div class="col-sm-7">
                                    <textarea name="domains_whitelist" id="domains_whitelist" class="form-control" rows="5">{$GDATA.domains_whitelist}</textarea>
                                    <div class="form-text">{$LANG->getModule('domains_whitelist_note')}</div>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="row g-2">
                                <div class="col-sm-6 col-xxl-5 offset-sm-5">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" role="switch" id="XSSsanitize" name="XSSsanitize" value="1"{if $GDATA.XSSsanitize} checked{/if}>
                                        <label class="form-check-label" for="XSSsanitize">{$LANG->getModule('XSSsanitize')}</label>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="row g-2">
                                <div class="col-sm-6 col-xxl-5 offset-sm-5">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" role="switch" id="admin_XSSsanitize" name="admin_XSSsanitize" value="1"{if $GDATA.admin_XSSsanitize} checked{/if}>
                                        <label class="form-check-label" for="admin_XSSsanitize">{$LANG->getModule('admin_XSSsanitize')}</label>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="row g-2">
                                <label for="request_uri_check" class="col-sm-5 col-form-label text-sm-end py-only-sm-0"><strong>{$LANG->getModule('request_uri_check')}</strong></label>
                                <div class="col-sm-7">
                                    <select name="request_uri_check" id="request_uri_check" class="form-select w-auto mw-100">
                                        {foreach from=$URI_CHECK_VALUES key=key item=value}
                                        <option value="{$key}"{if $key eq $GDATA.request_uri_check} selected{/if}>{$value}</option>
                                        {/foreach}
                                    </select>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div><strong>{$LANG->getModule('end_url_variables')}</strong></div>
                            <div class="form-text">{$LANG->getModule('end_url_variables_note')}</div>
                            <ul class="list-group mb-0 list mt-2">
                                {foreach from=$GDATA.end_url_variables key=key item=value}
                                <li class="list-group-item item">
                                    <input type="hidden" name="parameters[]" value="{empty($value) ? '' : $value|join:','}">
                                    <div class="row g-2 mb-3">
                                        <label class="col-sm-3 col-form-label text-sm-end fw-medium i-label" for="end_url_variables_{$key}">{$LANG->getModule('end_url_variable_name')}</label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="end_url_variables_{$key}" name="end_url_variables[]" value="{empty($key) ? '' : $key}" maxlength="50">
                                                <button class="btn btn-secondary add-variable" type="button"><i class="fa-solid fa-plus fa-fw text-center text-primary"></i></button>
                                                <button class="btn btn-secondary del-variable" type="button"><i class="fa-solid fa-xmark fa-fw text-center text-danger"></i></button>
                                            </div>
                                            <div class="form-text">{$LANG->getModule('end_url_variable_name_note')}</div>
                                        </div>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-sm-3 col-form-label text-sm-end pt-0 fw-medium">{$LANG->getModule('end_url_variable_dataformat')}</div>
                                        <div class="col-sm-9">
                                            <div class="form-check form-check-inline">
                                                <input type="checkbox" class="form-check-input parameter" id="check_{$key}_parameter_lower" value="lower"{if not empty($value) and in_array('lower', $value)} checked{/if}>
                                                <label class="form-check-label" for="check_{$key}_parameter_lower">{$LANG->getModule('lowercase_letters')}</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input type="checkbox" class="form-check-input parameter" id="check_{$key}_parameter_upper" value="upper"{if not empty($value) and in_array('upper', $value)} checked{/if}>
                                                <label class="form-check-label" for="check_{$key}_parameter_upper">{$LANG->getModule('uppercase_letters')}</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input type="checkbox" class="form-check-input parameter" id="check_{$key}_parameter_number" value="number"{if not empty($value) and in_array('number', $value)} checked{/if}>
                                                <label class="form-check-label" for="check_{$key}_parameter_number">{$LANG->getModule('number')}</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input type="checkbox" class="form-check-input parameter" id="check_{$key}_parameter_dash" value="dash"{if not empty($value) and in_array('dash', $value)} checked{/if}>
                                                <label class="form-check-label" for="check_{$key}_parameter_dash">{$LANG->getModule('dash')}</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input type="checkbox" class="form-check-input parameter" id="check_{$key}_parameter_under" value="under"{if not empty($value) and in_array('under', $value)} checked{/if}>
                                                <label class="form-check-label" for="check_{$key}_parameter_under">{$LANG->getModule('underline')}</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input type="checkbox" class="form-check-input parameter" id="check_{$key}_parameter_dot" value="dot"{if not empty($value) and in_array('dot', $value)} checked{/if}>
                                                <label class="form-check-label" for="check_{$key}_parameter_dot">{$LANG->getModule('dot')}</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input type="checkbox" class="form-check-input parameter" id="check_{$key}_parameter_at" value="at"{if not empty($value) and in_array('at', $value)} checked{/if}>
                                                <label class="form-check-label" for="check_{$key}_parameter_at">{$LANG->getModule('at_sign')}</label>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                {/foreach}
                            </ul>
                        </li>
                        <li class="list-group-item bg-body text-center">
                            <input type="hidden" name="selectedtab" value="{$SELECTEDTAB}">
                            <input type="hidden" name="checkss" value="{$CHECKSS}">
                            <input type="hidden" name="basicsave" value="1">
                            <button type="submit" class="btn btn-primary">{$LANG->getModule('submit')}</button>
                        </li>
                    </ul>
                </form>
            </div>
            {* Chống tấn công ngập lụt *}
            <div role="tabpanel" class="tab-pane{if 1 eq $SELECTEDTAB} active{/if}" id="settingFlood">
                <form action="{$FORM_ACTION}" method="post" class="ajax-submit" id="floodForm" style="margin-bottom:20px">
                    <ul class="list-group list-group-striped">
                        <li class="list-group-item active text-bg-primary">
                            <strong>{$LANG->getModule('flood_blocker')}</strong>
                        </li>

                        <li class="list-group-item">
                            <div class="row g-2">
                                <div class="col-sm-6 col-xxl-5 offset-sm-5">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" role="switch" value="1" id="is_flood_blocker" name="is_flood_blocker"{if $FDATA.is_flood_blocker} checked{/if}>
                                        <label class="form-check-label" for="is_flood_blocker">{$LANG->getModule('is_flood_blocker')}</label>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="row g-2">
                                <label class="col-sm-5 col-form-label text-sm-end py-only-sm-0" for="max_requests_60"><strong>{$LANG->getModule('max_requests_60')}</strong></label>
                                <div class="col-sm-7">
                                    <input type="number" min="0" max="9999" value="{$FDATA.max_requests_60}" id="max_requests_60" name="max_requests_60" class="form-control w-auto mw-100">
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="row g-2">
                                <label class="col-sm-5 col-form-label text-sm-end py-only-sm-0" for="max_requests_300"><strong>{$LANG->getModule('max_requests_300')}</strong></label>
                                <div class="col-sm-7">
                                    <input type="number" min="0" max="999999" value="{$FDATA.max_requests_300}" id="max_requests_300" name="max_requests_300" class="form-control w-auto mw-100">
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="row g-2">
                                <div class="col-sm-7 offset-sm-5 text-center text-sm-start">
                                    <input type="hidden" name="selectedtab" value="{$SELECTEDTAB}">
                                    <input type="hidden" name="checkss" value="{$CHECKSS}">
                                    <input type="hidden" name="floodsave" value="1">
                                    <button type="submit" class="btn btn-primary">{$LANG->getModule('submit')}</button>
                                </div>
                            </div>
                        </li>
                    </ul>
                </form>

                <div class="card">
                    <div class="card-header">
                        <strong>{$LANG->getModule('noflood_ip_list')}</strong>
                    </div>
                    <ul class="list-group list-group-flush list" id="noflips" data-url="{$FORM_ACTION}&amp;action=fip" data-del-url="{$FORM_ACTION}&amp;action=delfip" data-confirm="{$LANG->getModule('ip_delete_confirm')}" data-checkss="{$CHECKSS}"></ul>
                </div>
            </div>
            {* Cấu hình hiển thị captcha *}
            <div role="tabpanel" class="tab-pane{if 2 eq $SELECTEDTAB} active{/if}" id="settingCaptcha">
                <div class="accordion" id="accordion-settingCaptcha" role="tablist" aria-multiselectable="true">
                    <div class="accordion-item">
                        <div class="accordion-header">
                            <button type="button" role="tab" id="settingCaptcha-headingOne" class="accordion-button" data-bs-toggle="collapse" data-bs-target="#settingCaptcha-collapseOne" aria-expanded="true" aria-controls="settingCaptcha-collapseOne">
                                <strong>{$LANG->getModule('captcha')}</strong>
                            </button>
                        </div>
                        <div id="settingCaptcha-collapseOne" class="accordion-collapse collapse show" role="tabpanel" aria-labelledby="settingCaptcha-headingOne" data-bs-parent="#accordion-settingCaptcha">
                            <form action="{$FORM_ACTION}" method="post" class="ajax-submit" id="captcha-general-settings">
                                <ul class="list-group list-group-flush list-group-accordion list-group-striped">
                                    <li class="list-group-item">
                                        <div class="row g-2">
                                            <label for="nv_gfx_num" class="col-sm-5 col-form-label text-sm-end py-only-sm-0"><strong>{$LANG->getModule('captcha_num')}</strong></label>
                                            <div class="col-sm-7">
                                                <select name="nv_gfx_num" id="nv_gfx_num" class="form-select w-auto mw-100">
                                                    {for $i=2 to 9}
                                                    <option value="{$i}"{if $i eq $smarty.const.NV_GFX_NUM} selected{/if}>{$i}</option>
                                                    {/for}
                                                </select>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="list-group-item">
                                        <div class="row g-2">
                                            <label for="nv_gfx_width" class="col-sm-5 col-form-label text-sm-end py-only-sm-0"><strong>{$LANG->getModule('captcha_size')}</strong></label>
                                            <div class="col-sm-7">
                                                <div class="input-group">
                                                    <input class="form-control maxw-75" type="number" min="0" max="999" value="{$smarty.const.NV_GFX_WIDTH}" name="nv_gfx_width" id="nv_gfx_width">
                                                    <span class="input-group-text">x</span>
                                                    <input class="form-control maxw-75" type="number" min="0" max="999" value="{$smarty.const.NV_GFX_HEIGHT}" name="nv_gfx_height">
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="list-group-item">
                                        <div class="row g-2">
                                            <label for="recaptcha_ver" class="col-sm-5 col-form-label text-sm-end py-only-sm-0"><strong>{$LANG->getModule('recaptcha_ver')}</strong></label>
                                            <div class="col-sm-7">
                                                <select name="recaptcha_ver" id="recaptcha_ver" class="form-select w-auto mw-100">
                                                    {foreach from=$RECAPTCHA_VERS item=value}
                                                    <option value="{$value}"{if $value eq $DATA.recaptcha_ver} selected{/if}>{$value}</option>
                                                    {/foreach}
                                                </select>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="list-group-item">
                                        <div class="row g-2">
                                            <label for="recaptcha_sitekey" class="col-sm-5 col-form-label text-sm-end py-only-sm-0"><strong>{$LANG->getModule('recaptcha_sitekey')}</strong></label>
                                            <div class="col-sm-7">
                                                <div class="input-group">
                                                    <input class="form-control" type="text" value="{$RECAPTCHA_SITEKEY}" name="recaptcha_sitekey" id="recaptcha_sitekey" maxlength="200">
                                                    <a href="https://www.google.com/recaptcha/admin" target="_blank" data-bs-toggle="tooltip" title="{$LANG->getModule('recaptcha_guide')}" aria-label="{$LANG->getModule('recaptcha_guide')}" class="btn btn-secondary" data-bs-trigger="hover"><i class="fa-solid fa-circle-question"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="list-group-item">
                                        <div class="row g-2">
                                            <label for="recaptcha_secretkey" class="col-sm-5 col-form-label text-sm-end py-only-sm-0"><strong>{$LANG->getModule('recaptcha_secretkey')}</strong></label>
                                            <div class="col-sm-7">
                                                <div class="input-group">
                                                    <input class="form-control" type="text" value="{$RECAPTCHA_SECRETKEY}" name="recaptcha_secretkey" id="recaptcha_secretkey" maxlength="200">
                                                    <a href="https://www.google.com/recaptcha/admin" target="_blank" data-bs-toggle="tooltip" title="{$LANG->getModule('recaptcha_guide')}" aria-label="{$LANG->getModule('recaptcha_guide')}" class="btn btn-secondary" data-bs-trigger="hover"><i class="fa-solid fa-circle-question"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="list-group-item">
                                        <div class="row g-2">
                                            <label for="recaptcha_type" class="col-sm-5 col-form-label text-sm-end py-only-sm-0"><strong>{$LANG->getModule('recaptcha_type')}</strong></label>
                                            <div class="col-sm-7">
                                                <select name="recaptcha_type" id="recaptcha_type" class="form-select w-auto mw-100">
                                                    {foreach from=$RECAPTCHA_TYPE_LIST key=key item=value}
                                                    <option value="{$key}"{if $key eq $DATA.recaptcha_type} selected{/if}>{$value}</option>
                                                    {/foreach}
                                                </select>
                                                <div class="form-text">{$LANG->getModule('recaptcha_type_note')}</div>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="list-group-item">
                                        <div class="row g-2">
                                            <label for="turnstile_sitekey" class="col-sm-5 col-form-label text-sm-end py-only-sm-0"><strong>{$LANG->getModule('turnstile_sitekey')}</strong></label>
                                            <div class="col-sm-7">
                                                <div class="input-group">
                                                    <input class="form-control" type="text" value="{$TURNSTILE_SITEKEY}" name="turnstile_sitekey" id="turnstile_sitekey" maxlength="200">
                                                    <a href="https://dash.cloudflare.com/" target="_blank" data-bs-toggle="tooltip" title="{$LANG->getModule('recaptcha_guide')}" aria-label="{$LANG->getModule('recaptcha_guide')}" class="btn btn-secondary" data-bs-trigger="hover"><i class="fa-solid fa-circle-question"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="list-group-item">
                                        <div class="row g-2">
                                            <label for="turnstile_secretkey" class="col-sm-5 col-form-label text-sm-end py-only-sm-0"><strong>{$LANG->getModule('turnstile_secretkey')}</strong></label>
                                            <div class="col-sm-7">
                                                <div class="input-group">
                                                    <input class="form-control" type="text" value="{$TURNSTILE_SECRETKEY}" name="turnstile_secretkey" id="turnstile_secretkey" maxlength="200">
                                                    <a href="https://dash.cloudflare.com/" target="_blank" data-bs-toggle="tooltip" title="{$LANG->getModule('recaptcha_guide')}" aria-label="{$LANG->getModule('recaptcha_guide')}" class="btn btn-secondary" data-bs-trigger="hover"><i class="fa-solid fa-circle-question"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="list-group-item">
                                        <div class="row g-2">
                                            <div class="col-sm-7 offset-sm-5 text-center text-sm-start">
                                                <input type="hidden" name="checkss" value="{$CHECKSS}">
                                                <input type="hidden" name="selectedtab" value="{$SELECTEDTAB}">
                                                <input type="hidden" name="captchasave" value="1">
                                                <input type="submit" class="btn btn-primary" value="{$LANG->getModule('submit')}">
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </form>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <div class="accordion-header">
                            <button type="button" role="tab" id="settingCaptcha-headingTwo" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#settingCaptcha-collapseTwo" aria-expanded="false" aria-controls="settingCaptcha-collapseTwo">
                                <strong>{$LANG->getModule('captcha_for_module')}</strong>
                            </button>
                        </div>
                        <div id="settingCaptcha-collapseTwo" class="accordion-collapse collapse" role="tabpanel" aria-labelledby="settingCaptcha-headingTwo" data-bs-parent="#accordion-settingCaptcha">
                            <form action="{$FORM_ACTION}" method="post" class="ajax-submit" id="modcapt-settings">
                                <ul class="list-group list-group-flush list-group-accordion list-group-striped">
                                    {foreach from=$SITE_MODS key=title item=mod}
                                    {if $title eq 'users' or isset($MODULE_CONFIG[$title], $MODULE_CONFIG[$title].captcha_type)}
                                    {assign var="captcha_type" value=($title eq 'users' ? $DATA.captcha_type : $MODULE_CONFIG[$title].captcha_type) nocache}
                                    <li class="list-group-item">
                                        <div class="row g-2">
                                            <label class="col-5 col-form-label text-sm-end py-only-sm-0" for="captcha_type_{$title}"><strong>{$mod.custom_title}</strong></label>
                                            <div class="col-7">
                                                <select name="captcha_type[{$title}]" class="form-select" id="captcha_type_{$title}">
                                                    {foreach from=$CAPTCHA_OPTS item=value}
                                                    <option value="{$value}"{if not empty($captcha_type) and $value eq $captcha_type} selected{/if}>{$LANG->getModule("captcha_`$value`")}</option>
                                                    {/foreach}
                                                </select>
                                                <div class="form-text text-danger{if $captcha_type neq 'recaptcha' or ($captcha_type eq 'recaptcha' and not empty($DATA.recaptcha_sitekey) and not empty($DATA.recaptcha_secretkey))} d-none{/if}">{$LANG->getModule('captcha_type_recaptcha_note')}</div>
                                                <div class="form-text text-danger{if $captcha_type neq 'turnstile' or ($captcha_type eq 'turnstile' and not empty($DATA.turnstile_sitekey) and not empty($DATA.turnstile_secretkey))} d-none{/if}">{$LANG->getModule('captcha_type_turnstile_note')}</div>
                                            </div>
                                        </div>
                                    </li>
                                    {/if}
                                    {/foreach}

                                    <li class="list-group-item text-center">
                                        <div class="mb-2">{$LANG->getModule('select_all_as')}:</div>
                                        {foreach from=$CAPTCHA_OPTS item=value}
                                        <button type="button" class="btn btn-sm btn-secondary" data-toggle="selAllAs" data-type="{$value}">{$LANG->getModule("captcha_`$value`")}</button>
                                        {/foreach}
                                    </li>

                                    <li class="list-group-item text-center">
                                        <input type="hidden" name="checkss" value="{$CHECKSS}">
                                        <input type="hidden" name="modcapt" value="1">
                                        <input type="hidden" name="selectedtab" value="{$SELECTEDTAB}">
                                        <input type="submit" class="btn btn-primary" value="{$LANG->getModule('submit')}">
                                    </li>
                                </ul>
                            </form>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <div class="accordion-header">
                            <button type="button" role="tab" id="settingCaptcha-headingThree" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#settingCaptcha-collapseThree" aria-expanded="false" aria-controls="settingCaptcha-collapseThree">
                                <strong>{$LANG->getModule('captcha_area')}</strong>
                            </button>
                        </div>
                        <div id="settingCaptcha-collapseThree" class="accordion-collapse collapse" role="tabpanel" aria-labelledby="settingCaptcha-headingThree" data-bs-parent="#accordion-settingCaptcha">
                            <form action="{$FORM_ACTION}" method="post" class="ajax-submit" id="captarea-settings">
                                <ul class="list-group list-group-flush list-group-accordion list-group-striped">
                                    <li class="list-group-item">
                                        <div class="row g-2">
                                            <div class="col-sm-7 offset-sm-4">
                                                {foreach from=$CAPTCHA_AREA_LIST item=area}
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="captcha_area_{$area}" name="captcha_area[]" value="{$area}"{if str_contains($DATA.captcha_area, $area)} checked{/if}>
                                                    <label class="form-check-label" for="captcha_area_{$area}">{$LANG->getModule("captcha_area_`$area`")}</label>
                                                </div>
                                                {/foreach}
                                            </div>
                                        </div>
                                    </li>

                                    <li class="list-group-item text-center">
                                        <div class="row g-2">
                                            <div class="col-sm-7 offset-sm-4 text-center text-sm-start">
                                                <input type="hidden" name="checkss" value="{$CHECKSS}">
                                                <input type="hidden" name="captarea" value="1">
                                                <input type="hidden" name="selectedtab" value="{$SELECTEDTAB}">
                                                <input type="submit" class="btn btn-primary" value="{$LANG->getModule('submit')}">
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </form>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <div class="accordion-header">
                            <button type="button" role="tab" id="settingCaptcha-headingFourth" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#settingCaptcha-collapseFourth" aria-expanded="false" aria-controls="settingCaptcha-collapseFourth">
                                <strong>{$LANG->getModule('captcha_comm')}</strong>
                            </button>
                        </div>
                        <div id="settingCaptcha-collapseFourth" class="accordion-collapse collapse" role="tabpanel" aria-labelledby="settingCaptcha-headingFourth" data-bs-parent="#accordion-settingCaptcha">
                            <form action="{$FORM_ACTION}" method="post" class="ajax-submit" id="captcommarea-settings">
                                <ul class="list-group list-group-flush list-group-accordion list-group-striped">
                                    {foreach from=$SITE_MODS key=title item=mod}
                                    {if isset($MODULE_CONFIG[$title], $MODULE_CONFIG[$title].captcha_area_comm, $MODULE_CONFIG[$title].activecomm)}
                                    <li class="list-group-item">
                                        <div class="row g-2">
                                            <label for="captcha_area_comm_{$title}" class="col-sm-5 col-form-label text-sm-end py-only-sm-0"><strong>{$mod.custom_title}</strong></label>
                                            <div class="col-sm-7">
                                                <select name="captcha_area_comm[{$title}]" class="form-select" id="captcha_area_comm_{$title}">
                                                    {foreach from=$CAPTCHA_COMM_LIST key=key item=value}
                                                    <option value="{$key}"{if $key eq $MODULE_CONFIG[$title].captcha_area_comm} selected{/if}>{$value}</option>
                                                    {/foreach}
                                                </select>
                                            </div>
                                        </div>
                                    </li>
                                    {/if}
                                    {/foreach}

                                    <li class="list-group-item text-center">
                                        <div class="mb-2">{$LANG->getModule('select_all_as')}:</div>
                                        <div class="d-flex justify-content-center">
                                            <select class="form-select w-auto mw-100" data-toggle="selAllCaptComm" id="selAllCaptComm" name="selAllCaptComm">
                                                <option value="-1">{$LANG->getModule('captcha_comm_select')}</option>
                                                {foreach from=$CAPTCHA_COMM_LIST key=key item=value}
                                                <option value="{$key}">{$value}</option>
                                                {/foreach}
                                            </select>
                                        </div>
                                    </li>

                                    <li class="list-group-item text-center">
                                        <input type="hidden" name="checkss" value="{$CHECKSS}">
                                        <input type="hidden" name="captcommarea" value="1">
                                        <input type="hidden" name="selectedtab" value="{$SELECTEDTAB}">
                                        <input type="submit" class="btn btn-primary" value="{$LANG->getModule('submit')}">
                                    </li>
                                </ul>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            {* Quản lý IP cấm *}
            <div role="tabpanel" class="tab-pane{if 3 eq $SELECTEDTAB} active{/if}" id="settingIp">
                <ul class="list-group">
                    <li class="list-group-item active">
                        <strong>{$LANG->getModule('banip')}</strong>
                    </li>
                    <ul class="list-group list-group-flush list" id="banips" data-url="{$FORM_ACTION}&amp;action=bip" data-del-url="{$FORM_ACTION}&amp;action=delbip" data-confirm="{$LANG->getModule('ip_delete_confirm')}" data-checkss="{$CHECKSS}"></ul>
                </ul>
            </div>
            {* Thiết lập Cross-Site *}
            <div role="tabpanel" class="tab-pane{if 4 eq $SELECTEDTAB} active{/if}" id="settingCORS">
                <form action="{$FORM_ACTION}" method="post" class="ajax-submit" id="cors-settings">
                    <ul class="list-group list-group-striped">
                        <li class="list-group-item active text-bg-primary">
                            <strong>{$LANG->getModule('cors')}</strong>
                        </li>

                        <li class="list-group-item item">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="crosssite_restrict" name="crosssite_restrict"{if $CORS.crosssite_restrict} checked{/if}>
                                <label class="form-check-label" for="crosssite_restrict"><strong>{$LANG->getModule('cors_site_restrict')}</strong></label>
                            </div>
                            <div class="form-text">{$LANG->getModule('cors_site_restrict_help')}</div>
                            <div class="collapse{if $CORS.crosssite_restrict} show{/if}">
                                <div class="mb-2 pt-2"><i class="fa-solid fa-star"></i> <strong>{$LANG->getModule('cors_exceptions')}:</strong></div>
                                <hr>
                                <div class="row g-2 mb-3">
                                    <label for="crosssite_valid_domains" class="col-sm-5 col-form-label text-sm-end py-only-sm-0"><strong>{$LANG->getModule('cors_site_valid_domains')}</strong></label>
                                    <div class="col-sm-7">
                                        <textarea rows="3" class="form-control" id="crosssite_valid_domains" name="crosssite_valid_domains">{$CORS.crosssite_valid_domains}</textarea>
                                        <div class="form-text">{$LANG->getModule('cors_valid_domains_help')}</div>
                                    </div>
                                </div>

                                <div class="row g-2 mb-3">
                                    <label for="crosssite_valid_ips" class="col-sm-5 col-form-label text-sm-end py-only-sm-0"><strong>{$LANG->getModule('cors_site_valid_ips')}</strong></label>
                                    <div class="col-sm-7">
                                        <textarea rows="3" class="form-control" id="crosssite_valid_ips" name="crosssite_valid_ips">{$CORS.crosssite_valid_ips}</textarea>
                                        <div class="form-text">{$LANG->getModule('cors_valid_ips_help')}</div>
                                    </div>
                                </div>

                                <div class="row g-2">
                                    <label for="crosssite_allowed_variables" class="col-sm-5 col-form-label text-sm-end py-only-sm-0"><strong>{$LANG->getModule('cors_site_allowed_variables')}</strong></label>
                                    <div class="col-sm-7">
                                        <textarea rows="3" class="form-control" id="crosssite_allowed_variables" name="crosssite_allowed_variables">{$CORS.crosssite_allowed_variables}</textarea>
                                        <div class="form-text">{$LANG->getModule('cors_site_allowed_variables_note')}</div>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item item">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="crossadmin_restrict" name="crossadmin_restrict"{if $CORS.crossadmin_restrict} checked{/if}>
                                <label class="form-check-label" for="crossadmin_restrict"><strong>{$LANG->getModule('cors_admin_restrict')}</strong></label>
                            </div>
                            <div class="form-text">{$LANG->getModule('cors_admin_restrict_help')}</div>
                            <div class="collapse{if $CORS.crossadmin_restrict} show{/if}">
                                <div class="mb-2 pt-2"><i class="fa-solid fa-star"></i> <strong>{$LANG->getModule('cors_exceptions')}:</strong></div>
                                <hr>
                                <div class="row g-2 mb-3">
                                    <label for="crossadmin_valid_domains" class="col-sm-5 col-form-label text-sm-end py-only-sm-0"><strong>{$LANG->getModule('cors_admin_valid_domains')}</strong></label>
                                    <div class="col-sm-7">
                                        <textarea rows="3" class="form-control" id="crossadmin_valid_domains" name="crossadmin_valid_domains">{$CORS.crossadmin_valid_domains}</textarea>
                                        <div class="form-text">{$LANG->getModule('cors_valid_domains_help')}</div>
                                    </div>
                                </div>

                                <div class="row g-2">
                                    <label for="crossadmin_valid_ips" class="col-sm-5 col-form-label text-sm-end py-only-sm-0"><strong>{$LANG->getModule('cors_admin_valid_ips')}</strong></label>
                                    <div class="col-sm-7">
                                        <textarea rows="3" class="form-control" id="crossadmin_valid_ips" name="crossadmin_valid_ips">{$CORS.crossadmin_valid_ips}</textarea>
                                        <div class="form-text">{$LANG->getModule('cors_valid_ips_help')}</div>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="row g-2">
                                <div class="col-sm-7 offset-sm-5">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" value="1" role="switch" id="allow_null_origin" name="allow_null_origin"{if $CORS.allow_null_origin} checked{/if}>
                                        <label class="form-check-label" for="allow_null_origin">{$LANG->getModule('allow_null_origin')}</label>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="row g-2">
                                <label for="ip_allow_null_origin" class="col-sm-5 col-form-label text-sm-end py-only-sm-0"><strong>{$LANG->getModule('ip_allow_null_origin')}</strong></label>
                                <div class="col-sm-7">
                                    <textarea rows="3" class="form-control" id="ip_allow_null_origin" name="ip_allow_null_origin">{$CORS.ip_allow_null_origin}</textarea>
                                    <div class="form-text">{$LANG->getModule('ip_allow_null_origin_help')}</div>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="row g-2">
                                <div class="col-sm-7 offset-sm-5">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" value="1" id="auto_acao" role="switch" name="auto_acao"{if $CORS.auto_acao} checked{/if}>
                                        <label class="form-check-label" for="auto_acao">{$LANG->getModule('auto_acao')}</label>
                                    </div>
                                    <div class="form-text">{$LANG->getModule('auto_acao_note')}</div>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="row g-2">
                                <label for="load_files_seccode" class="col-sm-5 col-form-label text-sm-end py-only-sm-0"><strong>{$LANG->getModule('load_files_seccode')}</strong></label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input type="text" name="load_files_seccode" id="load_files_seccode" value="{$CORS.load_files_seccode}" class="form-control" readonly>
                                        <button class="btn btn-secondary" type="button" data-clipboard-target="#load_files_seccode" data-toggle="clipboard" data-title="{$LANG->getModule('value_copied')}" aria-label="{$LANG->getGlobal('copy')}"><i class="fa-solid fa-copy"></i></button>
                                        <button class="btn btn-secondary" type="button" data-target="#load_files_seccode" data-toggle="seccode_create" aria-label="{$LANG->getModule('recreate_files_seccode')}"><i class="fa-solid fa-retweet"></i></button>
                                        <button class="btn btn-secondary" type="button" data-target="#load_files_seccode" data-toggle="seccode_remove" aria-label="{$LANG->getGlobal('delete')}"><i class="fa-solid fa-trash text-danger"></i></button>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="row g-2">
                                <div class="col-sm-7 offset-sm-5 text-center text-sm-start">
                                    <input type="hidden" name="selectedtab" value="{$SELECTEDTAB}">
                                    <input type="hidden" name="checkss" value="{$CHECKSS}">
                                    <input type="hidden" name="corssave" value="1">
                                    <input type="submit" value="{$LANG->getGlobal('submit')}" class="btn btn-primary">
                                </div>
                            </div>
                        </li>
                    </ul>
                </form>
            </div>
            {/if}
            {* Thiết lập CSP *}
            <div role="tabpanel" class="tab-pane{if 5 eq $SELECTEDTAB} active{/if}" id="settingCSP">
                <form action="{$FORM_ACTION}" method="post" class="ajax-submit" id="csp-settings" data-confirm="{$LANG->getModule('csp_source_none_confirm')}">
                    <ul class="list-group">
                        <li class="list-group-item active">
                            <strong>{$LANG->getModule('csp')}</strong>
                        </li>

                        <li class="list-group-item">
                            <div class="form-text mb-2">{$LANG->getModule('csp_desc')} <a href="https://content-security-policy.com/" target="_blank">{$LANG->getModule('csp_details')}</a></div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" value="1" id="nv_csp_act" name="nv_csp_act" data-target="#csp_options"{if $DATA.nv_csp_act} checked{/if}>
                                <label class="form-check-label" for="nv_csp_act"><strong>{$LANG->getModule('csp_act')}</strong></label>
                            </div>
                        </li>
                    </ul>
                    <div class="accordion collapse{if $DATA.nv_csp_act} show{/if}" id="csp_options" role="tablist">
                        <div class="pt-3">
                            {foreach from=$CSP_DIRS key=name item=dir}
                            <div class="accordion-item directive">
                                <div class="accordion-header">
                                    <button type="button" class="accordion-button" role="tab" id="heading-{$name}" data-bs-toggle="collapse" data-bs-target="#collapse-{$name}" aria-expanded="true" aria-controls="collapse-{$name}">
                                        <strong>{$name}</strong>
                                    </button>
                                </div>
                                <div id="collapse-{$name}" class="accordion-collapse collapse show" role="tabpanel" aria-labelledby="heading-{$name}">
                                    <div class="accordion-body">
                                        <div class="form-text mt-0 mb-2">{$dir.desc}</div>
                                        <div class="mb-2">
                                            <div class="mb-1"><strong>{$LANG->getModule('csp_source_value')}</strong></div>
                                            <div>
                                                {foreach from=$dir.sources key=key item=source}
                                                {if $key neq 'hosts'}
                                                <div class="form-check form-check-inline">
                                                    <input type="checkbox" class="form-check-input" id="directives_{$name}_{$source.key}" name="directives[{$name}][{$source.key}]" value="1" data-toggle="{$source.key}"{$source.checked ? ' checked' : ''}{$source.disabled ? ' disabled' : ''}>
                                                    <label class="form-check-label" for="directives_{$name}_{$source.key}">{$source.key}</label>
                                                    <a href="#" data-bs-toggle="popover" data-bs-placement="auto" data-bs-trigger="focus" data-bs-content="{$source.name}"><i class="fa-regular fa-circle-question"></i></a>
                                                </div>
                                                {/if}
                                                {/foreach}
                                            </div>
                                        </div>
                                        {foreach from=$dir.sources key=key item=source}
                                        {if $key eq 'hosts'}
                                        <div class="mb-1"><strong>{$source.name}:</strong> <a href="#" data-bs-toggle="popover" data-bs-placement="auto" data-bs-trigger="focus" data-bs-content="{$LANG->getModule('csp_source_hosts_note')}"><i class="fa-regular fa-circle-question"></i></a></div>
                                        <textarea type="text" class="form-control" name="directives[{$name}][{$source.key}]"{$source.disabled ? ' disabled' : ''}>{$source.val}</textarea>
                                        <div class="form-text">{$LANG->getModule('csp_source_hosts_help')}</div>
                                        {/if}
                                        {/foreach}
                                        {if $name eq 'script-src'}
                                        <div class="mt-3">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" value="1" id="nv_csp_script_nonce" name="nv_csp_script_nonce"{if $DATA.nv_csp_script_nonce} checked{/if}>
                                                <label class="form-check-label" for="nv_csp_script_nonce"><strong>{$LANG->getModule('csp_script_nonce')}</strong></label>
                                                <a href="#" data-bs-toggle="popover" data-bs-placement="auto" data-bs-trigger="focus" data-bs-content="{$LANG->getModule('csp_script_nonce_note')}"><i class="fa-regular fa-circle-question"></i></a>
                                            </div>
                                        </div>
                                        {/if}
                                    </div>
                                </div>
                            </div>
                            {/foreach}
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <input type="hidden" name="selectedtab" value="{$SELECTEDTAB}">
                        <input type="hidden" name="checkss" value="{$CHECKSS}">
                        <input type="hidden" name="cspsave" value="1">
                        <input type="submit" value="{$LANG->getGlobal('submit')}" class="btn btn-primary">
                    </div>
                </form>
            </div>
            {* Thiết lập RP *}
            <div role="tabpanel" class="tab-pane{if 6 eq $SELECTEDTAB} active{/if}" id="settingRP">
                <form action="{$FORM_ACTION}" method="post" class="ajax-submit" id="rp-settings">
                    <ul class="list-group">
                        <li class="list-group-item active">
                            <strong>{$LANG->getModule('rp')}</strong>
                        </li>

                        <li class="list-group-item">
                            <p class="form-text">{$LANG->getModule('rp_desc')} <a href="https://www.w3.org/TR/referrer-policy/" target="_blank">{$LANG->getModule('rp_details')}</a></p>
                            <p><button type="button" class="btn btn-default btn-sm" data-bs-toggle="collapse" data-bs-target="#rp_directives_help" aria-expanded="false" aria-controls="rp_directives_help"><i class="fa-solid fa-arrow-right"></i> {$LANG->getModule('rp_directives_help')}</button></p>
                            <div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" value="1" id="nv_rp_act" name="nv_rp_act" data-target="#rp_options"{if $DATA.nv_rp_act} checked{/if}>
                                    <label for="nv_rp_act" class="form-check-label"><strong>{$LANG->getModule('rp_act')}</strong></label>
                                </div>
                            </div>
                            <div class="collapse{if $DATA.nv_rp_act} show{/if}" id="rp_options">
                                <div class="pt-3">
                                    <label class="form-label" for="nv_rp"><strong>{$LANG->getModule('rp_directives')}</strong></label>
                                    <input class="form-control" type="text" value="{$DATA.nv_rp}" id="nv_rp" name="nv_rp" maxlength="500">
                                    <div class="form-text">{$LANG->getModule('rp_note')}</div>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item text-center">
                            <input type="hidden" name="checkss" value="{$CHECKSS}">
                            <input type="hidden" name="selectedtab" value="{$SELECTEDTAB}">
                            <input type="hidden" name="rpsave" value="1">
                            <input type="submit" value="{$LANG->getGlobal('submit')}" class="btn btn-primary">
                        </li>
                    </ul>

                    <div class="collapse" id="rp_directives_help">
                        <div class="pt-5">
                            <div class="card">
                                <div class="card-body border-bottom">
                                    <h2><strong>{$LANG->getModule('rp_directives_help')}</strong></h2>
                                    <p>{$LANG->getModule('rp_desc2')}</p>
                                    <p class="mb-0"><strong>{$LANG->getModule('rp_directives')}:</strong></p>
                                </div>
                                <ul class="list-group list-group-flush">
                                    {foreach from=$RP_DIRECTIVES key=name item=desc}
                                    <li class="list-group-item">
                                        <code><strong>{$name}</strong></code>: {$desc}
                                    </li>
                                    {/foreach}
                                </ul>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            {* Thiết lập PP *}
            <div role="tabpanel" class="tab-pane{if 7 eq $SELECTEDTAB} active{/if}" id="settingPP">
                <form action="{$FORM_ACTION}" method="post" class="ajax-submit" id="pp-settings" data-cfnone="{$LANG->getModule('csp_source_none_confirm')}">
                    <ul class="list-group">
                        <li class="list-group-item active">
                            <strong>{$LANG->getModule('pp')}</strong>
                        </li>

                        <li class="list-group-item">
                            <p class="form-text mt-0 mb-2">{$LANG->getModule('pp_desc')} <a href="https://www.w3.org/TR/permissions-policy/" target="_blank">{$LANG->getModule('csp_details')}</a>.</p>
                            <p class="form-text mt-0 mb-2">{$LANG->getModule('pp_desc2')}.</p>
                            <p class="form-text mt-0 mb-2">{$LANG->getModule('pp_desc3')}.</p>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input type="checkbox" class="form-check-input" value="1" id="nv_pp_act" name="nv_pp_act" data-target="#pp_options" data-toggle="pp_act"{if $DATA.nv_pp_act} checked{/if}>
                                    <label class="form-check-label" for="nv_pp_act"><strong>{$LANG->getModule('pp_act')}</strong></label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="checkbox" class="form-check-input" value="1" id="nv_fp_act" name="nv_fp_act" data-target="#pp_options" data-toggle="pp_act"{if $DATA.nv_fp_act} checked{/if}>
                                    <label class="form-check-label" for="nv_fp_act"><strong>{$LANG->getModule('fp_act')}</strong></label>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <div class="mt-3 accordion collapse{if $DATA.nv_pp_act or $DATA.nv_fp_act} show{/if}" id="pp_options" role="tablist">
                        {foreach from=$PP_DIRS key=name item=dir}
                        <div class="accordion-item directive">
                            <div class="accordion-header">
                                <button class="accordion-button" type="button" role="tab" id="heading-pp-{$name}" data-bs-toggle="collapse" data-bs-target="#collapse-pp-{$name}" aria-expanded="true" aria-controls="collapse-pp-{$name}">
                                    <strong>{$name}</strong>
                                </button>
                            </div>
                            <div id="collapse-pp-{$name}" class="accordion-collapse collapse show" role="tabpanel" aria-labelledby="heading-pp-{$name}">
                                <div class="accordion-body">
                                    <div class="form-text mt-0 mb-2">{$dir.desc}</div>
                                    <div class="mb-2">
                                        <p class="mb-1"><strong>{$LANG->getModule('csp_source_value')}</strong></p>
                                        {foreach from=$dir.sources key=key item=source}
                                        {if $key neq 'hosts'}
                                        <div class="form-check form-check-inline">
                                            <input type="checkbox" class="form-check-input" id="directives_pp_{$name}_{$source.key}" name="directives[{$name}][{$source.key}]" value="1" data-toggle="{$source.key}"{$source.checked ? ' checked' : ''}{$source.disabled ? ' disabled' : ''}>
                                            <label class="form-check-label" for="directives_pp_{$name}_{$source.key}">{$source.key}</label>
                                            <a href="#" data-bs-toggle="popover" data-bs-placement="auto" data-bs-trigger="focus" data-bs-content="{$source.name}"><i class="fa-regular fa-circle-question"></i></a>
                                        </div>
                                        {/if}
                                        {/foreach}
                                    </div>
                                    {foreach from=$dir.sources key=key item=source}
                                    {if $key eq 'hosts'}
                                    <p class="mb-1"><strong>{$source.name}:</strong> <a href="#" data-bs-toggle="popover" data-bs-placement="auto" data-bs-trigger="focus" data-bs-content="{$LANG->getModule('pp_source_hosts_note')}"><i class="fa-regular fa-circle-question"></i></a></p>
                                    <textarea rows="{$source.rows}" type="text" class="form-control" name="directives[{$name}][{$source.key}]"{$source.disabled ? ' disabled' : ''}>{$source.val}</textarea>
                                    <div class="form-text">{$LANG->getModule('csp_source_hosts_help')}</div>
                                    {/if}
                                    {/foreach}
                                </div>
                            </div>
                        </div>
                        {/foreach}
                    </div>
                    <div class="text-center mt-3">
                        <input type="hidden" name="selectedtab" value="{$SELECTEDTAB}">
                        <input type="hidden" name="checkss" value="{$CHECKSS}">
                        <input type="hidden" name="ppsave" value="1">
                        <input type="submit" value="{$LANG->getGlobal('submit')}" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="gselectedtab" value="{$SELECTEDTAB}">
<div id="page-tool" class="modal fade" tabindex="-1" aria-labelledby="page-tool-label" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title fs-5 fw-medium" id="page-tool-label"></div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>
