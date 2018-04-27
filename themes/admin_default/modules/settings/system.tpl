<!-- BEGIN: main -->
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>

<!-- BEGIN: error -->
<div class="alert alert-danger">
    {ERROR}
</div>
<!-- END: error -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <col class="w400" />
            <tbody>
                <tr>
                    <td><strong>{LANG.closed_site}</strong></td>
                    <td>
                    <select name="closed_site" class="form-control w300">
                        <!-- BEGIN: closed_site_mode -->
                        <option value="{MODE_VALUE}"{MODE_SELECTED}>{MODE_NAME}</option>
                        <!-- END: closed_site_mode -->
                    </select></td>
                </tr>
                <tr>
                    <td><strong>{LANG.site_email}</strong></td>
                    <td><input type="text" name="site_email" value="{DATA.site_email}" class="form-control" style="width: 450px"/></td>
                </tr>
                <tr>
                    <td><strong>{LANG.site_phone}</strong></td>
                    <td><input type="text" name="site_phone" value="{DATA.site_phone}" class="form-control" style="width: 450px"/></td>
                </tr>
                <tr>
                    <td><strong>{LANG.themeadmin}</strong></td>
                    <td>
                    <select name="admin_theme" class="form-control w300" >
                        <!-- BEGIN: admin_theme -->
                        <option value="{THEME_NAME}"{THEME_SELECTED}>{THEME_NAME}</option>
                        <!-- END: admin_theme -->
                    </select></td>
                </tr>
                <tr>
                    <td><strong>{LANG.date_pattern}</strong></td>
                    <td><input type="text" name="date_pattern" value="{DATA.date_pattern}" class="w150 form-control"/></td>
                </tr>
                <tr>
                    <td><strong>{LANG.time_pattern}</strong></td>
                    <td><input type="text" name="time_pattern" value="{DATA.time_pattern}" class="w150 form-control"/></td>
                </tr>
                <tr>
                    <td><strong>{LANG.ssl_https}</strong></td>
                    <td>
                        <select id="ssl_https" name="ssl_https" class="form-control w300" data-val="{DATA.ssl_https}">
                            <!-- BEGIN: ssl_https -->
                            <option value="{SSL_HTTPS.key}"{SSL_HTTPS.selected}>{SSL_HTTPS.title}</option>
                            <!-- END: ssl_https -->
                        </select>
                    </td>
                </tr>
                <!-- BEGIN: system -->
                <tr>
                    <td><strong>{LANG.lang_multi}</strong></td>
                    <td><input type="checkbox" value="1" name="lang_multi" {CHECKED_LANG_MULTI} data-toggle="controlrw"/></td>
                </tr>
                <!-- BEGIN: lang_multi -->
                <tr>
                    <td><strong>{LANG.site_lang}</strong></td>
                    <td>
                    <select name="site_lang" class="form-control w300">
                        <!-- BEGIN: site_lang_option -->
                        <option value="{LANGOP}" {SELECTED}>{LANGVALUE} </option>
                        <!-- END: site_lang_option -->
                    </select></td>
                </tr>
                <!-- BEGIN: lang_geo -->
                <tr>
                    <td><strong>{LANG.lang_geo}</strong></td>
                    <td><input type="checkbox" value="1" name="lang_geo" {CHECKED_LANG_GEO} /> ( <a href="{CONFIG_LANG_GEO}">{LANG.lang_geo_config}</a> ) </td>
                </tr>
                <!-- END: lang_geo -->
                <!-- END: lang_multi -->
                <tr>
                    <td><strong>{LANG.rewrite}</strong></td>
                    <td><input type="checkbox" value="1" name="rewrite_enable" {CHECKED_REWRITE_ENABLE} data-toggle="controlrw"/></td>
                </tr>
                <tr id="tr_rewrite_optional"{SHOW_REWRITE_OPTIONAL}>
                    <td><strong>{LANG.rewrite_optional}</strong></td>
                    <td><input type="checkbox" value="1" name="rewrite_optional" {CHECKED_REWRITE_OPTIONAL} data-toggle="controlrw1"/></td>
                </tr>
                <tr id="tr_rewrite_op_mod"{SHOW_REWRITE_OP_MOD}>
                    <td><strong>{LANG.rewrite_op_mod}</strong></td>
                    <td>
                    <select name="rewrite_op_mod" class="form-control w300">
                        <option value="">&nbsp;</option>
                        <!-- BEGIN: rewrite_op_mod -->
                        <option value="{MODE_VALUE}"{MODE_SELECTED}>{MODE_NAME}</option>
                        <!-- END: rewrite_op_mod -->
                    </select></td>
                </tr>
                <tr>
                    <td><strong>{LANG.site_timezone}</strong></td>
                    <td>
                    <select name="site_timezone" id="site_timezone" class="form-control w300">
                        <option value="">{LANG.timezoneAuto}</option>
                        <!-- BEGIN: opsite_timezone -->
                        <option value="{TIMEZONEOP}" {TIMEZONESELECTED}>{TIMEZONELANGVALUE} </option>
                        <!-- END: opsite_timezone -->
                    </select>
                    {CURRENT_TIME}
                    </td>
                </tr>
                <tr>
                    <td><strong>{LANG.gzip_method}</strong></td>
                    <td><input type="checkbox" value="1" name="gzip_method" {CHECKED_GZIP_METHOD} /></td>
                </tr>
                <tr>
                    <td><strong>{LANG.my_domains}</strong></td>
                    <td><input type="text" name="my_domains" value="{MY_DOMAINS}" class="form-control" style="width: 450px"/></td>
                </tr>
                <tr>
                    <td><strong>{LANG.cdn_url}</strong></td>
                    <td><input type="text" name="cdn_url" value="{DATA.cdn_url}" class="form-control pull-left" style="width: 220px; margin-right: 10px"/><input type="button" value="{LANG.cdn_download}"  id="cdn_download" class="btn btn-info"/></td>
                </tr>
                <tr>
                    <td><strong>{LANG.error_set_logs}</strong></td>
                    <td><input type="checkbox" value="1" name="error_set_logs" {CHECKED_ERROR_SET_LOGS} /></td>
                </tr>
                <tr>
                    <td><strong>{LANG.nv_debug}</strong></td>
                    <td><label><input type="checkbox" name="nv_debug" value="1"{CFG_DEFINE.nv_debug}/> {LANG.nv_debug_help}</label></td>
                </tr>
                <tr>
                    <td><strong>{LANG.error_send_email}</strong></td>
                    <td><input type="text" name="error_send_email" value="{DATA.error_send_email}" class="form-control" style="width: 450px"/></td>
                </tr>
                <!-- END: system -->
                <tr>
                    <td><strong>{LANG.searchEngineUniqueID}</strong></td>
                    <td><input type="text" name="searchEngineUniqueID" value="{DATA.searchEngineUniqueID}" class="form-control" style="width: 450px" maxlength="50" /></td>
                </tr>
                <tr>
                    <td><strong>{LANG.googleMapsAPI}</strong><br />({LANG.googleMapsAPI_guide})</td>
                    <td><input type="text" name="googleMapsAPI" value="{DATA.googleMapsAPI}" class="form-control" style="width: 450px" maxlength="50" /></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <caption>
                {LANG.notification_config}
            </caption>
            <col class="w500" />
            <tbody>
                <tr>
                    <td><strong>{LANG.notification_active}</strong></td>
                    <td><input type="checkbox" name="notification_active" value="1" {CHECKED_NOTIFI_ACTIVE} /></td>
                </tr>
                <tr>
                    <td><strong>{LANG.notification_autodel}</strong><span class="help-block">{LANG.notification_autodel_note}</span></td>
                    <td><input type="text" name="notification_autodel" value="{DATA.notification_autodel}" class="form-control w100 pull-left" />&nbsp;<span class="text-middle">{LANG.notification_day}</span></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="text-center">
        <input type="submit" name="submit" value="{LANG.submit}" class="btn btn-primary"/>
    </div>
</form>
<script type="text/javascript">
$(document).ready(function() {
    $("#site_timezone").select2();
});

var LANG = [];
LANG.note_ssl = "{LANG.note_ssl}";
var CFG = [];
CFG.cdndl = "{NV_CHECK_SESSION}";
</script>
<!-- END: main -->
