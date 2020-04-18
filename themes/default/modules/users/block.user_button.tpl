<!-- BEGIN: main -->
<span><a title="{GLANG.signin} - {GLANG.register}" class="pa pointer button" data-toggle="tip" data-target="#guestBlock_{BLOCKID}" data-click="y" data-callback="recaptchareset"><em class="fa fa-user fa-lg"></em><span class="hidden">{GLANG.signin}</span></a></span>
<!-- START FORFOOTER -->
<div id="guestBlock_{BLOCKID}" class="hidden">
    <div class="guestBlock">
        <h3><a href="#" onclick="switchTab(this);tipAutoClose(true);" class="guest-sign pointer margin-right current" data-switch=".log-area, .reg-area" data-obj=".guestBlock">{GLANG.signin}</a> <!-- BEGIN: allowuserreg2 --><a href="#" onclick="switchTab(this);tipAutoClose(false);" class="guest-reg pointer" data-switch=".reg-area, .log-area" data-obj=".guestBlock">{GLANG.register}</a> <!-- END: allowuserreg2 --></h3>
        <div class="log-area">
            {FILE "login_form.tpl"}
            <div class="text-center margin-top-lg" id="other_form">
                <a href="{USER_LOSTPASS}">{GLANG.lostpass}?</a>
            </div>
        </div>
        <!-- BEGIN: allowuserreg -->
        <div class="reg-area hidden">
            {FILE "register_form.tpl"}
        </div>
        <!-- END: allowuserreg -->
    </div>
</div>
<!-- END FORFOOTER -->
<!-- BEGIN: datepicker -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<!-- END: datepicker -->
<script type="text/javascript" src="{NV_BASE_SITEURL}themes/{BLOCK_JS}/js/users.js"></script>
<!-- END: main -->

<!-- BEGIN: signed -->
<span><a title="{USER.full_name}" class="pointer button user" data-toggle="tip" data-target="#userBlock_{BLOCKID}" data-click="y" style="background-image:url({AVATA})"><span class="hidden">{USER.full_name}</span></a></span>
<!-- START FORFOOTER -->
<div id="userBlock_{BLOCKID}" class="hidden">
    <div class="nv-info" style="display:none"></div>
    <div class="userBlock clearfix">
    	<h3 class="text-center"><span class="lev-{LEVEL} text-normal">{WELCOME}:</span> {USER.full_name}</h3>
    	<div class="row">
    		<div class="col-xs-8 text-center">
    			<a title="{LANG.edituser}" href="#" onclick="changeAvatar('{URL_AVATAR}')"><img src="{AVATA}" alt="{USER.full_name}" class="img-thumbnail bg-gainsboro" /></a>
    		</div>
    		<div class="col-xs-16">
    		    <ul class="nv-list-item sm">
    		    	<li class="active"><a href="{URL_MODULE}">{LANG.user_info}</a></li>
    		    	<li><a href="{URL_HREF}editinfo">{LANG.editinfo}</a></li>
    		    	<!-- BEGIN: allowopenid --><li><a href="{URL_HREF}editinfo/openid">{LANG.openid_administrator}</a></li><!-- END: allowopenid -->
    		    	<!-- BEGIN: regroups --><li><a href="{URL_HREF}editinfo/group">{LANG.in_group}</a></li><!-- END: regroups -->
    		    </ul>
    		</div>
    	</div>
        <!-- BEGIN: admintoolbar -->
        <div class="margin-top boder-top padding-top">
            <p class="margin-bottom-sm"><strong>{GLANG.for_admin}</strong></p>
            <ul class="nv-list-item sm">
                <li><em class="fa fa-cog fa-horizon margin-right-sm"></em><a href="{NV_BASE_SITEURL}{NV_ADMINDIR}/index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}" title="{GLANG.admin_page}"><span>{GLANG.admin_page}</span></a></li>
                <!-- BEGIN: is_modadmin -->
        		<li><em class="fa fa-key fa-horizon margin-right-sm"></em><a href="{URL_ADMINMODULE}" title="{GLANG.admin_module_sector} {MODULENAME}"><span>{GLANG.admin_module_sector} {MODULENAME}</span></a></li>
        		<!-- END: is_modadmin -->
                <!-- BEGIN: is_spadadmin -->
        		<li><em class="fa fa-arrows fa-horizon margin-right-sm"></em><a href="{URL_DBLOCK}" title="{LANG_DBLOCK}"><span>{LANG_DBLOCK}</span></a></li>
        		<!-- END: is_spadadmin -->
                <li><em class="fa fa-user fa-horizon margin-right-sm"></em><a href="{URL_AUTHOR}" title="{GLANG.admin_view}"><span>{GLANG.admin_view}</span></a></li>
            </ul>
        </div>
        <!-- END: admintoolbar -->
    </div>
    <div class="tip-footer">
        <div class="row">
            <div class="col-xs-16 small">
                <em class="button btn-sm icon-enter" title="{LANG.current_login}"></em>{USER.current_login_txt}
            </div>
            <div class="col-xs-8 text-right">
                <button type="button" class="btn btn-default btn-sm active" onclick="{URL_LOGOUT}(this);"><em class="icon-exit"></em>&nbsp;{LANG.logout_title}&nbsp;</button>
            </div>
        </div>
    </div>
</div>
<!-- END FORFOOTER -->
<script src="{NV_BASE_SITEURL}themes/{BLOCK_JS}/js/users.js"></script>
<!-- END: signed -->