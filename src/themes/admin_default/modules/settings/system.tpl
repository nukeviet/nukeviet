<!-- BEGIN: main -->
<link type="text/css" href="{ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{ASSETS_STATIC_URL}/js/select2/select2.min.css">
<script type="text/javascript" src="{ASSETS_STATIC_URL}/js/select2/select2.min.js"></script>
<script type="text/javascript" src="{ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{ASSETS_LANG_STATIC_URL}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<!-- BEGIN: closed_site -->
<div class="panel panel-{SITE_STATUS.panel} mb-lg">
    <div class="panel-heading">
        <div class="d-flex" style="align-items: center">
            <div><em class="fa {SITE_STATUS.icon} fa-3x fa-fw"></em></div>
            <div style="flex-grow: 1">
                <strong>{SITE_STATUS.title}</strong>
                <!-- BEGIN: reopening -->
                <div class="small" style="margin-top:6px;">{SITE_STATUS.reopening}</div>
                <!-- END: reopening -->
            </div>
            <div style="margin-left: 5px"><button type="button" class="btn btn-{SITE_STATUS.panel} btn-sm" data-toggle="collapse" data-target="#closed_site_collapse" aria-expanded="false" aria-controls="closed_site_collapse"><em class="fa fa-cogs"></em><span class="hidden-xs"> {LANG.closed_site}</span></button></div>
        </div>
    </div>
    <div class="collapse" id="closed_site_collapse">
        <form class="panel-body ajax-submit" id="change-site-mode" action="{FORM_ACTION}" method="post">
            <div class="row">
                <div class="col-md-10 col-md-offset-7">
                    <div class="form-group">
                        <select name="closed_site" class="form-control">
                            <!-- BEGIN: closed_site_mode -->
                            <option value="{MODE_VALUE}" {MODE_SELECTED}>{MODE_NAME}</option>
                            <!-- END: closed_site_mode -->
                        </select>
                    </div>
                    <div id="reopening_time" class="form-group" style="<!-- BEGIN: reopening_time -->display:none<!-- END: reopening_time -->">
                        <div class="text-center m-bottom">{LANG.closed_site_reopening_time}</div>
                        <div class="d-flex">
                            <input class="form-control" style="flex-grow: 1" name="reopening_date" id="reopening_date" value="{DATA.reopening_date}" maxlength="10" type="text" />
                            <select class="form-control" name="reopening_hour" style="width: fit-content;margin-left:3px">
                                <!-- BEGIN: reopening_hour -->
                                <option value="{RHOUR.num}" {RHOUR.sel}>{RHOUR.title}</option>
                                <!-- END: reopening_hour -->
                            </select>
                            <select class="form-control" name="reopening_min" style="width: fit-content;margin-left:3px">
                                <!-- BEGIN: reopening_min -->
                                <option value="{RMIN.num}" {RMIN.sel}>{RMIN.title}</option>
                                <!-- END: reopening_min -->
                            </select>
                        </div>
                    </div>

                    <div class="text-center">
                        <input type="hidden" name="site_mode" value="1" />
                        <input type="submit" name="submit" value="{LANG.submit}" class="btn btn-{SITE_STATUS.panel}" />
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- END: closed_site -->

