<!-- BEGIN: main -->
<div class="row centered margin-top-lg margin-bottom-lg">
<div class="col-md-13" style="min-width:300px">
    <div class="page panel panel-default box-shadow<!-- BEGIN: not_redirect --> bg-lavender<!-- END: not_redirect -->">
        <div class="panel-body">
            <!-- BEGIN: redirect2 -->
            <div class="text-center margin-bottom-lg">
                <!-- BEGIN: image -->
                <a title="{SITE_NAME}" href="{THEME_SITE_HREF}"><img src="{LOGO_SRC}" width="{LOGO_WIDTH}" height="{LOGO_HEIGHT}" alt="{SITE_NAME}" /></a>
                <!-- END: image -->
                <!-- BEGIN: swf -->
                <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="{LOGO_WIDTH}" height="{LOGO_HEIGHT}" >
       	            <param name="wmode" value="transparent" />
                   	<param name="movie" value="{LOGO_SRC}" />
                   	<param name="quality" value="high" />
                   	<param name="menu" value="false" />
                   	<param name="seamlesstabbing" value="false" />
                   	<param name="allowscriptaccess" value="samedomain" />
                   	<param name="loop" value="true" />
                   	<!--[if !IE]> <-->
                   	<object type="application/x-shockwave-flash" width="{LOGO_WIDTH}" height="{LOGO_HEIGHT}" data="{LOGO_SRC}" >
                        <param name="wmode" value="transparent" />
                        <param name="pluginurl" value="http://www.adobe.com/go/getflashplayer" />
                        <param name="loop" value="true" />
                        <param name="quality" value="high" />
                        <param name="menu" value="false" />
                        <param name="seamlesstabbing" value="false" />
                        <param name="allowscriptaccess" value="samedomain" />
               	    </object>
                    <!--> <![endif]-->
                </object>
                <!-- END: swf -->
            </div>
            <!-- END: redirect2 -->
            <h2 class="text-center margin-bottom-lg">{LANG.register}</h2>
            {FILE "register_form.tpl"}
            <div class="margin-top-lg">
                <ul class="users-menu nav navbar-nav">
                    <!-- BEGIN: navbar --><li><a href="{NAVBAR.href}"><em class="fa fa-caret-right margin-right-sm"></em>{NAVBAR.title}</a></li><!-- END: navbar -->
                </ul>
            </div>
        </div>
    </div>
</div>
</div>
<!-- BEGIN: datepicker -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/ui/jquery.ui.core.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/ui/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/ui/jquery.ui.datepicker.css" rel="stylesheet" />
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/ui/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/ui/jquery.ui.datepicker.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<!-- END: datepicker -->
<!-- END: main -->