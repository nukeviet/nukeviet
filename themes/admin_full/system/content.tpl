<!-- BEGIN: main -->
{FILE "header.tpl"}

<div class="info_tab">
    <!-- BEGIN: empty_page_title -->
    <span class="cell_left">{PAGE_TITLE}</span>
    <!-- END: empty_page_title -->
    
    <!-- BEGIN: select_option -->
    <span class="cell_right">
        <select name="select_options" onchange="top.location.href=this.options[this.selectedIndex].value;return;">
            <option value="">{PLEASE_SELECT}</option>
            <!-- BEGIN: select_option_loop -->
            <option value="{SELECT_VALUE}">{SELECT_NAME}</option>
            <!-- END: select_option_loop -->
        </select>
    </span>
    <!-- END: select_option -->
    <!-- BEGIN: site_mods -->
    <span class="cell_right">
        <a href="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}">{NV_GO_CLIENTMOD}</a>
    </span>
    <!-- END: site_mods -->
    <div style="clear:both"></div>
</div>
<div id="middle_column_r">
    {THEME_ERROR_INFO}
    {MODULE_CONTENT}
    <div style="clear:both"></div>
</div>

{FILE "footer.tpl"}
<!-- END: main -->