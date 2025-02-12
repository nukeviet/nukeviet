<!-- BEGIN: main -->
<link type="text/css" href="{ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<script type="text/javascript" src="{ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{ASSETS_LANG_STATIC_URL}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{ASSETS_STATIC_URL}/js/clipboard/clipboard.min.js"></script>

<div class="row">
    <div class="col-md-6 col-md-push-18">
        <div class="well visible-xs-block visible-sm-block">
            <select class="form-control" id="settingSelect">
                <!-- BEGIN: sys_tabs -->
                <option value="settingBasic" {TAB0_SEL}>{LANG.general_settings}</option>
                <option value="settingFlood" {TAB1_SEL}>{LANG.flood_blocker}</option>
                <option value="settingCaptcha" {TAB2_SEL}>{LANG.captcha}</option>
                <option value="settingIp" {TAB3_SEL}>{LANG.banip}</option>
                <option value="settingCORS" {TAB4_SEL}>{LANG.cors}</option>
                <!-- END: sys_tabs -->
                <option value="settingCSP" {TAB5_SEL}>{LANG.csp}</option>
                <option value="settingRP" {TAB6_SEL}>{LANG.rp}</option>
                <option value="settingPP" {TAB7_SEL}>{LANG.pp}</option>
            </select>
        </div>
        <ul class="nav nav-pills nav-stacked hidden-xs hidden-sm" role="tablist" id="settingTabs">
            <!-- BEGIN: sys_tabs2 -->
            <li role="presentation" class="{TAB0_ACTIVE}"><a href="#settingBasic" aria-controls="settingBasic" aria-offsets="0" role="tab" data-toggle="pill" data-location="{FORM_ACTION}&amp;selectedtab=0"><em class="fa fa-caret-right"></em> {LANG.general_settings}</a></li>
            <li role="presentation" class="{TAB1_ACTIVE}"><a href="#settingFlood" aria-controls="settingFlood" aria-offsets="1" role="tab" data-toggle="pill" data-location="{FORM_ACTION}&amp;selectedtab=1" data-loaded="false" data-type="1" data-load-url="{FORM_ACTION}&amp;action=fiplist"><em class="fa fa-caret-right"></em> {LANG.flood_blocker}</a></li>
            <li role="presentation" class="{TAB2_ACTIVE}"><a href="#settingCaptcha" aria-controls="settingCaptcha" aria-offsets="2" role="tab" data-toggle="pill" data-location="{FORM_ACTION}&amp;selectedtab=2"><em class="fa fa-caret-right"></em> {LANG.captcha}</a></li>
            <li role="presentation" class="{TAB3_ACTIVE}"><a href="#settingIp" aria-controls="settingIp" aria-offsets="3" role="tab" data-toggle="pill" data-location="{FORM_ACTION}&amp;selectedtab=3" data-loaded="false" data-type="0" data-load-url="{FORM_ACTION}&amp;action=biplist"><em class="fa fa-caret-right"></em> {LANG.banip}</a></li>
            <li role="presentation" class="{TAB4_ACTIVE}"><a href="#settingCORS" aria-controls="settingCORS" aria-offsets="4" role="tab" data-toggle="pill" data-location="{FORM_ACTION}&amp;selectedtab=4"><em class="fa fa-caret-right"></em> {LANG.cors}</a></li>
            <!-- END: sys_tabs2 -->
            <li role="presentation" class="{TAB5_ACTIVE}"><a href="#settingCSP" aria-controls="settingCSP" aria-offsets="5" role="tab" data-toggle="pill" data-location="{FORM_ACTION}&amp;selectedtab=5"><em class="fa fa-caret-right"></em> {LANG.csp}</a></li>
            <li role="presentation" class="{TAB6_ACTIVE}"><a href="#settingRP" aria-controls="settingRP" aria-offsets="6" role="tab" data-toggle="pill" data-location="{FORM_ACTION}&amp;selectedtab=6"><em class="fa fa-caret-right"></em> {LANG.rp}</a></li>
            <li role="presentation" class="{TAB7_ACTIVE}"><a href="#settingPP" aria-controls="settingPP" aria-offsets="7" role="tab" data-toggle="pill" data-location="{FORM_ACTION}&amp;selectedtab=7"><em class="fa fa-caret-right"></em> {LANG.pp}</a></li>
        </ul>
    </div>
    <div class="col-md-18 col-md-pull-6">
        <div class="tab-content">
            <!-- BEGIN: sys_contents -->
            <!-- Các thiết lập chính -->
            <div role="tabpanel" class="tab-pane{TAB0_ACTIVE}" id="settingBasic">
                <form action="{FORM_ACTION}" method="post" class="form-horizontal ajax-submit" id="secForm">
                    <ul class="list-group type2n1">
                        <li class="list-group-item active">
                            <strong>{LANG.general_settings}</strong>
                        </li>

                        <li class="list-group-item">
                            <div class="form-group mb-0">
                                <label class="col-sm-10 control-label hidden-xs"><strong>{LANG.is_login_blocker}</strong></label>
                                <div class="col-sm-14">
                                    <label class="checkbox-inline">
                                        <input type="checkbox" class="form-control" value="1" name="is_login_blocker" {IS_LOGIN_BLOCKER} />
                                        <strong class="visible-xs-inline-block">{LANG.is_login_blocker}</strong>
                                    </label>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="form-group mb-0">
                                <label class="col-sm-10 control-label"><strong>{LANG.login_number_tracking}</strong></label>
                                <div class="col-sm-14">
                                    <div class="input-group" style="width:fit-content">
                                        <input type="text" value="{LOGIN_NUMBER_TRACKING}" name="login_number_tracking" class="required form-control text-center w60 number" />
                                        <span class="input-group-addon" id="basic-addon2" style="border-left: 0;">{LANG.login_time_tracking}</span>
                                        <input type="text" value="{LOGIN_TIME_TRACKING}" name="login_time_tracking" class="required form-control text-center w60 number" style="border-left: 0;" />
                                        <span class="input-group-addon" id="basic-addon2">{GLANG.min}</span>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="form-group mb-0">
                                <label class="col-sm-10 control-label"><strong>{LANG.login_time_ban}</strong></label>
                                <div class="col-sm-14">
                                    <div class="input-group" style="width:fit-content">
                                        <input type="text" value="{LOGIN_TIME_BAN}" name="login_time_ban" class="required form-control text-center w60 number" />
                                        <span class="input-group-addon" id="basic-addon2">{GLANG.min}</span>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="form-group mb-0">
                                <label class="col-sm-10 control-label"><strong>{LANG.passshow_button}</strong></label>
                                <div class="col-sm-14">
                                    <select class="form-control" name="passshow_button">
                                        <!-- BEGIN: passshow_button -->
                                        <option value="{OPT.val}" {OPT.sel}>{OPT.name}</option>
                                        <!-- END: passshow_button -->
                                    </select>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="form-group mb-0">
                                <label class="col-sm-10 control-label"><strong>{LANG.two_step_verification}</strong></label>
                                <div class="col-sm-14">
                                    <select name="two_step_verification" class="form-control">
                                        <!-- BEGIN: two_step_verification -->
                                        <option value="{TWO_STEP_VERIFICATION.key}" {TWO_STEP_VERIFICATION.selected}>{TWO_STEP_VERIFICATION.title}</option>
                                        <!-- END: two_step_verification -->
                                    </select>
                                    <div class="help-block mb-0">{LANG.two_step_verification_note}</div>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="form-group mb-0">
                                <label class="col-sm-10 control-label"><strong>{LANG.admin_2step_opt}</strong></label>
                                <div class="col-sm-14">
                                    <!-- BEGIN: admin_2step_opt -->
                                    <div>
                                        <label><input type="checkbox" class="form-control" name="admin_2step_opt[]" value="{ADMIN_2STEP_OPT.key}" {ADMIN_2STEP_OPT.checked}> {ADMIN_2STEP_OPT.title}</label>
                                        <!-- BEGIN: link_config -->
                                        (<a href="{LINK_CONFIG}" target="_blank">{LANG.admin_2step_appconfig}</a>)
                                        <!-- END: link_config -->
                                    </div>
                                    <!-- END: admin_2step_opt -->
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="form-group mb-0">
                                <label class="col-sm-10 control-label"><strong>{LANG.admin_2step_default}</strong></label>
                                <div class="col-sm-14">
                                    <select name="admin_2step_default" class="form-control">
                                        <!-- BEGIN: admin_2step_default -->
                                        <option value="{ADMIN_2STEP_DEFAULT.key}" {ADMIN_2STEP_DEFAULT.selected}>{ADMIN_2STEP_DEFAULT.title}</option>
                                        <!-- END: admin_2step_default -->
                                    </select>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="form-group mb-0">
                                <label class="col-sm-10 control-label hidden-xs"><strong>{LANG.nv_anti_agent}</strong></label>
                                <div class="col-sm-14">
                                    <label class="checkbox-inline">
                                        <input type="checkbox" class="form-control" value="1" name="nv_anti_agent" {ANTI_AGENT} />
                                        <strong class="visible-xs-inline-block">{LANG.nv_anti_agent}</strong>
                                    </label>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="form-group mb-0">
                                <label class="col-sm-10 control-label"><strong>{LANG.proxy_blocker}</strong></label>
                                <div class="col-sm-14">
                                    <select name="proxy_blocker" class="form-control">
                                        <!-- BEGIN: proxy_blocker -->
                                        <option value="{PROXYOP}" {PROXYSELECTED}>{PROXYVALUE} </option>
                                        <!-- END: proxy_blocker -->
                                    </select>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="form-group mb-0">
                                <label class="col-sm-10 control-label hidden-xs"><strong>{LANG.str_referer_blocker}</strong></label>
                                <div class="col-sm-14">
                                    <label class="checkbox-inline">
                                        <input type="checkbox" class="form-control" value="1" name="str_referer_blocker" {REFERER_BLOCKER} />
                                        <strong class="visible-xs-inline-block">{LANG.str_referer_blocker}</strong>
                                    </label>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="form-group mb-0">
                                <label class="col-sm-10 control-label hidden-xs"><strong>{LANG.nv_anti_iframe}</strong></label>
                                <div class="col-sm-14">
                                    <label class="checkbox-inline">
                                        <input type="checkbox" class="form-control" value="1" name="nv_anti_iframe" {ANTI_IFRAME} />
                                        <strong class="visible-xs-inline-block">{LANG.nv_anti_iframe}</strong>
                                    </label>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="form-group mb-0">
                                <label class="col-sm-10 control-label"><strong>{LANG.nv_allowed_html_tags}</strong></label>
                                <div class="col-sm-14">
                                    <textarea name="nv_allowed_html_tags" class="form-control required" style="height: 100px">{NV_ALLOWED_HTML_TAGS}</textarea>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="form-group mb-0">
                                <label class="col-sm-10 control-label hidden-xs"><strong>{LANG.domains_restrict}</strong></label>
                                <div class="col-sm-14">
                                    <label class="checkbox-inline">
                                        <input type="checkbox" class="form-control" name="domains_restrict" value="1" {DOMAINS_RESTRICT}>
                                        <strong class="visible-xs-inline-block">{LANG.domains_restrict}</strong>
                                    </label>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="form-group mb-0">
                                <label class="col-sm-10 control-label"><strong>{LANG.domains_whitelist}</strong></label>
                                <div class="col-sm-14">
                                    <textarea name="domains_whitelist" class="form-control" rows="5">{DOMAINS_WHITELIST}</textarea>
                                    <div class="help-block mb-0">{LANG.domains_whitelist_note}</div>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="form-group mb-0">
                                <label class="col-sm-10 control-label hidden-xs"><strong>{LANG.XSSsanitize}</strong></label>
                                <div class="col-sm-14">
                                    <label class="checkbox-inline">
                                        <input type="checkbox" class="form-control" name="XSSsanitize" value="1" {XSSSANITIZE}>
                                        <strong class="visible-xs-inline-block">{LANG.XSSsanitize}</strong>
                                    </label>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="form-group mb-0">
                                <label class="col-sm-10 control-label hidden-xs"><strong>{LANG.admin_XSSsanitize}</strong></label>
                                <div class="col-sm-14">
                                    <label class="checkbox-inline">
                                        <input type="checkbox" class="form-control" name="admin_XSSsanitize" value="1" {ADMIN_XSSSANITIZE}>
                                        <strong class="visible-xs-inline-block">{LANG.admin_XSSsanitize}</strong>
                                    </label>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="form-group mb-0">
                                <label class="col-sm-10 control-label"><strong>{LANG.request_uri_check}</strong></label>
                                <div class="col-sm-14">
                                    <select name="request_uri_check" class="form-control">
                                        <!-- BEGIN: request_uri_check -->
                                        <option value="{URI_CHECK.val}" {URI_CHECK.sel}>{URI_CHECK.name}</option>
                                        <!-- END: request_uri_check -->
                                    </select>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div><strong>{LANG.end_url_variables}</strong></div>
                            <div class="help-block">{LANG.end_url_variables_note}</div>
                            <ul class="list-group mb-0 list">
                                <!-- BEGIN: end_url_variable -->
                                <li class="list-group-item item">
                                    <input type="hidden" name="parameters[]" value="{VAR.parameters}" />
                                    <div class="form-group">
                                        <label class="col-sm-6 control-label">{LANG.end_url_variable_name}</label>
                                        <div class="col-sm-18">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="end_url_variables[]" value="{VAR.name}" maxlength="50" />
                                                <span class="input-group-btn">
                                                    <button class="btn btn-info add-variable" type="button"><em class="fa fa-plus fa-fw"></em></button>
                                                    <button class="btn btn-info del-variable" type="button"><em class="fa fa-times fa-fw"></em></button>
                                                </span>
                                            </div>
                                            <div class="help-block mb-0">{LANG.end_url_variable_name_note}</div>
                                        </div>
                                    </div>

                                    <div class="form-inline">
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label">{LANG.end_url_variable_dataformat}</label>
                                            <div class="col-sm-18">
                                                <div class="checkbox-inline">
                                                    <label><input type="checkbox" class="form-control parameter" value="lower" {VAR.lower_checked}> {LANG.lowercase_letters}</label>
                                                </div>
                                                <div class="checkbox-inline">
                                                    <label><input type="checkbox" class="form-control parameter" value="upper" {VAR.upper_checked}> {LANG.uppercase_letters}</label>
                                                </div>
                                                <div class="checkbox-inline">
                                                    <label><input type="checkbox" class="form-control parameter" value="number" {VAR.number_checked}> {LANG.number}</label>
                                                </div>
                                                <div class="checkbox-inline">
                                                    <label><input type="checkbox" class="form-control parameter" value="dash" {VAR.dash_checked}> {LANG.dash}</label>
                                                </div>
                                                <div class="checkbox-inline">
                                                    <label><input type="checkbox" class="form-control parameter" value="under" {VAR.under_checked}> {LANG.underline}</label>
                                                </div>
                                                <div class="checkbox-inline">
                                                    <label><input type="checkbox" class="form-control parameter" value="dot" {VAR.dot_checked}> {LANG.dot}</label>
                                                </div>
                                                <div class="checkbox-inline">
                                                    <label><input type="checkbox" class="form-control parameter" value="at" {VAR.at_checked}> {LANG.at_sign}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <!-- END: end_url_variable -->
                            </ul>
                        </li>
                    </ul>
                    <div class="text-center">
                        <input type="hidden" name="selectedtab" value="{SELECTEDTAB}" />
                        <input type="hidden" name="checkss" value="{CHECKSS}" />
                        <input type="hidden" name="basicsave" value="1" />
                        <button type="submit" class="btn btn-primary">{LANG.submit}</button>
                    </div>
                </form>
            </div>

            <!-- Chống tấn công ngập lụt -->
            <div role="tabpanel" class="tab-pane{TAB1_ACTIVE}" id="settingFlood">
                <form action="{FORM_ACTION}" method="post" class="form-horizontal ajax-submit" id="floodForm" style="margin-bottom:20px">
                    <ul class="list-group type2n1">
                        <li class="list-group-item active">
                            <strong>{LANG.flood_blocker}</strong>
                        </li>

                        <li class="list-group-item">
                            <div class="form-group mb-0">
                                <label class="col-sm-10 control-label hidden-xs"><strong>{LANG.is_flood_blocker}</strong></label>
                                <div class="col-sm-14">
                                    <label class="checkbox-inline">
                                        <input type="checkbox" class="form-control" value="1" name="is_flood_blocker" {IS_FLOOD_BLOCKER} />
                                        <strong class="visible-xs-inline-block">{LANG.is_flood_blocker}</strong>
                                    </label>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="form-group mb-0">
                                <label class="col-sm-10 control-label hidden-xs"><strong>{LANG.max_requests_60}</strong></label>
                                <div class="col-sm-14">
                                    <input type="text" value="{MAX_REQUESTS_60}" name="max_requests_60" class="required form-control number text-center w100" maxlength="4" />
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="form-group mb-0">
                                <label class="col-sm-10 control-label hidden-xs"><strong>{LANG.max_requests_300}</strong></label>
                                <div class="col-sm-14">
                                    <input type="text" value="{MAX_REQUESTS_300}" name="max_requests_300" class="required form-control number text-center w100" maxlength="6" />
                                </div>
                            </div>
                        </li>
                    </ul>
                    <div class="text-center">
                        <input type="hidden" name="selectedtab" value="{SELECTEDTAB}" />
                        <input type="hidden" name="checkss" value="{CHECKSS}" />
                        <input type="hidden" name="floodsave" value="1" />
                        <button type="submit" class="btn btn-primary">{LANG.submit}</button>
                    </div>
                </form>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>{LANG.noflood_ip_list}</strong>
                    </div>
                    <ul class="list-group list" id="noflips" data-url="{ADD_FLOODIP_URL}" data-del-url="{DEL_FLOODIP_URL}" data-confirm="{LANG.ip_delete_confirm}" data-checkss="{CHECKSS}"></ul>
                </div>
            </div>

            <!-- Cấu hình hiển thị captcha -->
            <div role="tabpanel" class="tab-pane{TAB2_ACTIVE}" id="settingCaptcha">
                <div class="panel-group" id="accordion-settingCaptcha" role="tablist" aria-multiselectable="true">
                    <div class="panel panel-primary">
                        <a role="tab" id="settingCaptcha-headingOne" class="panel-heading" style="display: block;text-decoration:none" data-toggle="collapse" data-parent="#accordion-settingCaptcha" href="#settingCaptcha-collapseOne" aria-expanded="true" aria-controls="settingCaptcha-collapseOne">
                            <strong>{LANG.captcha}</strong>
                        </a>
                        <div id="settingCaptcha-collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="settingCaptcha-headingOne">
                            <form action="{FORM_ACTION}" method="post" class="form-horizontal ajax-submit" id="captcha-general-settings">
                                <ul class="list-group type2n1 mb-0">
                                    <li class="list-group-item">
                                        <div class="form-group mb-0">
                                            <label class="col-sm-10 control-label"><strong>{LANG.captcha_num}</strong></label>
                                            <div class="col-sm-14">
                                                <select name="nv_gfx_num" class="form-control" style="width:fit-content">
                                                    <!-- BEGIN: nv_gfx_num -->
                                                    <option value="{OPTION.value}" {OPTION.select}>{OPTION.text}</option>
                                                    <!-- END: nv_gfx_num -->
                                                </select>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="list-group-item">
                                        <div class="form-group mb-0">
                                            <label class="col-sm-10 control-label"><strong>{LANG.captcha_size}</strong></label>
                                            <div class="col-sm-14">
                                                <input class="form-control w50 number pull-left" type="text" value="{NV_GFX_WIDTH}" name="nv_gfx_width" maxlength="3" /> <span class="text-middle pull-left">&nbsp;x&nbsp;</span> <input class="form-control w50 number pull-left" type="text" value="{NV_GFX_HEIGHT}" name="nv_gfx_height" maxlength="3" />
                                            </div>
                                        </div>
                                    </li>

                                    <li class="list-group-item">
                                        <div class="form-group mb-0">
                                            <label class="col-sm-10 control-label"><strong>{LANG.recaptcha_ver}</strong></label>
                                            <div class="col-sm-14">
                                                <select name="recaptcha_ver" class="form-control" style="width:fit-content">
                                                    <!-- BEGIN: recaptcha_ver -->
                                                    <option value="{OPTION.value}" {OPTION.select}>{OPTION.value}</option>
                                                    <!-- END: recaptcha_ver -->
                                                </select>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="list-group-item">
                                        <div class="form-group mb-0">
                                            <label class="col-sm-10 control-label"><strong>{LANG.recaptcha_sitekey}</strong></label>
                                            <div class="col-sm-14">
                                                <div class="input-group">
                                                    <input class="form-control" type="text" value="{RECAPTCHA_SITEKEY}" name="recaptcha_sitekey" maxlength="200" />
                                                    <span class="input-group-btn">
                                                        <a href="https://www.google.com/recaptcha/admin" target="_blank" data-toggle="tooltip" title="{LANG.recaptcha_guide}" class="btn btn-default"><i class="fa fa-info-circle"></i></a>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="list-group-item">
                                        <div class="form-group mb-0">
                                            <label class="col-sm-10 control-label"><strong>{LANG.recaptcha_secretkey}</strong></label>
                                            <div class="col-sm-14">
                                                <div class="input-group">
                                                    <input class="form-control" type="text" value="{RECAPTCHA_SECRETKEY}" name="recaptcha_secretkey" maxlength="200" />
                                                    <span class="input-group-btn">
                                                        <a href="https://www.google.com/recaptcha/admin" target="_blank" data-toggle="tooltip" title="{LANG.recaptcha_guide}" class="btn btn-default"><i class="fa fa-info-circle"></i></a>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="list-group-item">
                                        <div class="form-group mb-0">
                                            <label class="col-sm-10 control-label"><strong>{LANG.turnstile_sitekey}</strong></label>
                                            <div class="col-sm-14">
                                                <div class="input-group">
                                                    <input class="form-control" type="text" value="{turnstile_SITEKEY}" name="turnstile_sitekey" maxlength="200" />
                                                    <span class="input-group-btn">
                                                        <a href="https://dash.cloudflare.com/" target="_blank" data-toggle="tooltip" title="{LANG.recaptcha_guide}" class="btn btn-default"><i class="fa fa-info-circle"></i></a>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="list-group-item">
                                        <div class="form-group mb-0">
                                            <label class="col-sm-10 control-label"><strong>{LANG.recaptcha_secretkey}</strong></label>
                                            <div class="col-sm-14">
                                                <div class="input-group">
                                                    <input class="form-control" type="text" value="{TURNSTILE_SECRETKEY}" name="turnstile_secretkey" maxlength="200" />
                                                    <span class="input-group-btn">
                                                        <a href="https://dash.cloudflare.com/" target="_blank" data-toggle="tooltip" title="{LANG.recaptcha_guide}" class="btn btn-default"><i class="fa fa-info-circle"></i></a>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="list-group-item">
                                        <div class="form-group mb-0">
                                            <label class="col-sm-10 control-label"><strong>{LANG.recaptcha_type}</strong></label>
                                            <div class="col-sm-14">
                                                <select name="recaptcha_type" class="form-control" style="width: fit-content;">
                                                    <!-- BEGIN: recaptcha_type -->
                                                    <option value="{RECAPTCHA_TYPE.value}" {RECAPTCHA_TYPE.select}>{RECAPTCHA_TYPE.text}</option>
                                                    <!-- END: recaptcha_type -->
                                                </select>
                                                <div class="help-block mb-0">{LANG.recaptcha_type_note}</div>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="list-group-item text-center">
                                        <input type="hidden" name="checkss" value="{CHECKSS}" />
                                        <input type="hidden" name="selectedtab" value="{SELECTEDTAB}" />
                                        <input type="hidden" name="captchasave" value="1" />
                                        <input type="submit" class="btn btn-primary" value="{LANG.submit}" />
                                    </li>
                                </ul>
                            </form>
                        </div>
                    </div>
                    <div class="panel panel-primary">
                        <a role="tab" id="settingCaptcha-headingTwo" class="panel-heading text-center collapsed" style="display: block;text-decoration:none" data-toggle="collapse" data-parent="#accordion-settingCaptcha" href="#settingCaptcha-collapseTwo" aria-expanded="false" aria-controls="settingCaptcha-collapseTwo">
                            <strong>{LANG.captcha_for_module}</strong>
                        </a>
                        <div id="settingCaptcha-collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="settingCaptcha-headingTwo">
                            <form action="{FORM_ACTION}" method="post" class="form-horizontal ajax-submit" id="modcapt-settings">
                                <ul class="list-group type2n1 mb-0">
                                    <!-- BEGIN: mod -->
                                    <li class="list-group-item">
                                        <div class="form-group mb-0">
                                            <label class="col-xs-10 control-label"><strong>{MOD.custom_title}</strong></label>
                                            <div class="col-xs-14">
                                                <select name="captcha_type[{MOD.title}]" class="form-control" style="width: fit-content;">
                                                    <!-- BEGIN: opt -->
                                                    <option value="{OPT.val}" {OPT.sel}>{OPT.title}</option>
                                                    <!-- END: opt -->
                                                </select>
                                                <div class="small text-danger" style="margin-top:5px;<!-- BEGIN: dnone -->display:none<!-- END: dnone -->">{LANG.captcha_type_recaptcha_note}</div>
                                                <div class="small text-danger" style="margin-top:5px;<!-- BEGIN: dnone_tt -->display:none<!-- END: dnone_tt -->">{LANG.captcha_type_turnstile_note}</div>
                                            </div>
                                        </div>
                                    </li>
                                    <!-- END: mod -->

                                    <li class="list-group-item text-center">
                                        <div class="m-bottom">{LANG.select_all_as}:</div>
                                        <button type="button" class="btn btn-xs btn-default" data-toggle="selAllAs" data-type="">{LANG.captcha_}</button>
                                        <button type="button" class="btn btn-xs btn-default" data-toggle="selAllAs" data-type="captcha">{LANG.captcha_captcha}</button>
                                        <button type="button" class="btn btn-xs btn-default" data-toggle="selAllAs" data-type="recaptcha">{LANG.captcha_recaptcha}</button>
                                        <button type="button" class="btn btn-xs btn-default" data-toggle="selAllAs" data-type="turnstile">{LANG.captcha_turnstile}</button>
                                    </li>

                                    <li class="list-group-item text-center">
                                        <input type="hidden" name="checkss" value="{CHECKSS}" />
                                        <input type="hidden" name="modcapt" value="1" />
                                        <input type="hidden" name="selectedtab" value="{SELECTEDTAB}" />
                                        <input type="submit" class="btn btn-primary" value="{LANG.submit}" />
                                    </li>
                                </ul>
                            </form>
                        </div>
                    </div>
                    <div class="panel panel-primary">
                        <a role="tab" id="settingCaptcha-headingThree" class="panel-heading text-center collapsed" style="display: block;text-decoration:none" data-toggle="collapse" data-parent="#accordion-settingCaptcha" href="#settingCaptcha-collapseThree" aria-expanded="false" aria-controls="settingCaptcha-collapseThree">
                            <strong>{LANG.captcha_area}</strong>
                        </a>
                        <div id="settingCaptcha-collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="settingCaptcha-headingThree">
                            <form action="{FORM_ACTION}" method="post" class="form-horizontal ajax-submit" id="captarea-settings">
                                <ul class="list-group mb-0">
                                    <li class="list-group-item">
                                        <div class="d-flex justify-content-center">
                                            <div>
                                                <!-- BEGIN: captcha_area -->
                                                <p>
                                                    <label class="checkbox-inline">
                                                        <input type="checkbox" class="form-control" name="captcha_area[]" value="{CAPTCHAAREA.key}" {CAPTCHAAREA.checked} /> {CAPTCHAAREA.title}
                                                    </label>
                                                </p>
                                                <!-- END: captcha_area -->
                                            </div>
                                        </div>
                                    </li>

                                    <li class="list-group-item text-center">
                                        <input type="hidden" name="checkss" value="{CHECKSS}" />
                                        <input type="hidden" name="captarea" value="1" />
                                        <input type="hidden" name="selectedtab" value="{SELECTEDTAB}" />
                                        <input type="submit" class="btn btn-primary" value="{LANG.submit}" />
                                    </li>
                                </ul>
                            </form>
                        </div>
                    </div>
                    <div class="panel panel-primary">
                        <a role="tab" id="settingCaptcha-headingFourth" class="panel-heading text-center collapsed" style="display: block;text-decoration:none" data-toggle="collapse" data-parent="#accordion-settingCaptcha" href="#settingCaptcha-collapseFourth" aria-expanded="false" aria-controls="settingCaptcha-collapseFourth">
                            <strong>{LANG.captcha_comm}</strong>
                        </a>
                        <div id="settingCaptcha-collapseFourth" class="panel-collapse collapse" role="tabpanel" aria-labelledby="settingCaptcha-headingFourth">
                            <form action="{FORM_ACTION}" method="post" class="form-horizontal ajax-submit" id="captcommarea-settings">
                                <ul class="list-group type2n1 mb-0">
                                    <!-- BEGIN: modcomm -->
                                    <li class="list-group-item">
                                        <div class="form-group mb-0">
                                            <label class="col-sm-10 control-label"><strong>{MOD.custom_title}</strong></label>
                                            <div class="col-sm-14">
                                                <select name="captcha_area_comm[{MOD.title}]" class="form-control">
                                                    <!-- BEGIN: opt -->
                                                    <option value="{OPT.val}" {OPT.sel}>{OPT.title}</option>
                                                    <!-- END: opt -->
                                                </select>
                                            </div>
                                        </div>
                                    </li>
                                    <!-- END: modcomm -->

                                    <li class="list-group-item text-center">
                                        <div class="m-bottom">{LANG.select_all_as}:</div>
                                        <select class="form-control d-inline-block" data-toggle="selAllCaptComm" style="width:fit-content">
                                            <option value="-1">{LANG.captcha_comm_select}</option>
                                            <!-- BEGIN: optAll -->
                                            <option value="{OPTALL.val}">{OPTALL.title}</option>
                                            <!-- END: optAll -->
                                        </select>
                                    </li>

                                    <li class="list-group-item text-center">
                                        <input type="hidden" name="checkss" value="{CHECKSS}" />
                                        <input type="hidden" name="captcommarea" value="1" />
                                        <input type="hidden" name="selectedtab" value="{SELECTEDTAB}" />
                                        <input type="submit" class="btn btn-primary" value="{LANG.submit}" />
                                    </li>
                                </ul>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quản lý IP cấm -->
            <div role="tabpanel" class="tab-pane{TAB3_ACTIVE}" id="settingIp">
                <ul class="list-group">
                    <li class="list-group-item active">
                        <strong>{LANG.banip}</strong>
                    </li>
                    <ul class="list-group list" id="banips" data-url="{ADD_BANIP_URL}" data-del-url="{DEL_BANIP_URL}" data-confirm="{LANG.ip_delete_confirm}" data-checkss="{CHECKSS}"></ul>
                </ul>
            </div>

            <!-- Thiết lập Cross-Site -->
            <div role="tabpanel" class="tab-pane{TAB4_ACTIVE}" id="settingCORS">
                <form action="{FORM_ACTION}" method="post" class="form-horizontal ajax-submit" id="cors-settings">
                    <ul class="list-group type2n1 mb-0">
                        <li class="list-group-item active">
                            <strong>{LANG.cors}</strong>
                        </li>

                        <li class="list-group-item item">
                            <label class="checkbox-inline">
                                <input class="form-control" type="checkbox" value="1" name="crosssite_restrict" {CONFIG_CROSS.crosssite_restrict}> <strong>{LANG.cors_site_restrict}</strong>
                            </label>
                            <div class="help-block">{LANG.cors_site_restrict_help}</div>
                            <div class="collapse{CONFIG_CROSS.crosssite_options}">
                                <label class="m-bottom"><i class="fa fa-star"></i> <strong>{LANG.cors_exceptions}:</strong></label>
                                <div class="form-group">
                                    <label class="col-sm-10 control-label"><strong>{LANG.cors_site_valid_domains}</strong></label>
                                    <div class="col-sm-14">
                                        <textarea rows="3" class="form-control" name="crosssite_valid_domains">{CONFIG_CROSS.crosssite_valid_domains}</textarea>
                                        <div class="help-block">{LANG.cors_valid_domains_help}</div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-10 control-label"><strong>{LANG.cors_site_valid_ips}</strong></label>
                                    <div class="col-sm-14">
                                        <textarea rows="3" class="form-control" name="crosssite_valid_ips">{CONFIG_CROSS.crosssite_valid_ips}</textarea>
                                        <div class="help-block">{LANG.cors_valid_ips_help}</div>
                                    </div>
                                </div>

                                <div class="form-group mb-0">
                                    <label class="col-sm-10 control-label"><strong>{LANG.cors_site_allowed_variables}</strong></label>
                                    <div class="col-sm-14">
                                        <textarea rows="3" class="form-control" name="crosssite_allowed_variables">{CONFIG_CROSS.crosssite_allowed_variables}</textarea>
                                        <div class="help-block">{LANG.cors_site_allowed_variables_note}</div>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item item">
                            <label class="checkbox-inline">
                                <input class="form-control" type="checkbox" value="1" name="crossadmin_restrict" {CONFIG_CROSS.crossadmin_restrict}> <strong>{LANG.cors_admin_restrict}</strong>
                            </label>
                            <div class="help-block">{LANG.cors_admin_restrict_help}</div>
                            <div class="collapse{CONFIG_CROSS.crossadmin_options}">
                                <label><i class="fa fa-star"></i> <strong>{LANG.cors_exceptions}:</strong></label>
                                <div class="form-group">
                                    <label class="col-sm-10 control-label"><strong>{LANG.cors_admin_valid_domains}</strong></label>
                                    <div class="col-sm-14">
                                        <textarea rows="3" class="form-control" name="crossadmin_valid_domains">{CONFIG_CROSS.crossadmin_valid_domains}</textarea>
                                        <div class="help-block">{LANG.cors_valid_domains_help}</div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-10 control-label"><strong>{LANG.cors_admin_valid_ips}</strong></label>
                                    <div class="col-sm-14">
                                        <textarea rows="3" class="form-control" name="crossadmin_valid_ips">{CONFIG_CROSS.crossadmin_valid_ips}</textarea>
                                        <div class="help-block">{LANG.cors_valid_ips_help}</div>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="form-group mb-0">
                                <label class="col-sm-10 control-label hidden-xs"><strong>{LANG.allow_null_origin}</strong></label>
                                <div class="ccol-sm-14">
                                    <label class="checkbox-inline">
                                        <input type="checkbox" class="form-control" value="1" name="allow_null_origin" {CONFIG_CROSS.allow_null_origin}>
                                        <strong class="visible-xs-inline-block">{LANG.allow_null_origin}</strong>
                                    </label>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="form-group mb-0">
                                <label class="col-sm-10 control-label"><strong>{LANG.ip_allow_null_origin}</strong></label>
                                <div class="col-sm-14">
                                    <textarea rows="3" class="form-control" name="ip_allow_null_origin">{CONFIG_CROSS.ip_allow_null_origin}</textarea>
                                    <div class="help-block mb-0">{LANG.ip_allow_null_origin_help}</div>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="form-group mb-0">
                                <label class="col-sm-10 control-label hidden-xs"><strong>{LANG.auto_acao}</strong></label>
                                <div class="col-sm-14">
                                    <label class="checkbox-inline">
                                        <input type="checkbox" class="form-control" value="1" name="auto_acao" {CONFIG_CROSS.auto_acao}>
                                        <strong class="visible-xs-inline-block">{LANG.auto_acao}</strong>
                                    </label>
                                    <div class="help-block">{LANG.auto_acao_note}</div>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="form-group mb-0">
                                <label class="col-sm-10 control-label"><strong>{LANG.load_files_seccode}</strong></label>
                                <div class="col-sm-14">
                                    <div class="input-group">
                                        <input type="text" name="load_files_seccode" id="load_files_seccode" value="{CONFIG_CROSS.load_files_seccode}" class="form-control bg-white" readonly="readonly">
                                        <div class="input-group-btn">
                                            <button class="btn btn-default" type="button" data-clipboard-target="#load_files_seccode" data-toggle="clipboard" data-title="{LANG.value_copied}" data-placement="left" data-container="body" data-trigger="manual" data-animation="false"><i class="fa fa-copy"></i></button>
                                            <button class="btn btn-default" type="button" data-target="#load_files_seccode" data-toggle="seccode_create"><i class="fa fa-retweet"></i></button>
                                            <button class="btn btn-default" type="button" data-target="#load_files_seccode" data-toggle="seccode_remove"><i class="fa fa-trash-o"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item text-center">
                            <input type="hidden" name="selectedtab" value="{SELECTEDTAB}" />
                            <input type="hidden" name="checkss" value="{CHECKSS}" />
                            <input type="hidden" name="corssave" value="1" />
                            <input type="submit" value="{GLANG.submit}" class="btn btn-primary" />
                        </li>
                    </ul>
                </form>
            </div>
            <!-- END: sys_contents -->

            <!-- Thiết lập CSP -->
            <div role="tabpanel" class="tab-pane{TAB5_ACTIVE}" id="settingCSP">
                <form action="{FORM_ACTION}" method="post" class="form-horizontal ajax-submit" id="csp-settings" data-confirm="{LANG.csp_source_none_confirm}">
                    <ul class="list-group">
                        <li class="list-group-item active">
                            <strong>{LANG.csp}</strong>
                        </li>

                        <li class="list-group-item">
                            <p class="help-block">{LANG.csp_desc} <a href="https://content-security-policy.com/" target="_blank">{LANG.csp_details}</a></p>
                            <label class="checkbox-inline">
                                <input type="checkbox" class="form-control" value="1" name="nv_csp_act" data-target="#csp_options" {CSP_ACT} /> <strong>{LANG.csp_act}</strong>
                            </label>
                        </li>
                    </ul>
                    <div class="panel-group collapse{CSP_OPTIONS}" id="csp_options" role="tablist">
                        <!-- BEGIN: csp_directive -->
                        <div class="panel panel-default directive">
                            <a class="panel-heading" style="display: block;text-decoration:none" role="tab" id="heading-{DIRECTIVE.name}" data-toggle="collapse" href="#collapse-{DIRECTIVE.name}" aria-expanded="true" aria-controls="collapse-{DIRECTIVE.name}">
                                <strong>{DIRECTIVE.name}</strong>
                            </a>
                            <div id="collapse-{DIRECTIVE.name}" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-{DIRECTIVE.name}">
                                <div class="panel-body">
                                    <div class="help-block" style="margin-top:0">{DIRECTIVE.desc}</div>
                                    <div class="m-bottom">
                                        <p><strong>{LANG.csp_source_value}</strong></p>
                                        <!-- BEGIN: checkbox -->
                                        <span style="margin-right:15px">
                                            <label>
                                                <input type="checkbox" class="form-control" name="directives[{DIRECTIVE.name}][{SOURCE.key}]" value="1" data-toggle="{SOURCE.key}" {SOURCE.checked}{SOURCE.disabled}> {SOURCE.key}
                                            </label>
                                            <a href="#" data-toggle="popover" data-placement="auto" data-trigger="focus" data-content="{SOURCE.name}"><em class="fa fa-question-circle-o fa-pointer"></em></a>
                                        </span>
                                        <!-- END: checkbox -->
                                    </div>
                                    <!-- BEGIN: input -->
                                    <div class="form-group mb-0">
                                        <p><strong>{SOURCE.name}:</strong> <a href="#" data-toggle="popover" data-placement="auto" data-trigger="focus" data-content="{LANG.csp_source_hosts_note}"><em class="fa fa-question-circle-o fa-pointer"></em></a></p>
                                        <textarea type="text" class="form-control" name="directives[{DIRECTIVE.name}][{SOURCE.key}]" {SOURCE.disabled}>{SOURCE.val}</textarea>
                                        <div class="help-block mb-0">{LANG.csp_source_hosts_help}</div>
                                    </div>
                                    <!-- END: input -->
                                    <!-- BEGIN: csp_script_nonce -->
                                    <div style="margin-top:15px">
                                        <label>
                                            <input type="checkbox" class="form-control" value="1" name="nv_csp_script_nonce" {CSP_SCRIPT_NONCE} /> <strong>{LANG.csp_script_nonce}</strong>
                                        </label>
                                        <a href="#" data-toggle="popover" data-placement="auto" data-trigger="focus" data-content="{LANG.csp_script_nonce_note}"><em class="fa fa-question-circle-o fa-pointer"></em></a>
                                    </div>
                                    <!-- END: csp_script_nonce -->
                                </div>
                            </div>
                        </div>
                        <!-- END: csp_directive -->
                    </div>
                    <div class="text-center">
                        <input type="hidden" name="selectedtab" value="{SELECTEDTAB}" />
                        <input type="hidden" name="checkss" value="{CHECKSS}" />
                        <input type="hidden" name="cspsave" value="1" />
                        <input type="submit" value="{GLANG.submit}" class="btn btn-primary" />
                    </div>
                </form>
            </div>

            <!-- Thiết lập RP -->
            <div role="tabpanel" class="tab-pane{TAB6_ACTIVE}" id="settingRP">
                <form action="{FORM_ACTION}" method="post" class="form-horizontal ajax-submit" id="rp-settings">
                    <ul class="list-group">
                        <li class="list-group-item active">
                            <strong>{LANG.rp}</strong>
                        </li>

                        <li class="list-group-item">
                            <p clas="help-block">{LANG.rp_desc} <a href="https://www.w3.org/TR/referrer-policy/" target="_blank">{LANG.rp_details}</a></p>
                            <p><button type="button" class="btn btn-default btn-xs active" data-toggle="collapse" data-target="#rp_directives_help" aria-expanded="false" aria-controls="rp_directives_help"><em class="fa fa-arrow-right"></em> {LANG.rp_directives_help}</button></p>
                            <div class="form-group">
                                <label class="checkbox-inline">
                                    <input type="checkbox" class="form-control" value="1" name="nv_rp_act" data-target="#rp_options" {RP_ACT} /> <strong>{LANG.rp_act}</strong>
                                </label>
                            </div>
                            <div class="collapse{RP_OPTIONS}" id="rp_options">
                                <div class="form-group mb-0">
                                    <label><strong>{LANG.rp_directives}</strong></label>
                                    <input class="form-control" type="text" value="{RP}" name="nv_rp" maxlength="500" />
                                    <div class="help-block">{LANG.rp_note}</div>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <div class="text-center">
                        <input type="hidden" name="checkss" value="{CHECKSS}" />
                        <input type="hidden" name="selectedtab" value="{SELECTEDTAB}" />
                        <input type="hidden" name="rpsave" value="1" />
                        <input type="submit" value="{GLANG.submit}" class="btn btn-primary" />
                    </div>

                    <div class="collapse" id="rp_directives_help" style="margin-top:50px">
                        <h2><strong>{LANG.rp_directives_help}</strong></h2>
                        <p>{LANG.rp_desc2}</p>
                        <p><strong>{LANG.rp_directives}:</strong></p>
                        <ul class="list-group">
                            <!-- BEGIN: rp_directive -->
                            <li class="list-group-item">
                                <code><strong>{RP_DIRECTIVE.name}</strong></code>: {RP_DIRECTIVE.desc}
                            </li>
                            <!-- END: rp_directive -->
                        </ul>
                    </div>
                </form>
            </div>

            <!-- Thiết lập PP -->
            <div role="tabpanel" class="tab-pane{TAB7_ACTIVE}" id="settingPP">
                <form action="{FORM_ACTION}" method="post" class="form-horizontal ajax-submit" id="pp-settings" data-cfnone="{LANG.csp_source_none_confirm}">
                    <ul class="list-group">
                        <li class="list-group-item active">
                            <strong>{LANG.pp}</strong>
                        </li>

                        <li class="list-group-item">
                            <p class="help-block">{LANG.pp_desc} <a href="https://www.w3.org/TR/permissions-policy/" target="_blank">{LANG.csp_details}</a>.</p>
                            <p class="help-block">{LANG.pp_desc2}.</p>
                            <p class="help-block">{LANG.pp_desc3}.</p>
                            <label class="checkbox-inline">
                                <input type="checkbox" class="form-control" value="1" name="nv_pp_act" data-target="#pp_options" data-toggle="pp_act" {PP_ACT}> <strong>{LANG.pp_act}</strong>
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" class="form-control" value="1" name="nv_fp_act" data-target="#pp_options" data-toggle="pp_act"{FP_ACT}> <strong>{LANG.fp_act}</strong>
                            </label>
                        </li>
                    </ul>
                    <div class="panel-group collapse{PP_OPTIONS}" id="pp_options" role="tablist">
                        <!-- BEGIN: pp_directive -->
                        <div class="panel panel-default directive">
                            <a class="panel-heading" style="display: block;text-decoration:none" role="tab" id="heading-pp-{DIRECTIVE.name}" data-toggle="collapse" href="#collapse-pp-{DIRECTIVE.name}" aria-expanded="true" aria-controls="collapse-pp-{DIRECTIVE.name}">
                                <strong>{DIRECTIVE.name}</strong>
                            </a>
                            <div id="collapse-pp-{DIRECTIVE.name}" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-pp-{DIRECTIVE.name}">
                                <div class="panel-body">
                                    <div class="help-block" style="margin-top:0">{DIRECTIVE.desc}</div>
                                    <div class="m-bottom">
                                        <p><strong>{LANG.csp_source_value}</strong></p>
                                        <!-- BEGIN: checkbox -->
                                        <span style="margin-right:15px">
                                            <label>
                                                <input type="checkbox" class="form-control" name="directives[{DIRECTIVE.name}][{SOURCE.key}]" value="1" data-toggle="{SOURCE.key}" {SOURCE.checked}{SOURCE.disabled}> {SOURCE.key}
                                            </label>
                                            <a href="#" data-toggle="popover" data-placement="auto" data-trigger="focus" data-content="{SOURCE.name}"><em class="fa fa-question-circle-o fa-pointer"></em></a>
                                        </span>
                                        <!-- END: checkbox -->
                                    </div>
                                    <!-- BEGIN: input -->
                                    <div class="form-group mb-0">
                                        <p><strong>{SOURCE.name}:</strong> <a href="#" data-toggle="popover" data-placement="auto" data-trigger="focus" data-content="{LANG.pp_source_hosts_note}"><em class="fa fa-question-circle-o fa-pointer"></em></a></p>
                                        <textarea rows="{SOURCE.rows}" type="text" class="form-control" name="directives[{DIRECTIVE.name}][{SOURCE.key}]" {SOURCE.disabled}>{SOURCE.val}</textarea>
                                        <div class="help-block mb-0">{LANG.csp_source_hosts_help}</div>
                                    </div>
                                    <!-- END: input -->
                                </div>
                            </div>
                        </div>
                        <!-- END: pp_directive -->
                    </div>
                    <div class="text-center">
                        <input type="hidden" name="selectedtab" value="{SELECTEDTAB}" />
                        <input type="hidden" name="checkss" value="{CHECKSS}" />
                        <input type="hidden" name="ppsave" value="1" />
                        <input type="submit" value="{GLANG.submit}" class="btn btn-primary" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<input type="hidden" name="gselectedtab" value="{SELECTEDTAB}" />

<div id="page-tool" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>
<!-- END: main -->

<!-- BEGIN: iplist -->
<!-- Block hiển thị danh sách IP -->
<!-- BEGIN: loop -->
<li class="list-group-item{ROW.class}">
    <div title="{ROW.status}"><em class="fa {ROW.status_icon}"></em> <strong>{ROW.ip}</strong></div>
    <p class="small">({ROW.note})</p>
    <div>
        <button class="btn btn-default btn-sm" title="{LANG.ip_edit}" data-toggle="edit_ip" data-id="{ROW.id}"><em class="fa fa-edit fa-fw"></em> {GLANG.edit}</button>
        <button class="btn btn-default btn-sm" title="{LANG.ip_delete}" data-toggle="del_ip" data-id="{ROW.id}"><em class="fa fa-trash-o fa-fw"></em> {GLANG.delete}</button>
    </div>
</li>
<!-- END: loop -->
<li class="list-group-item text-center">
    <button type="button" class="btn btn-primary" data-toggle="add_ip" title="{LANG.ip_add}">{LANG.ip_add}</button>
</li>
<!-- END: iplist -->

<!-- BEGIN: ip_action -->
<!-- Block thêm/sửa IP -->
<form action="{FORM_ACTION}" method="post" class="form-horizontal ip-action">
    <ul class="list-group type2n1 mb-0">
        <li class="list-group-item">
            <div class="form-group mb-0">
                <label class="col-sm-6 control-label"><strong>{LANG.ip_version}</strong></label>
                <div class="col-sm-18">
                    <select class="form-control ip-version" name="version" style="width:fit-content">
                        <!-- BEGIN: version -->
                        <option value="{IP_VERSION.key}" {IP_VERSION.sel}>{IP_VERSION.title}</option>
                        <!-- END: version -->
                    </select>
                </div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="form-group mb-0">
                <label class="col-sm-6 control-label"><strong>{LANG.ip_address}</strong></label>
                <div class="col-sm-18">
                    <input class="form-control required" type="text" name="ip" value="{DATA.ip}">
                </div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="form-group mb-0">
                <label class="col-sm-6 control-label"><strong>{LANG.ip_mask}</strong></label>
                <div class="col-sm-18">
                    <select class="form-control ip-mask" name="mask" style="width:fit-content">
                        <!-- BEGIN: mask -->
                        <option value="{MASK.val}" data-version="{MASK.ver}" {MASK.selected}{MASK.disabled}>{MASK.title}</option>
                        <!-- END: mask -->
                    </select>
                </div>
            </div>
        </li>
        <!-- BEGIN: is_area -->
        <li class="list-group-item">
            <div class="form-group mb-0">
                <label class="col-sm-6 control-label"><strong>{LANG.banip_area}</strong></label>
                <div class="col-sm-18">
                    <div class="form-inline">
                        <select name="area" class="form-control">
                            <!-- BEGIN: area -->
                            <option value="{AREA.val}" {AREA.sel}>{AREA.title}</option>
                            <!-- END: area -->
                        </select>
                    </div>
                </div>
            </div>
        </li>
        <!-- END: is_area -->
        <li class="list-group-item">
            <div class="form-group mb-0">
                <label class="col-sm-6 control-label"><strong>{LANG.start_time}</strong></label>
                <div class="col-sm-18">
                    <div class="d-flex" style="width:fit-content">
                        <input type="text" name="begintime" class="w100 datepicker form-control" value="{DATA.begintime}">
                        <select name="beginhour" class="form-control m-left">
                            <!-- BEGIN: beginhour -->
                            <option value="{BEGIN_HOUR.val}" {BEGIN_HOUR.sel}>{BEGIN_HOUR.title}</option>
                            <!-- END: beginhour -->
                        </select>
                        <select name="beginmin" class="form-control m-left">
                            <!-- BEGIN: beginmin -->
                            <option value="{BEGIN_MIN.val}" {BEGIN_MIN.sel}>{BEGIN_MIN.title}</option>
                            <!-- END: beginmin -->
                        </select>
                    </div>
                </div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="form-group mb-0">
                <label class="col-sm-6 control-label"><strong>{LANG.end_time}</strong></label>
                <div class="col-sm-18">
                    <div class="d-flex" style="width:fit-content">
                        <input type="text" name="endtime" class="w100 datepicker form-control" value="{DATA.endtime}">
                        <select name="endhour" class="form-control m-left">
                            <!-- BEGIN: endhour -->
                            <option value="{END_HOUR.val}" {END_HOUR.sel}>{END_HOUR.title}</option>
                            <!-- END: endhour -->
                        </select>
                        <select name="endmin" class="form-control m-left">
                            <!-- BEGIN: endmin -->
                            <option value="{END_MIN.val}" {END_MIN.sel}>{END_MIN.title}</option>
                            <!-- END: endmin -->
                        </select>
                    </div>
                </div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="form-group mb-0">
                <label class="col-sm-6 control-label"><strong>{LANG.notice}</strong></label>
                <div class="col-sm-18">
                    <textarea class="form-control" name="notice" maxlength="255" style="height:60px">{DATA.notice}</textarea>
                </div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="text-center">
                <input type="hidden" name="checkss" value="{CHECKSS}" />
                <input type="hidden" name="save" value="1" />
                <input type="submit" value="{GLANG.submit}" class="btn btn-primary">
                <button type="button" class="btn btn-default" data-dismiss="modal">{GLANG.close}</button>
            </div>
        </li>
    </ul>
</form>
<script>
    $(function() {
        $(".datepicker").datepicker({
            showOn: "focus",
            dateFormat: "dd/mm/yy",
            changeMonth: true,
            changeYear: true,
            showOtherMonths: true
        });
    })
</script>
<!-- END: ip_action -->
