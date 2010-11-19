<!-- BEGIN: main -->
	<!-- BEGIN: header --> 
	{FILE "header.tpl"}
	<!-- END: header -->
	<div id="middle_outer">
    	<table cellpadding="0" cellspacing="0" width="100%" border="0" class="table_full">
        <tr>
	    	<td width="200" valign="top" bgcolor="#ECF5FF">
            	<div id="middle_column_l">
                    <!-- BEGIN: vertical_menu -->
                    <div id="ver_menu">
                        <!-- BEGIN: vertical_menu_loop -->
                        <a {VERTICAL_MENU_CURRENT}  href="{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={VERTICAL_MENU_HREF}">{VERTICAL_MENU_NAME}  </a>
                        <!-- BEGIN: vertical_menu_sub_loop -->
                        <a {VERTICAL_MENU_SUB_CURRENT}  
                        href="{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={VERTICAL_MENU_SUB_HREF}&amp;{NV_OP_VARIABLE}={VERTICAL_MENU_SUB_HREF1}">
                        {VERTICAL_MENU_SUB_NAME}  </a>
                        <!-- END: vertical_menu_sub_loop -->
                        <!-- END: vertical_menu_loop -->
                    </div>
                    <!-- END: vertical_menu -->
	    		</div>
        	</td>
            <td valign="top">
            	<div class="info_tab">
                    <!-- BEGIN: empty_page_title -->
                    <span class="cell_left">{PAGE_TITLE}</span>
                    <!-- END: empty_page_title -->
                    
                    <!-- BEGIN: select_option -->
                    <div class="cell_right">
                        <select name="select_options" onchange="top.location.href=this.options[this.selectedIndex].value;return;">
                            <option value="">{PLEASE_SELECT}</option>
                            <!-- BEGIN: select_option_loop -->
                            <option value="{SELECT_VALUE}">{SELECT_NAME}</option>
                            <!-- END: select_option_loop -->
                        </select>
                    </div>
                    <!-- END: select_option -->
                    <!-- BEGIN: site_mods -->
                    <div class="cell_right">
                        <a href="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}">{NV_GO_CLIENTMOD}</a>
                    </div>
                    <!-- END: site_mods -->
                    <div style="clear:both"></div>
                </div>
	    		<div id="middle_column_r">
                    {THEME_ERROR_INFO}
                    {MODULE_CONTENT}
	    		</div>
        	</td>
        </tr>
	    </table>
	</div>
	<!-- BEGIN: footer -->
	{FILE "footer.tpl"}
	<!-- END: footer -->
<!-- END: main -->