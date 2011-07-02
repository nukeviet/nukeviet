<!-- BEGIN: main -->
<script type="text/javascript">
    function nv_check_form(OForm)
    {
        var f_method = document.getElementById( 'f_method' ).options[document.getElementById( 'f_method' ).selectedIndex].value;
        var f_value = document.getElementById( 'f_value' ).value;
    
        if(f_method != '' && f_value != '')
        {
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
                <td>
                    <strong>{LANG.funcs}</strong>
                </td>
            </tr>
        </thead>
        <!-- BEGIN: xusers -->
        <tbody>
            <tr>
                <td>
                    {CONTENT_TD.userid}
                </td>
                <td>
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
                <td>
                    &nbsp;&nbsp;<span class="edit_icon"><a href="{ACTIVATE_URL}">{LANG.awaiting_active}</a></span>
                    &nbsp;&nbsp;<span class="delete_icon"><a href="javascript:void(0);" onclick="nv_waiting_row_del({CONTENT_TD.userid});">{LANG.delete}</a></span>
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
