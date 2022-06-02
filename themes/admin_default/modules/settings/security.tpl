<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>

<!-- BEGIN: error_save -->
<div class="alert alert-danger">{ERROR_SAVE}</div>
<!-- END: error_save -->

<!-- BEGIN: manual_save -->
<div class="alert alert-danger">{MESSAGE}</div>
<pre><code>{CODE}</code></pre>
<!-- END: manual_save -->

<div class="clearfix">
    <ul class="nav nav-tabs setting-tabnav" role="tablist" id="settingTabs">
        <!-- BEGIN: sys_tabs -->
        <li role="presentation" class="{TAB0_ACTIVE}"><a href="#settingBasic" aria-controls="settingBasic" aria-offsets="0" role="tab" data-toggle="tab" data-location="{FORM_ACTION}&amp;selectedtab=0">{LANG.security}</a></li>
        <li role="presentation" class="{TAB1_ACTIVE}"><a href="#settingFlood" aria-controls="settingFlood" aria-offsets="1" role="tab" data-toggle="tab" data-location="{FORM_ACTION}&amp;selectedtab=1">{LANG.flood_blocker}</a></li>
        <li role="presentation" class="{TAB2_ACTIVE}"><a href="#settingCaptcha" aria-controls="settingCaptcha" aria-offsets="2" role="tab" data-toggle="tab" data-location="{FORM_ACTION}&amp;selectedtab=2">{LANG.captcha}</a></li>
        <li role="presentation" class="{TAB3_ACTIVE}"><a href="#settingIp" aria-controls="settingIp" aria-offsets="3" role="tab" data-toggle="tab" data-location="{FORM_ACTION}&amp;selectedtab=3">{LANG.banip}</a></li>
        <li role="presentation" class="{TAB4_ACTIVE}"><a href="#settingCORS" aria-controls="settingCORS" aria-offsets="4" role="tab" data-toggle="tab" data-location="{FORM_ACTION}&amp;selectedtab=4">{LANG.cors}</a></li>
        <!-- END: sys_tabs -->
        <li role="presentation" class="{TAB5_ACTIVE}"><a href="#settingCSP" aria-controls="settingCSP" aria-offsets="5" role="tab" data-toggle="tab" data-location="{FORM_ACTION}&amp;selectedtab=5">{LANG.csp}</a></li>
        <li role="presentation" class="{TAB6_ACTIVE}"><a href="#settingRP" aria-controls="settingRP" aria-offsets="6" role="tab" data-toggle="tab" data-location="{FORM_ACTION}&amp;selectedtab=6">{LANG.rp}</a></li>
    </ul>
    <div class="tab-content">
        <!-- BEGIN: sys_contents -->
        <div role="tabpanel" class="tab-pane{TAB0_ACTIVE}" id="settingBasic">
            <div class="setting-tabcontent clearfix">
                <form action="{FORM_ACTION}" method="post">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover table-first">
                            <colgroup>
                                <col style="width: 40%" />
                                <col style="width: 60%" />
                            </colgroup>
                            <tbody>
                                <tr>
                                    <td><strong>{LANG.is_login_blocker}</strong></td>
                                    <td><input type="checkbox" value="1" name="is_login_blocker" {IS_LOGIN_BLOCKER} /></td>
                                </tr>
                                <tr>
                                    <td><strong>{LANG.login_number_tracking}</strong></td>
                                    <td><input type="text" value="{LOGIN_NUMBER_TRACKING}" name="login_number_tracking" class="required form-control w100"/></td>
                                </tr>
                                <tr>
                                    <td><strong>{LANG.login_time_tracking}</strong> ({GLANG.min})</td>
                                    <td><input type="text" value="{LOGIN_TIME_TRACKING}" name="login_time_tracking" class="required form-control w100"/></td>
                                </tr>
                                <tr>
                                    <td><strong>{LANG.login_time_ban}</strong> ({GLANG.min})</td>
                                    <td><input type="text" value="{LOGIN_TIME_BAN}" name="login_time_ban" class="required form-control w100"/></td>
                                </tr>
                                <tr>
                                    <td><strong>{LANG.two_step_verification}</strong></td>
                                    <td>
                                        <span class="help-block">{LANG.two_step_verification_note}</span>
                                        <select name="two_step_verification" class="form-control w200">
                                            <!-- BEGIN: two_step_verification --><option value="{TWO_STEP_VERIFICATION.key}"{TWO_STEP_VERIFICATION.selected}>{TWO_STEP_VERIFICATION.title}</option><!-- END: two_step_verification -->
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>{LANG.admin_2step_opt}</strong></td>
                                    <td>
                                        <!-- BEGIN: admin_2step_opt -->
                                        <div>
                                            <label><input type="checkbox" name="admin_2step_opt[]" value="{ADMIN_2STEP_OPT.key}"{ADMIN_2STEP_OPT.checked}> {ADMIN_2STEP_OPT.title}</label>
                                            <!-- BEGIN: link_config -->
                                            (<a href="{LINK_CONFIG}" target="_blank">{LANG.admin_2step_appconfig}</a>)
                                            <!-- END: link_config -->
                                        </div>
                                        <!-- END: admin_2step_opt -->
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>{LANG.admin_2step_default}</strong></td>
                                    <td>
                                        <select name="admin_2step_default" class="form-control w200">
                                            <!-- BEGIN: admin_2step_default -->
                                            <option value="{ADMIN_2STEP_DEFAULT.key}"{ADMIN_2STEP_DEFAULT.selected}>{ADMIN_2STEP_DEFAULT.title}</option>
                                            <!-- END: admin_2step_default -->
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>{LANG.nv_anti_agent}</strong></td>
                                    <td><input type="checkbox" value="1" name="nv_anti_agent" {ANTI_AGENT} /></td>
                                </tr>
                                <tr>
                                    <td><strong>{LANG.proxy_blocker}</strong></td>
                                    <td>
                                        <select name="proxy_blocker" class="form-control w200">
                                            <!-- BEGIN: proxy_blocker -->
                                            <option value="{PROXYOP}" {PROXYSELECTED}>{PROXYVALUE} </option>
                                            <!-- END: proxy_blocker -->
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>{LANG.str_referer_blocker}</strong></td>
                                    <td><input type="checkbox" value="1" name="str_referer_blocker" {REFERER_BLOCKER} /></td>
                                </tr>
                                <tr>
                                    <td><strong>{LANG.nv_anti_iframe}</strong></td>
                                    <td><input type="checkbox" value="1" name="nv_anti_iframe" {ANTI_IFRAME} /></td>
                                </tr>
                                <tr>
                                    <td><strong>{LANG.nv_allowed_html_tags}</strong></td>
                                    <td><textarea name="nv_allowed_html_tags" class="form-control" style="height: 100px" class="required">{NV_ALLOWED_HTML_TAGS}</textarea></td>
                                </tr>
                                <tr>
                                    <td><strong>{LANG.domains_restrict}</strong></td>
                                    <td>
                                        <input type="checkbox" name="domains_restrict" value="1"{DOMAINS_RESTRICT}>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>{LANG.domains_whitelist}</strong></td>
                                    <td>
                                        <textarea name="domains_whitelist" class="form-control" rows="5">{DOMAINS_WHITELIST}</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>{LANG.XSSsanitize}</strong></td>
                                    <td>
                                        <div><label><input type="checkbox" name="XSSsanitize" value="1"{XSSSANITIZE}>{LANG.user_XSSsanitize}</label></div>
                                        <div><label><input type="checkbox" name="admin_XSSsanitize" value="1"{ADMIN_XSSSANITIZE}>{LANG.admin_XSSsanitize}<label></div>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2">
                                        <div class="text-center">
                                            <input type="hidden" name="checkss" value="{CHECKSS}" />
                                            <input type="submit" class="btn btn-primary w100" name="submitbasic" value="{LANG.submit}" />
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <input type="hidden" name="selectedtab" value="{SELECTEDTAB}"/>
                </form>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane{TAB1_ACTIVE}" id="settingFlood">
            <div class="setting-tabcontent clearfix">
                <form action="{FORM_ACTION}" method="post">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover table-first">
                            <colgroup>
                                <col style="width: 40%" />
                                <col style="width: 60%" />
                            </colgroup>
                            <tbody>
                                <tr>
                                    <td><strong>{LANG.is_flood_blocker}</strong></td>
                                    <td><input type="checkbox" value="1" name="is_flood_blocker" {IS_FLOOD_BLOCKER} /></td>
                                </tr>
                                <tr>
                                    <td><strong>{LANG.max_requests_60}</strong></td>
                                    <td><input type="text" value="{MAX_REQUESTS_60}" name="max_requests_60" class="required form-control w100"/></td>
                                </tr>
                                <tr>
                                    <td><strong>{LANG.max_requests_300}</strong></td>
                                    <td><input type="text" value="{MAX_REQUESTS_300}" name="max_requests_300" class="required form-control w100"/></td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2">
                                        <div class="text-center">
                                            <input type="hidden" name="checkss" value="{CHECKSS}" />
                                            <input type="submit" class="btn btn-primary w100" name="submitflood" value="{LANG.submit}" />
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <input type="hidden" name="selectedtab" value="{SELECTEDTAB}"/>
                </form>

                <!-- BEGIN: noflips -->
                <div class="panel panel-default">
                    <div class="panel-heading"><strong>{LANG.noflood_ip_list}</strong></div>
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>{LANG.banip_ip}</th>
                                <th>{LANG.banip_mask}</th>
                                <th>{LANG.banip_timeban}</th>
                                <th>{LANG.banip_timeendban}</th>
                                <th class="text-center">{LANG.banip_funcs}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- BEGIN: loop -->
                            <tr>
                                <td>{ROW.dbip}</td>
                                <td>{ROW.dbmask}</td>
                                <td>{ROW.dbbegintime}</td>
                                <td>{ROW.dbendtime}</td>
                                <td class="text-center">
                                    <a class="edit btn btn-default btn-xs" title="{LANG.banip_edit}" href="{ROW.url_edit}"><em class="fa fa-edit fa-fw"></em>{LANG.banip_edit}</a>
                                    <a class="deleteone-ip btn btn-danger btn-xs" title="{LANG.banip_delete}" href="{ROW.url_delete}"><em class="fa fa-trash-o fa-fw"></em>{LANG.banip_delete}</a>
                                </td>
                            </tr>
                            <!-- END: loop -->
                        </tbody>
                    </table>
                </div>
                <!-- END: noflips -->

                <form action="{FORM_ACTION}" method="post">
                    <input type="hidden" name="flid" value="{FLDATA.flid}" />
                    <div class="panel panel-default">
                        <div class="panel-heading"><strong>{NOFLOODIP_TITLE}</strong></div>
                        <div class="panel-body">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="ipt_fip_version" class="col-sm-6 control-label">{LANG.ip_version}:</label>
                                    <div class="col-sm-18 col-md-14 col-lg-10">
                                        <div class="form-inline">
                                            <select class="form-control" name="fip_version" id="ipt_fip_version">
                                                <!-- BEGIN: fip_version -->
                                                <option value="{IP_VERSION.key}"{IP_VERSION.f_selected}>{IP_VERSION.title}</option>
                                                <!-- END: fip_version -->
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="ipt_flip" class="col-sm-6 control-label">{LANG.banip_address} <span style="color:red">(*)</span>:</label>
                                    <div class="col-sm-18 col-md-14 col-lg-10">
                                        <input class="form-control" type="text" id="ipt_flip" name="flip" value="{FLDATA.flip}">
                                    </div>
                                </div>
                                <div class="form-group{CLASS_FIP_VERSION4}" id="ip4_fmask">
                                    <label for="ipt_flmask" class="col-sm-6 control-label">{LANG.banip_mask}:</label>
                                    <div class="col-sm-18 col-md-14 col-lg-10">
                                        <div class="form-inline">
                                            <select class="form-control" name="flmask" id="ipt_flmask">
                                                <option value="0">{MASK_TEXT_ARRAY.0}</option>
                                                <option value="3"{FLDATA.selected3}>{MASK_TEXT_ARRAY.3}</option>
                                                <option value="2"{FLDATA.selected2}>{MASK_TEXT_ARRAY.2}</option>
                                                <option value="1"{FLDATA.selected1}>{MASK_TEXT_ARRAY.1}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group{CLASS_FIP_VERSION6}" id="ip6_fmask">
                                    <label for="ipt_flmaskv6" class="col-sm-6 control-label">{LANG.banip_mask}:</label>
                                    <div class="col-sm-18 col-md-14 col-lg-10">
                                        <div class="form-inline">
                                            <select class="form-control" name="flmask_v6" id="ipt_flmaskv6">
                                                <!-- BEGIN: flmask6 -->
                                                <option value="{IPMASK.key}"{IPMASK.f_selected}>{IPMASK.title}</option>
                                                <!-- END: flmask6 -->
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="ipt_flbegintime" class="col-sm-6 control-label">{LANG.banip_begintime}:</label>
                                    <div class="col-sm-18 col-md-14 col-lg-10">
                                        <div class="form-inline">
                                            <input type="text" id="ipt_flbegintime" name="flbegintime" class="w150 datepicker form-control pull-left" value="{FLDATA.begintime}" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="ipt_flendtime" class="col-sm-6 control-label">{LANG.banip_endtime}:</label>
                                    <div class="col-sm-18 col-md-14 col-lg-10">
                                        <div class="form-inline">
                                            <input type="text" id="ipt_flendtime" name="flendtime" class="w150 datepicker form-control pull-left text" value="{FLDATA.endtime}" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="ipt_flnotice" class="col-sm-6 control-label">{LANG.banip_notice}:</label>
                                    <div class="col-sm-18 col-md-14 col-lg-10">
                                        <textarea rows="5" class="form-control" name="flnotice" id="ipt_flnotice">{FLDATA.notice}</textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-18 col-md-14 col-lg-10 col-sm-offset-6">
                                        <input type="hidden" name="checkss" value="{CHECKSS}" />
                                        <input type="submit" value="{LANG.banip_confirm}" name="submitfloodip" class="btn btn-primary">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="selectedtab" value="{SELECTEDTAB}">
                </form>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane{TAB2_ACTIVE}" id="settingCaptcha">
            <div class="setting-tabcontent clearfix">
                <form action="{FORM_ACTION}" method="post">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover table-first">
                            <colgroup>
                                <col style="width: 40%" />
                                <col style="width: 60%" />
                            </colgroup>
                            <tbody>
                                <tr>
                                    <td><strong>{LANG.captcha_num}</strong></td>
                                    <td>
                                        <select name="nv_gfx_num" class="form-control" style="width:auto">
                                            <!-- BEGIN: nv_gfx_num -->
                                            <option value="{OPTION.value}"{OPTION.select}>{OPTION.text}</option>
                                            <!-- END: nv_gfx_num -->
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>{LANG.captcha_size}</strong></td>
                                    <td><input class="form-control pull-left" style="width:50px;" type="text" value="{NV_GFX_WIDTH}" name="nv_gfx_width" maxlength="3"/> <span class="text-middle pull-left">&nbsp;x&nbsp;</span> <input class="form-control pull-left" style="width:50px;" type="text" value="{NV_GFX_HEIGHT}" name="nv_gfx_height" maxlength="3"/></td>
                                </tr>
                                <tr>
                                    <td><strong>{LANG.recaptcha_ver}</strong></td>
                                    <td>
                                        <select name="recaptcha_ver" class="form-control" style="width:auto">
                                            <!-- BEGIN: recaptcha_ver -->
                                            <option value="{OPTION.value}"{OPTION.select}>{OPTION.value}</option>
                                            <!-- END: recaptcha_ver -->
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>{LANG.recaptcha_sitekey}</strong></td>
                                    <td>
                                        <input class="form-control pull-left w300" type="text" value="{RECAPTCHA_SITEKEY}" name="recaptcha_sitekey" maxlength="200"/>
                                        &nbsp; <a href="https://www.google.com/recaptcha/admin" target="_blank" data-toggle="tooltip" title="{LANG.recaptcha_guide}" class="text-middle"><i class="fa fa-info-circle"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>{LANG.recaptcha_secretkey}</strong></td>
                                    <td>
                                        <input class="form-control pull-left w300" type="text" value="{RECAPTCHA_SECRETKEY}" name="recaptcha_secretkey" maxlength="200"/>
                                        &nbsp; <a href="https://www.google.com/recaptcha/admin" target="_blank" data-toggle="tooltip" title="{LANG.recaptcha_guide}" class="text-middle"><i class="fa fa-info-circle"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>{LANG.recaptcha_type}</strong></td>
                                    <td>
                                        <select name="recaptcha_type" class="form-control w300">
                                            <!-- BEGIN: recaptcha_type -->
                                            <option value="{RECAPTCHA_TYPE.value}"{RECAPTCHA_TYPE.select}>{RECAPTCHA_TYPE.text}</option>
                                            <!-- END: recaptcha_type -->
                                        </select>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2">
                                        <div class="text-center">
                                            <input type="hidden" name="checkss" value="{CHECKSS}" />
                                            <input type="submit" class="btn btn-primary w100" name="submitcaptcha" value="{LANG.submit}" />
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <input type="hidden" name="selectedtab" value="{SELECTEDTAB}"/>
                </form>

                <div class="row">
                    <form action="{FORM_ACTION}" method="post" class="col-sm-12" data-recaptcha-sitekey="{RECAPTCHA_SITEKEY}" data-recaptcha-secretkey="{RECAPTCHA_SECRETKEY}">
                        <div class="panel panel-default">
                            <div class="panel-heading"><strong>{LANG.captcha_for_module}</strong></div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <td colspan="2" class="text-center">
                                                <div class="m-bottom">{LANG.select_all_as}:</div>
                                                <button type="button" class="btn btn-xs btn-default" onclick="selAllAs('', this.form)">{LANG.captcha_}</button>
                                                <button type="button" class="btn btn-xs btn-default" onclick="selAllAs('captcha', this.form)">{LANG.captcha_captcha}</button>
                                                <button type="button" class="btn btn-xs btn-default" onclick="selAllAs('recaptcha', this.form)">{LANG.captcha_recaptcha}</button>
                                            </td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- BEGIN: mod -->
                                        <tr>
                                            <td>{MOD.custom_title}</td>
                                            <td>
                                                <select name="captcha_type[{MOD.title}]" class="form-control" style="margin-bottom:4px" onchange="noteShow(this, this.form)">
                                                    <!-- BEGIN: opt -->
                                                    <option value="{OPT.val}"{OPT.sel}>{OPT.title}</option>
                                                    <!-- END: opt -->
                                                </select>
                                                <div class="small text-danger"<!-- BEGIN: dnone --> style="display:none"<!-- END: dnone -->><em>{LANG.captcha_type_recaptcha_note}</em></div>
                                            </td>
                                        </tr>
                                        <!-- END: mod -->
                                    </tbody>
                                </table>
                            </div>
                            <div class="panel-footer text-center">
                                <input type="hidden" name="checkss" value="{CHECKSS}" />
                                <input type="hidden" name="modcapt" value="1" />
                                <input type="hidden" name="selectedtab" value="{SELECTEDTAB}"/>
                                <input type="submit" class="btn btn-primary w100" value="{LANG.submit}" />
                                <input type="reset" class="btn btn-default" value="{GLANG.reset}" />
                            </div>
                        </div>
                    </form>

                    <div class="col-sm-12">
                        <form action="{FORM_ACTION}" method="post">
                            <div class="panel panel-default">
                                <div class="panel-heading"><strong>{LANG.captcha_area}</strong></div>
                                <div class="panel-body">
                                    <!-- BEGIN: captcha_area -->
                                    <p>
                                        <input type="checkbox" id="captcha_area{CAPTCHAAREA.key}" name="captcha_area[]" value="{CAPTCHAAREA.key}"{CAPTCHAAREA.checked}/>
                                        <label for="captcha_area{CAPTCHAAREA.key}">{CAPTCHAAREA.title}</label>
                                    </p>
                                    <!-- END: captcha_area -->
                                </div>
                                <div class="panel-footer text-center">
                                    <input type="hidden" name="checkss" value="{CHECKSS}" />
                                    <input type="hidden" name="captarea" value="1" />
                                    <input type="hidden" name="selectedtab" value="{SELECTEDTAB}"/>
                                    <input type="submit" class="btn btn-primary w100" value="{LANG.submit}" />
                                </div>
                            </div>
                        </form>
                        <form action="{FORM_ACTION}" method="post">
                            <div class="panel panel-default">
                                <div class="panel-heading"><strong>{LANG.captcha_comm}</strong></div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <td colspan="2" class="text-center">
                                                    <div class="m-bottom">{LANG.select_all_as}:</div>
                                                    <select class="form-control d-inline-block" onchange="selAllCaptComm(this, this.form)" style="width:auto">
                                                    <option value="-1">{LANG.captcha_comm_select}</option>
                                                        <!-- BEGIN: optAll -->
                                                        <option value="{OPTALL.val}">{OPTALL.title}</option>
                                                        <!-- END: optAll -->
                                                    </select>
                                                </td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- BEGIN: modcomm -->
                                            <tr>
                                                <td>{MOD.custom_title}</td>
                                                <td>
                                                    <select name="captcha_area_comm[{MOD.title}]" class="form-control">
                                                        <!-- BEGIN: opt -->
                                                        <option value="{OPT.val}"{OPT.sel}>{OPT.title}</option>
                                                        <!-- END: opt -->
                                                    </select>
                                                </td>
                                            </tr>
                                            <!-- END: modcomm -->
                                        </tbody>
                                    </table>
                                </div>
                                <div class="panel-footer text-center">
                                    <input type="hidden" name="checkss" value="{CHECKSS}" />
                                    <input type="hidden" name="captcommarea" value="1" />
                                    <input type="hidden" name="selectedtab" value="{SELECTEDTAB}"/>
                                    <input type="submit" class="btn btn-primary w100" value="{LANG.submit}" />
                                    <input type="reset" class="btn btn-default" value="{GLANG.reset}" />
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane{TAB3_ACTIVE}" id="settingIp">
            <div class="setting-tabcontenttop">
                <!-- BEGIN: listip -->
                <div class="panel panel-default">
                    <div class="panel-heading"><strong>{LANG.banip}</strong></div>
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>{LANG.banip_ip}</th>
                                <th>{LANG.banip_mask}</th>
                                <th>{LANG.banip_area}</th>
                                <th>{LANG.banip_timeban}</th>
                                <th>{LANG.banip_timeendban}</th>
                                <th class="text-center">{LANG.banip_funcs}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- BEGIN: loop -->
                            <tr>
                                <td>{ROW.dbip}</td>
                                <td>{ROW.dbmask}</td>
                                <td>{ROW.dbarea}</td>
                                <td>{ROW.dbbegintime}</td>
                                <td>{ROW.dbendtime}</td>
                                <td class="text-center">
                                    <a class="edit btn btn-default btn-xs" title="{LANG.banip_edit}" href="{ROW.url_edit}"><em class="fa fa-edit fa-fw"></em>{LANG.banip_edit}</a>
                                    <a class="deleteone-ip btn btn-danger btn-xs" title="{LANG.banip_delete}" href="{ROW.url_delete}"><em class="fa fa-trash-o fa-fw"></em>{LANG.banip_delete}</a>
                                </td>
                            </tr>
                            <!-- END: loop -->
                        </tbody>
                    </table>
                </div>
                <!-- END: listip -->

                <form action="{FORM_ACTION}" method="post">
                    <input type="hidden" name ="cid" value="{DATA.cid}" />
                    <div class="panel panel-default">
                        <div class="panel-heading"><strong>{BANIP_TITLE}</strong></div>
                        <div class="panel-body">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="ipt_ip_version" class="col-sm-6 control-label">{LANG.ip_version}:</label>
                                    <div class="col-sm-18 col-md-14 col-lg-10">
                                        <div class="form-inline">
                                            <select class="form-control" name="ip_version" id="ipt_ip_version">
                                                <!-- BEGIN: ip_version -->
                                                <option value="{IP_VERSION.key}"{IP_VERSION.b_selected}>{IP_VERSION.title}</option>
                                                <!-- END: ip_version -->
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="ipt_ip" class="col-sm-6 control-label">{LANG.banip_address} <span style="color:red">(*)</span>:</label>
                                    <div class="col-sm-18 col-md-14 col-lg-10">
                                        <input class="form-control" type="text" id="ipt_ip" name="ip" value="{DATA.ip}">
                                    </div>
                                </div>
                                <div class="form-group{CLASS_IP_VERSION4}" id="ip4_mask">
                                    <label for="ipt_mask" class="col-sm-6 control-label">{LANG.banip_mask}:</label>
                                    <div class="col-sm-18 col-md-14 col-lg-10">
                                        <div class="form-inline">
                                            <select id="ipt_mask" name="mask" class="form-control">
                                                <option value="0">{MASK_TEXT_ARRAY.0}</option>
                                                <option value="3"{DATA.selected3}>{MASK_TEXT_ARRAY.3}</option>
                                                <option value="2"{DATA.selected2}>{MASK_TEXT_ARRAY.2}</option>
                                                <option value="1"{DATA.selected1}>{MASK_TEXT_ARRAY.1}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group{CLASS_IP_VERSION6}" id="ip6_mask">
                                    <label for="ipt_maskv6" class="col-sm-6 control-label">{LANG.banip_mask}:</label>
                                    <div class="col-sm-18 col-md-14 col-lg-10">
                                        <div class="form-inline">
                                            <select class="form-control" name="mask6" id="ipt_maskv6">
                                                <!-- BEGIN: mask6 -->
                                                <option value="{IPMASK.key}"{IPMASK.b_selected}>{IPMASK.title}</option>
                                                <!-- END: mask6 -->
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="ipt_area" class="col-sm-6 control-label">{LANG.banip_area} <span style="color:red">(*)</span>:</label>
                                    <div class="col-sm-18 col-md-14 col-lg-10">
                                        <div class="form-inline">
                                            <select id="ipt_area" name="area" class="form-control">
                                                <option value="0">{BANIP_AREA_ARRAY.0}</option>
                                                <option value="1"{DATA.selected_area_1}>{BANIP_AREA_ARRAY.1}</option>
                                                <option value="2"{DATA.selected_area_2}>{BANIP_AREA_ARRAY.2}</option>
                                                <option value="3"{DATA.selected_area_3}>{BANIP_AREA_ARRAY.3}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="ipt_begintime" class="col-sm-6 control-label">{LANG.banip_begintime}:</label>
                                    <div class="col-sm-18 col-md-14 col-lg-10">
                                        <div class="form-inline">
                                            <input type="text" id="ipt_begintime" name="begintime" class="w150 datepicker form-control pull-left" value="{DATA.begintime}" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="ipt_endtime" class="col-sm-6 control-label">{LANG.banip_endtime}:</label>
                                    <div class="col-sm-18 col-md-14 col-lg-10">
                                        <div class="form-inline">
                                            <input type="text" id="ipt_endtime" name="endtime" class="w150 datepicker form-control pull-left text" value="{DATA.endtime}" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="ipt_notice" class="col-sm-6 control-label">{LANG.banip_notice}:</label>
                                    <div class="col-sm-18 col-md-14 col-lg-10">
                                        <textarea rows="5" class="form-control" name="notice" id="ipt_notice">{DATA.notice}</textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-18 col-md-14 col-lg-10 col-sm-offset-6">
                                        <input type="hidden" name="checkss" value="{CHECKSS}" />
                                        <input type="hidden" name="save" value="1">
                                        <input type="submit" value="{LANG.banip_confirm}" class="btn btn-primary">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="selectedtab" value="{SELECTEDTAB}"/>
                </form>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane{TAB4_ACTIVE}" id="settingCORS">
            <div class="setting-tabcontent clearfix">
                <form action="{FORM_ACTION}" method="post">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover table-first">
                            <colgroup>
                                <col style="width: 40%" />
                                <col style="width: 60%" />
                            </colgroup>
                            <tbody>
                                <tr>
                                    <td><strong>{LANG.cors_site_restrict}</strong></td>
                                    <td>
                                        <label>
                                            <input type="checkbox" value="1" name="crosssite_restrict" {CONFIG_CROSS.crosssite_restrict}>
                                            {LANG.cors_site_restrict_help}
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>{LANG.cors_site_valid_domains}</strong></td>
                                    <td>
                                        <textarea rows="3" class="form-control" name="crosssite_valid_domains">{CONFIG_CROSS.crosssite_valid_domains}</textarea>
                                        <div class="form-text text-muted">{LANG.cors_valid_domains_help}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>{LANG.cors_site_valid_ips}</strong></td>
                                    <td>
                                        <textarea rows="3" class="form-control" name="crosssite_valid_ips">{CONFIG_CROSS.crosssite_valid_ips}</textarea>
                                        <div class="form-text text-muted">{LANG.cors_valid_ips_help}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>{LANG.cors_admin_restrict}</strong></td>
                                    <td>
                                        <label>
                                            <input type="checkbox" value="1" name="crossadmin_restrict" {CONFIG_CROSS.crossadmin_restrict}>
                                            {LANG.cors_admin_restrict_help}
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>{LANG.cors_admin_valid_domains}</strong></td>
                                    <td>
                                        <textarea rows="3" class="form-control" name="crossadmin_valid_domains">{CONFIG_CROSS.crossadmin_valid_domains}</textarea>
                                        <div class="form-text text-muted">{LANG.cors_valid_domains_help}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>{LANG.cors_admin_valid_ips}</strong></td>
                                    <td>
                                        <textarea rows="3" class="form-control" name="crossadmin_valid_ips">{CONFIG_CROSS.crossadmin_valid_ips}</textarea>
                                        <div class="form-text text-muted">{LANG.cors_valid_ips_help}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>{LANG.allow_null_origin}</strong></td>
                                    <td>
                                        <input type="checkbox" value="1" name="allow_null_origin" {CONFIG_CROSS.allow_null_origin}>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>{LANG.ip_allow_null_origin}</strong></td>
                                    <td>
                                        <textarea rows="3" class="form-control" name="ip_allow_null_origin">{CONFIG_CROSS.ip_allow_null_origin}</textarea>
                                        <div class="form-text text-muted">{LANG.ip_allow_null_origin_help}</div>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" class="text-center">
                                        <input type="hidden" name="checkss" value="{CHECKSS}" />
                                        <input type="submit" value="{GLANG.submit}" name="submitcors" class="btn btn-primary w100"/>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <input type="hidden" name="selectedtab" value="{SELECTEDTAB}"/>
                </form>
            </div>
        </div>
        <!-- END: sys_contents -->
        <div role="tabpanel" class="tab-pane{TAB5_ACTIVE}" id="settingCSP">
            <div class="setting-tabcontent clearfix">
                <form action="{FORM_ACTION}" method="post">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover table-first">
                            <colgroup>
                                <col style="width: 40%" />
                                <col style="width: 60%" />
                            </colgroup>
                            <tbody>
                                <tr>
                                    <td colspan="2">{LANG.csp_desc} <a href="https://content-security-policy.com/" target="_blank">{LANG.csp_details}</a></td>
                                </tr>
                                <tr>
                                    <td><strong>{LANG.csp_act}</strong></td>
                                    <td>
                                        <label>
                                            <input type="checkbox" value="1" name="nv_csp_act"{CSP_ACT}/>
                                        </label>
                                    </td>
                                </tr>
                                <!-- BEGIN: csp_directive -->
                                <tr>
                                    <td><strong>{DIRECTIVE.name}</strong><br />{DIRECTIVE.desc}</td>
                                    <td>
                                        <textarea rows="3" class="form-control" name="directives[{DIRECTIVE.name}]">{DIRECTIVE.value}</textarea>
                                        <div class="form-text text-muted">{LANG.csp_note}</div>
                                    </td>
                                </tr>
                                <!-- END: csp_directive -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" class="text-center">
                                        <input type="hidden" name="checkss" value="{CHECKSS}" />
                                        <input type="submit" value="{GLANG.submit}" name="submitcsp" class="btn btn-primary w100"/>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <input type="hidden" name="selectedtab" value="{SELECTEDTAB}"/>
                </form>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane{TAB6_ACTIVE}" id="settingRP">
            <div class="setting-tabcontent clearfix">
                <form action="{FORM_ACTION}" method="post">
                    <div>
                        <div class="well-sm">
                            {LANG.rp_desc} <a href="https://www.w3.org/TR/referrer-policy/" target="_blank">{LANG.rp_details}</a>.<br />
                            {LANG.rp_desc2}
                        </div>

                        <strong>{LANG.rp_directives}:</strong>
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                            <!-- BEGIN: rp_directive -->
                            <div class="panel panel-default">
                                <a role="button" class="panel-heading btn-block" data-toggle="collapse" data-parent="#accordion" href="#collapse-{RP_DIRECTIVE.name}" aria-expanded="false" aria-controls="collapse-{RP_DIRECTIVE.name}">
                                    {RP_DIRECTIVE.name}
                                </a>
                                <div id="collapse-{RP_DIRECTIVE.name}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="{RP_DIRECTIVE.name}">
                                    <div class="panel-body">
                                        <code><strong>{RP_DIRECTIVE.name}</strong></code>: {RP_DIRECTIVE.desc}
                                    </div>
                                </div>
                            </div>
                            <!-- END: rp_directive -->
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover table-first border-top">
                            <colgroup>
                                <col style="width: 30%" />
                                <col style="width: 70%" />
                            </colgroup>
                            <tbody>
                                <tr>
                                    <td colspan="2">{LANG.rp_note}</td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>{LANG.rp_act}</strong>
                                    </td>
                                    <td><input type="checkbox" value="1" name="nv_rp_act"{RP_ACT}/></td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>{LANG.rp_directives}</strong>
                                    </td>
                                    <td>
                                        <input class="form-control pull-left" type="text" value="{RP}" name="nv_rp" maxlength="500"/>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" class="text-center">
                                        <input type="hidden" name="checkss" value="{CHECKSS}" />
                                        <input type="submit" value="{GLANG.submit}" name="submitrp" class="btn btn-primary w100"/>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <input type="hidden" name="selectedtab" value="{SELECTEDTAB}"/>
                </form>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="gselectedtab" value="{SELECTEDTAB}"/>
