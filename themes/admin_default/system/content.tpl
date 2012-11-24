<!-- BEGIN: main -->
{FILE "header.tpl"}
<div id="middle_column_r" style="width: 99%">
    <div class="info">
        <!-- BEGIN: select_option -->
        <div class="go">
            <select name="select_options" onchange="top.location.href=this.options[this.selectedIndex].value;return;">
                <option value="">{PLEASE_SELECT}</option>
                <!-- BEGIN: select_option_loop --><option value="{SELECT_VALUE}">{SELECT_NAME}</option>
                <!-- END: select_option_loop -->
            </select>
        </div>
        <!-- END: select_option -->
        <!-- BEGIN: site_mods -->
        <div class="go">
            <span>&bull;</span>
            <a href="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}">{NV_GO_CLIENTMOD}</a>
        </div>
        <!-- END: site_mods --><!-- BEGIN: empty_page_title -->&raquo; <span>{PAGE_TITLE} </span>
        <!-- END: empty_page_title -->
    </div>
    {THEME_ERROR_INFO}
    {MODULE_CONTENT}
</div>
{FILE "footer.tpl"}
<!-- END: main -->