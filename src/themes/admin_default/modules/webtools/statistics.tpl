<!-- BEGIN: main -->
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>

<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <tfoot>
                <tr>
                    <td colspan="2" class="text-center"><input type="hidden" name="checkss" value="{CHECKSS}" /><input type="submit" name="submit" value="{LANG.submit}" class="btn btn-primary"/></td>
                </tr>
            </tfoot>
            <tbody>
                <tr>
                    <td><strong>{LANG.statistics_timezone}</strong></td>
                    <td>
                    <select name="statistics_timezone" id="statistics_timezone" class="form-control w200">
                        <!-- BEGIN: timezone -->
                        <option value="{TIMEZONEOP}" {TIMEZONESELECTED}>{TIMEZONELANGVALUE} </option>
                        <!-- END: timezone -->
                    </select></td>
                </tr>
                <tr>
                    <td><strong>{LANG.online_upd}</strong></td>
                    <td><input type="checkbox" value="1" name="online_upd" {DATA.online_upd} /></td>
                </tr>
                <tr>
                    <td><strong>{LANG.statistic}</strong></td>
                    <td><input type="checkbox" value="1" name="statistic" {DATA.statistic} /></td>
                </tr>
                <tr>
                    <td><strong>{LANG.referer_blocker}</strong></td>
                    <td><input type="checkbox" value="1" name="referer_blocker" {DATA.referer_blocker} /></td>
                </tr>
                <tr>
                    <td><strong>{LANG.googleAnalytics4ID}</strong></td>
                    <td><input type="text" class="form-control w400" name="googleAnalytics4ID" value="{DATA.googleAnalytics4ID}" maxlength="20" /></td>
                </tr>
                <tr>
                    <td><strong>{LANG.googleAnalyticsID}</strong></td>
                    <td><input type="text" class="form-control w400" name="googleAnalyticsID" value="{DATA.googleAnalyticsID}" maxlength="20" /></td>
                </tr>
            </tbody>
        </table>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function() {
        $("#statistics_timezone").select2();
    });
</script>
<!-- END: main -->
