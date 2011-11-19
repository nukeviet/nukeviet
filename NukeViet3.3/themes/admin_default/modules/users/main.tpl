<!-- BEGIN: main -->
<script type="text/javascript">
function nv_check_form(OForm){
	if(document.getElementById('f_value').value != ''){
		OForm.submit();
	}
	return false;
}
</script>
<div id="users">
    <!-- BEGIN: is_forum -->
    <div class="quote" style="width:780px;">
        <blockquote class="error">
            <span>{LANG.modforum}</span>
        </blockquote>
    </div>
    <div class="clear"></div>
    <!-- END: is_forum -->
    <div style="padding-top:10px;">
    <form action="{FORM_ACTION}" method="post" onsubmit="nv_check_form(this);return false;">
        <span><strong>{LANG.search_type}:</strong></span>
        <select name="method" id="f_method">
            <option value="">---</option>
            <!-- BEGIN: method -->
            <option value="{METHODS.key}"{METHODS.selected}>{METHODS.value}</option>
            <!-- END: method -->
        </select>
        <input type="text" name="value" id="f_value" value="{SEARCH_VALUE}" />
        <input name='search' type="submit" value="{LANG.submit}" />
        <p>
            {LANG.search_note}
        </p>
    </form>
    </div>
    <table class="tab1">
        <caption>{TABLE_CAPTION}</caption>
        <thead>
            <tr>
                <!-- BEGIN: head_td -->
                <td>
                    <a href="{HEAD_TD.href}">{HEAD_TD.title}</a>
                </td>
                <!-- END: head_td -->
                <td style="text-align: center">
                    <strong>{LANG.memberlist_active}</strong>
                </td>
                <td>
                    <strong>{LANG.funcs}</strong>
                </td>
            </tr>
        <thead>
        <!-- BEGIN: xusers -->
        <tbody>
            <tr>
                <td>
                    {CONTENT_TD.userid}
                </td>
                <td>
                    <!-- BEGIN: is_admin -->
                    <img style="vertical-align:middle;" alt="{CONTENT_TD.level}" title="{CONTENT_TD.level}" src="{NV_BASE_SITEURL}themes/{NV_ADMIN_THEME}/images/{CONTENT_TD.img}.png" width="38" height="18" />
                    <!-- END: is_admin -->
                    {CONTENT_TD.username}
                </td>
                <td>
                    {CONTENT_TD.full_name}
                </td>
                <td>
                    <a href="mailto:{CONTENT_TD.email}">{CONTENT_TD.email}</a>
                </td>
                <td>
                    {CONTENT_TD.regdate}
                </td>
                <td style="text-align: center">
                    <input type="checkbox" name="active" id="change_status_{CONTENT_TD.userid}" value="{CONTENT_TD.userid}"{CONTENT_TD.checked}{CONTENT_TD.disabled} />
                </td>
                <td style="white-space: nowrap">
                    <!-- BEGIN: edit -->
                    &nbsp;&nbsp;<span class="edit_icon"><a href="{EDIT_URL}">{LANG.memberlist_edit}</a></span>
                    <!-- END: edit -->
                    <!-- BEGIN: del -->
                    &nbsp;&nbsp;<span class="delete_icon"><a href="javascript:void(0);" onclick="nv_row_del({CONTENT_TD.userid});">{LANG.delete}</a></span>
                    <!-- END: del -->
                </td>
            </tr>
        </tbody>
        <!-- END: xusers -->
        <!-- BEGIN: generate_page -->
        <tr class="footer">
            <td colspan="8">
                {GENERATE_PAGE}
            </td>
        </tr>
        <!-- END: generate_page -->
    </table>
</div>
<!-- END: main -->
