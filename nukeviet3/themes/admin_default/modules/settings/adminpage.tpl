<!-- BEGIN: main -->
<div id="settingFront"></div>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){$("#settingFront").load("{LOAD_SETTING}&num="+nv_randomPassword(10))});
//]]>
</script>
<!-- END: main -->
<!-- BEGIN: setting -->
<table class="tab1" summary="">
    <tbody class="second">
        <tr>
            <td>
                <strong>{LANG.themeadmin}</strong>
            </td>
            <td>
                <select id="admin_theme">
                    <!-- BEGIN: admin_theme -->
                    <option value="{THEME_VALUE}"{THEME_SELECTED}>{THEME_NAME}</option>
                    <!-- END: admin_theme -->
                </select>
            </td>
        </tr>
    </tbody>
    <tbody>
        <tr>
            <td>
                <strong>{LANG.loginMode}</strong>
            </td>
            <td>
                <select id="admin_login_mode">
                    <!-- BEGIN: admin_login_mode -->
                    <option value="{MODE_VALUE}"{MODE_SELECTED}>{MODE_NAME}</option>
                    <!-- END: admin_login_mode -->
                </select>
            </td>
        </tr>
    </tbody>
</table>
<div id="settingSubmit" style="height:20px">
    <a class="button1" href="#"><span><span>{LANG.submit}</span></span></a>
</div>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){$("#settingSubmit a").click(function(){var a=$("#admin_theme").attr("value"),b=$("#admin_login_mode").attr("value");$("#settingFront").text("").load("{LOAD_SAVE}&admin_theme="+a+"&admin_login_mode="+b+"&num="+nv_randomPassword(10));return false})});
//]]>
</script>
<!-- END: setting -->
<!-- BEGIN: save -->
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){$("#settingFront").text("").load("{LOAD_SETTING2}&num="+nv_randomPassword(10))});
//]]>
</script>
<!-- END: save -->
