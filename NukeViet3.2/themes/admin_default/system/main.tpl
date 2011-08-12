<!-- BEGIN: main -->
{FILE "header.tpl"}

<div id="outer">
    <div id="header">
        <div class="logo">
            <a title="{NV_SITE_NAME}" href="{NV_BASE_SITEURL}{NV_ADMINDIR}/index.php"><img alt="{NV_SITE_NAME}" title="{SITE_NAME}" src="{NV_BASE_SITEURL}themes/{NV_ADMIN_THEME}/images/logo_small.png" width="240" height="50" /></a>
        </div><!-- BEGIN: langdata -->
        <div class="lang">
            <strong>{NV_LANGDATA}</strong>: 
            <select id="lang" onchange="top.location.href=this.options[this.selectedIndex].value;return;">
                <!-- BEGIN: option --><option value="{LANGOP}"{SELECTED}>{LANGVALUE} </option>
                <!-- END: option -->
            </select>
        </div>
        <!-- END: langdata -->
        <div class="logout">
            <a class="button1" href="{NV_GO_CLIENTSECTOR_URL}"><span><span>{NV_GO_CLIENTSECTOR}</span></span></a>
            <a class="button1" href="javascript:void(0);" onclick="nv_admin_logout();"><span><span>{NV_LOGOUT}</span></span></a>
        </div>
    </div>
    <!-- BEGIN: top_menu -->
    <div id="topmenu">
        <ul>
            <!-- BEGIN: top_menu_loop -->
            <li {TOP_MENU_CURRENT}>
                <a href="{NV_BASE_SITEURL}{NV_ADMINDIR}/index.php?{NV_NAME_VARIABLE}={TOP_MENU_HREF}"><span><strong>&bull;</strong>{TOP_MENU_NAME}</span></a>
            </li>
            <!-- END: top_menu_loop -->
        </ul>
        <div class="right">
        </div>
        <div class="clear">
        </div>
    </div>
    <!-- END: top_menu -->
    <div id="top_message">
        <div class="clock_container">
            <div class="clock">
                <label>
                    <span id="digclock">{NV_DIGCLOCK}</span>
                </label>
            </div>
        </div>
        <div class="info">
            <!-- BEGIN: hello_admin -->
            {HELLO_ADMIN1} <!-- END: hello_admin -->
            <!-- BEGIN: hello_admin3 -->
            {HELLO_ADMIN3} <!-- END: hello_admin3 -->
            <!-- BEGIN: hello_admin2 -->
            {HELLO_ADMIN2} <!-- END: hello_admin2 -->
        </div>
        <div class="clear">
        </div>
    </div>
            	
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
	    <div class="clear"></div>
	</div>
	
    <div id="footer" class="clearfix">
        <div class="copyright">
            {NV_DB_NUM_QUERIES}: {COUNT_QUERY_STRS}/{NV_TOTAL_TIME} <a href="#queries" onclick="nv_show_hidden('div_hide',2);">{NV_SHOW_QUERIES}</a>
            <br/>
            <strong>{NV_COPYRIGHT}</strong>
        </div>
        <div class="imgstat">
            <a title="NUKEVIET CMS" href="http://nukeviet.vn" target="_blank"><img alt="NUKEVIET CMS" title="NUKEVIET CMS" src="{NV_BASE_SITEURL}images/banner_nukeviet_88x15.jpg" width="88" height="15" /></a>
            <br/>
        </div>
    </div>
    <div id="div_hide" style="visibility:hidden;display:none;">
        <!-- BEGIN: nv_show_queries --><a name="queries"></a>
        <table summary="{NV_SHOW_QUERIES}" class="tab1">
            <caption>
                {NV_SHOW_QUERIES}
            </caption>
            <col width="16" /><!-- BEGIN: nv_show_queries_loop -->
            <tbody {NV_SHOW_QUERIES_CLASS}>
                <tr>
                    <td>
                        {NV_FIELD1}
                    </td>
                    <td>
                        {NV_FIELD}
                    </td>
                </tr>
            </tbody>
            <!-- END: nv_show_queries_loop -->
        </table>
        <br/>
        <br/>
        <!-- END: nv_show_queries -->
    </div>
</div>
<script type="text/javascript">
    nv_DigitalClock('digclock');
</script>

{FILE "footer.tpl"}
<!-- END: main -->