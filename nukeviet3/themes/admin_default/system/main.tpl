<!-- BEGIN: main -->
	<!-- BEGIN: header --> 
	{FILE "header.tpl"}
	<!-- END: header -->
	<div id="middle_outer">
	    <div id="middle_column_l">
	        <!-- BEGIN: vertical_menu -->
	        <div id="ver_menu">
	            <!-- BEGIN: vertical_menu_loop --><a {VERTICAL_MENU_CURRENT}  href="{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={VERTICAL_MENU_HREF}">{VERTICAL_MENU_NAME}  </a>
	            <!-- BEGIN: vertical_menu_sub_loop --><a {VERTICAL_MENU_SUB_CURRENT}  href="{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={VERTICAL_MENU_SUB_HREF}&amp;{NV_OP_VARIABLE}={VERTICAL_MENU_SUB_HREF1}">{VERTICAL_MENU_SUB_NAME}  </a>
	            <!-- END: vertical_menu_sub_loop --><!-- END: vertical_menu_loop -->
	        </div>
	        <!-- END: vertical_menu -->
	    </div>
	    <div id="middle_column_r">
	        <div class="info">
	            <!-- BEGIN: select_option -->
	            <div class="go">
	                <select name="select_options" onchange="top.location.href=this.options[this.selectedIndex].value;return;">
	                    <option value="">{PLEASE_SELECT}</option>
	                    <!-- BEGIN: select_option_loop --><option value="{SELECT_VALUE}">{SELECT_NAME}</option>
	                    <!-- END: select_option_loop -->
	                </select>
	            </div>
	            <!-- END: select_option --><!-- BEGIN: site_mods -->
	            <div class="go">
	                <span>&bull;</span>
	                <a href="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}">{NV_GO_CLIENTMOD}</a>
	            </div>
	            <!-- END: site_mods --><!-- BEGIN: empty_page_title -->&raquo; <span>{PAGE_TITLE} </span>
	            <!-- END: empty_page_title -->
	        </div>
	        {THEME_ERROR_INFO}
	        {MODULE_CONTENT}
	    </div>
	    <div class="clear"></div>
	</div>
	<!-- BEGIN: footer -->
	{FILE "footer.tpl"}
	<!-- END: footer -->
<!-- END: main -->