<form id="system-settings" class="form-horizontal ajax-submit mb-lg" action="{FORM_ACTION}" method="post">
    <div class="panel-group" id="accordion-settings" role="tablist" aria-multiselectable="true">
        <div class="panel panel-primary">
            <a role="tab" id="headingOne" class="panel-heading" style="display: block;text-decoration:none" data-toggle="collapse" data-parent="#accordion-settings" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                {LANG.general_settings}
            </a>
            <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                <ul class="list-group type2n1">
                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <label class="col-sm-10 control-label"><strong>{GLANG.site_email}</strong></label>
                            <div class="col-sm-14">
                                <input type="email" name="site_email" value="{DATA.site_email}" class="form-control" />
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <label class="col-sm-10 control-label"><strong>{GLANG.site_phone}</strong></label>
                            <div class="col-sm-14">
                                <div class="input-group">
                                    <input type="text" name="site_phone" value="{DATA.site_phone}" class="form-control" />
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-info" data-toggle="phone_note" title="{GLANG.phone_note_title}"><i class="fa fa-question" aria-hidden="true"></i></button>
                                    </span>
                                </div>
                                <div class="phone_note_content hide">
                                    {GLANG.phone_note_content2}
                                </div>
                            </div>
                        </div>
                    </li>

                    <!-- BEGIN: my_domains -->
                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <label class="col-sm-10 control-label"><strong>{LANG.my_domains}</strong></label>
                            <div class="col-sm-14">
                                <input type="text" name="my_domains" value="{MY_DOMAINS}" class="form-control" />
                                <div class="help-block mb-0">{LANG.params_info}</div>
                            </div>
                        </div>
                    </li>
                    <!-- END: my_domains -->

                    <!-- BEGIN: timesettings -->
                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <label class="col-sm-10 control-label"><strong>{LANG.site_timezone}</strong></label>
                            <div class="col-sm-14">
                                <select name="site_timezone" id="site_timezone" class="form-control" style="width:100%">
                                    <option value="">{LANG.timezoneAuto}</option>
                                    <!-- BEGIN: opsite_timezone -->
                                    <option value="{TIMEZONEOP}" {TIMEZONESELECTED}>{TIMEZONELANGVALUE} </option>
                                    <!-- END: opsite_timezone -->
                                </select>
                                <div class="help-block">{CURRENT_TIME}</div>
                            </div>
                        </div>
                    </li>
                    <!-- END: timesettings -->

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <label class="col-sm-10 control-label"><strong>{LANG.ssl_https}</strong></label>
                            <div class="col-sm-14">
                                <select id="ssl_https" name="ssl_https" class="form-control" data-val="{DATA.ssl_https}" data-confirm="{LANG.note_ssl}" style="width:fit-content">
                                    <!-- BEGIN: ssl_https -->
                                    <option value="{SSL_HTTPS.key}" {SSL_HTTPS.selected}>{SSL_HTTPS.title}</option>
                                    <!-- END: ssl_https -->
                                </select>
                            </div>
                        </div>
                    </li>

                    <!-- BEGIN: optimization_settings -->
                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <label class="col-sm-10 control-label hidden-xs"><strong>{LANG.gzip_method}</strong></label>
                            <div class="col-sm-14">
                                <label class="checkbox-inline">
                                    <input type="checkbox" class="form-control" value="1" name="gzip_method" {CHECKED_GZIP_METHOD} />
                                    <strong class="visible-xs-inline-block">{LANG.gzip_method}</strong>
                                </label>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <label class="col-sm-10 control-label"><strong>{LANG.resource_preload}</strong></label>
                            <div class="col-sm-14">
                                <select name="resource_preload" class="form-control" style="width:fit-content">
                                    <!-- BEGIN: resource_preload -->
                                    <option value="{PRELOAD.val}" {PRELOAD.sel}>{PRELOAD.name}</option>
                                    <!-- END: resource_preload -->
                                </select>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <label class="col-sm-10 control-label hidden-xs"><strong>{LANG.blank_operation}</strong></label>
                            <div class="col-sm-14">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" class="form-control" value="1" name="blank_operation" {CHECKED_BLANK_OPERATION}>
                                        <strong class="visible-xs-inline-block">{LANG.blank_operation}.</strong> {LANG.blank_operation_help}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </li>
                    <!-- END: optimization_settings -->
                </ul>
            </div>
        </div>

        <!-- BEGIN: lang_rewrite -->
        <div class="panel panel-primary">
            <a role="tab" id="headingTwo" class="panel-heading collapsed" style="display: block;text-decoration:none" data-toggle="collapse" data-parent="#accordion-settings" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                {LANG.lang_rewrite_settings}
            </a>
            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                <ul class="list-group">
                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <label class="col-sm-10 control-label hidden-xs"><strong>{LANG.lang_multi}</strong></label>
                            <div class="col-sm-14">
                                <label class="checkbox-inline">
                                    <input type="checkbox" class="form-control" value="1" name="lang_multi" {CHECKED_LANG_MULTI} data-toggle="controlrw" />
                                    <strong class="visible-xs-inline-block">{LANG.lang_multi}</strong>
                                </label>
                            </div>
                        </div>
                    </li>

                    <!-- BEGIN: lang_multi -->
                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <label class="col-sm-10 control-label"><strong>{LANG.site_lang}</strong></label>
                            <div class="col-sm-14">
                                <select name="site_lang" class="form-control" style="width:fit-content">
                                    <!-- BEGIN: site_lang_option -->
                                    <option value="{LANGOP}" {SELECTED}>{LANGVALUE} </option>
                                    <!-- END: site_lang_option -->
                                </select>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item" id="lang-geo" style="<!-- BEGIN: hide_geo -->display:none;<!-- END: hide_geo -->">
                        <div class="form-group mb-0">
                            <label class="col-sm-10 control-label"><strong>{LANG.lang_geo}</strong></label>
                            <div class="col-sm-14">
                                <div class="checkbox-inline" style="cursor:auto;">
                                    <input type="checkbox" class="form-control" value="1" name="lang_geo" {CHECKED_LANG_GEO} />
                                    <span style="margin-left:20px">
                                        <i class="fa fa-arrow-right" aria-hidden="true"></i>
                                        <a href="{CONFIG_LANG_GEO}">{LANG.lang_geo_config}</a>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </li>
                    <!-- END: lang_multi -->

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <label class="col-sm-10 control-label hidden-xs"><strong>{LANG.rewrite}</strong></label>
                            <div class="col-sm-14">
                                <label class="checkbox-inline">
                                    <input type="checkbox" class="form-control" value="1" name="rewrite_enable" {CHECKED_REWRITE_ENABLE} data-toggle="controlrw" />
                                    <strong class="visible-xs-inline-block">{LANG.rewrite}</strong>
                                </label>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item" id="tr_rewrite_optional" {SHOW_REWRITE_OPTIONAL}>
                        <div class="form-group mb-0">
                            <label class="col-sm-10 control-label hidden-xs"><strong>{LANG.rewrite_optional}</strong></label>
                            <div class="col-sm-14">
                                <label class="checkbox-inline">
                                    <input type="checkbox" class="form-control" value="1" name="rewrite_optional" {CHECKED_REWRITE_OPTIONAL} data-toggle="controlrw1" />
                                    <strong class="visible-xs-inline-block">{LANG.rewrite_optional}</strong>
                                </label>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item" id="tr_rewrite_op_mod" {SHOW_REWRITE_OP_MOD}>
                        <div class="form-group mb-0">
                            <label class="col-sm-10 control-label"><strong>{LANG.rewrite_op_mod}</strong></label>
                            <div class="col-sm-14">
                                <select name="rewrite_op_mod" class="form-control" style="width: fit-content;">
                                    <option value="">&nbsp;---&nbsp;</option>
                                    <!-- BEGIN: rewrite_op_mod -->
                                    <option value="{MODE_VALUE}" {MODE_SELECTED}>{MODE_NAME}</option>
                                    <!-- END: rewrite_op_mod -->
                                </select>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <label class="col-sm-10 control-label hidden-xs"><strong>{LANG.static_noquerystring}</strong></label>
                            <div class="col-sm-14">
                                <label class="checkbox-inline">
                                    <input type="checkbox" class="form-control" name="static_noquerystring" value="1" {STATIC_NOQUERYSTRING_CHECKED} />
                                    <strong class="visible-xs-inline-block">{LANG.static_noquerystring}</strong>
                                </label>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <!-- END: lang_rewrite -->

        <!-- BEGIN: error_handling -->
        <div class="panel panel-primary">
            <a role="tab" id="headingThree" class="panel-heading collapsed" style="display: block;text-decoration:none" data-toggle="collapse" data-parent="#accordion-settings" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                {LANG.error_handler_settings}
            </a>
            <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                <ul class="list-group type2n1">
                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <label class="col-sm-10 control-label hidden-xs"><strong>{LANG.error_set_logs}</strong></label>
                            <div class="col-sm-14">
                                <label class="checkbox-inline">
                                    <input type="checkbox" class="form-control" value="1" name="error_set_logs" {CHECKED_ERROR_SET_LOGS} />
                                    <strong class="visible-xs-inline-block">{LANG.error_set_logs}</strong>
                                </label>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <label class="col-sm-10 control-label hidden-xs"><strong>{LANG.nv_debug}</strong></label>
                            <div class="col-sm-14">
                                <label class="checkbox-inline">
                                    <input type="checkbox" class="form-control" name="nv_debug" value="1" {CFG_DEFINE.nv_debug} />
                                    <strong class="visible-xs-inline-block">{LANG.nv_debug}</strong>
                                </label>
                                <div class="help-block mb-0">{LANG.nv_debug_help}</div>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <label class="col-sm-10 control-label"><strong>{LANG.error_send_email}</strong></label>
                            <div class="col-sm-14">
                                <input type="email" name="error_send_email" value="{DATA.error_send_email}" class="form-control" />
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <!-- END: error_handling -->

        <div class="panel panel-primary">
            <a role="tab" id="headingFour" class="panel-heading collapsed" style="display: block;text-decoration:none" data-toggle="collapse" data-parent="#accordion-settings" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                {LANG.search_settings}
            </a>
            <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
                <ul class="list-group type2n1">
                    <!-- BEGIN: unsign_vietwords -->
                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <label class="col-sm-10 control-label hidden-xs"><strong>{LANG.unsign_vietwords}</strong></label>
                            <div class="col-sm-14">
                                <label class="checkbox-inline">
                                    <input type="checkbox" class="form-control" value="1" name="unsign_vietwords" {CHECKED_UNSIGN_VIETWORDS} />
                                    <strong class="visible-xs-inline-block">{LANG.unsign_vietwords}</strong>
                                </label>
                                <div class="help-block mb-0">{LANG.unsign_vietwords_note}</div>
                            </div>
                        </div>
                    </li>
                    <!-- END: unsign_vietwords -->

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <label class="col-sm-10 control-label"><strong>{LANG.searchEngineUniqueID}</strong></label>
                            <div class="col-sm-14">
                                <input type="text" name="searchEngineUniqueID" value="{DATA.searchEngineUniqueID}" class="form-control" maxlength="50" />
                                <div class="help-block mb-0">{LANG.searchEngineUniqueID_note}</div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <div class="panel panel-primary">
            <a role="tab" id="headingFive" class="panel-heading collapsed" style="display: block;text-decoration:none" data-toggle="collapse" data-parent="#accordion-settings" href="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                {LANG.acp_settings}
            </a>
            <div id="collapseFive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFive">
                <ul class="list-group type2n1">
                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <label class="col-sm-10 control-label"><strong>{LANG.themeadmin}</strong></label>
                            <div class="col-sm-14">
                                <select name="admin_theme" class="form-control" style="width:fit-content">
                                    <!-- BEGIN: admin_theme -->
                                    <option value="{THEME_NAME}" {THEME_SELECTED}>{THEME_NAME}</option>
                                    <!-- END: admin_theme -->
                                </select>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <label class="col-sm-10 control-label hidden-xs"><strong>{LANG.notification_active}</strong></label>
                            <div class="col-sm-14">
                                <label class="checkbox-inline">
                                    <input type="checkbox" class="form-control" name="notification_active" value="1" {CHECKED_NOTIFI_ACTIVE} />
                                    <strong class="visible-xs-inline-block">{LANG.notification_active}</strong>
                                </label>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="form-group mb-0">
                            <label class="col-sm-10 control-label"><strong>{LANG.notification_autodel}</strong></label>
                            <div class="col-sm-14">
                                <div class="input-group w150">
                                    <input type="text" name="notification_autodel" value="{DATA.notification_autodel}" class="form-control number" />
                                    <span class="input-group-addon">{LANG.notification_day}</span>
                                </div>
                                <div class="help-block">{LANG.notification_autodel_note}</div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="text-center">
        <input type="hidden" name="checkss" value="{DATA.checkss}" />
        <input type="submit" name="submit" value="{LANG.submit}" class="btn btn-primary" />
    </div>
</form>
<!-- END: main -->
