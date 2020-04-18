<!-- BEGIN: main -->
<div class="alert alert-info"><span id="message">{LANG.xcopyblock_notice}</span></div>
<form name="copy_block" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}" method="post">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <tfoot>
                <tr>
                    <td class="text-center" colspan="4">
                        <input name="continue" type="button" value="{LANG.xcopyblock_process}" class="btn btn-primary" />
                        <input type="button" value="{LANG.block_checkall}" data-toggle="checkallpos" data-target="[name='position[]']" class="btn btn-default"/>
                    </td>
                </tr>
            </tfoot>
            <tbody>
                <tr>
                    <td class="text-right"><strong class="text-middle">{LANG.xcopyblock} {LANG.xcopyblock_from}: </strong><input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}"/></td>
                    <td width="200">
                        <select name="theme1" class="form-control w200 pull-left">
                            <option value="0">{LANG.autoinstall_method_theme_none}</option>
                            <!-- BEGIN: theme_from -->
                            <option value="{THEME_FROM}">{THEME_FROM}</option>
                            <!-- END: theme_from -->
                        </select>
                    </td>
                    <td><strong class="text-middle">{LANG.xcopyblock_to}: </strong></td>
                    <td>
                        <select name="theme2" class="form-control w200 pull-left">
                            <option value="0">{LANG.autoinstall_method_theme_none}</option>
                            <!-- BEGIN: theme_to -->
                            <option value="{THEME_TO.key}"{THEME_TO.selected}>{THEME_TO.key}</option>
                            <!-- END: theme_to -->
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        <div id="loadposition"></div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</form>
<script type="text/javascript">
//<![CDATA[
LANG.autoinstall_package_processing = '{LANG.autoinstall_package_processing}';
LANG.xcopyblock_no_position = '{LANG.xcopyblock_no_position}';
//]]>
</script>
<!-- END: main -->