<script type="text/javascript">
function noteShow(obj, form) {
    var val = $(obj).val(),
        sitekey = $(form).data('recaptcha-sitekey'),
        secretkey = $(form).data('recaptcha-secretkey');
    if (val != 'recaptcha' || (val == 'recaptcha' && sitekey != '' && secretkey != '')) {
        $(obj).next().hide()
    } else {
        $(obj).next().show()
    }
}

function selAllAs(type, form) {
    $('select', form).val(type);
    $('[type=submit]', form).focus()
}

function selAllCaptComm(obj, form) {
    var val = $(obj).val();
    if (val != '-1') {
        $('[name*=captcha_area_comm]', form).val(val);
        $(obj).val('-1');
        $('[type=submit]', form).focus()
    }
}

var LANG = [];
LANG.banip_delete_confirm = '{LANG.banip_delete_confirm}';
LANG.banip_del_success = '{LANG.banip_del_success}';
$(document).ready(function() {
    $('#settingTabs a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
        $('[name="selectedtab"]').val($(this).attr('aria-offsets'));
        $('[name="gselectedtab"]').val($(this).attr('aria-offsets'));
    });

    // Đổi phiên bản IP bỏ qua kiểm tra Flood
    $('#ipt_fip_version').on('change', function() {
        if ($(this).val() == '4') {
            $('#ip4_fmask').removeClass('hidden');
            $('#ip6_fmask').addClass('hidden');
        } else {
            $('#ip4_fmask').addClass('hidden');
            $('#ip6_fmask').removeClass('hidden');
        }
    });

    // Đổi phiên bản IP cấm
    $('#ipt_ip_version').on('change', function() {
        if ($(this).val() == '4') {
            $('#ip4_mask').removeClass('hidden');
            $('#ip6_mask').addClass('hidden');
        } else {
            $('#ip4_mask').addClass('hidden');
            $('#ip6_mask').removeClass('hidden');
        }
    });
});
</script>
<!-- END: main -->
