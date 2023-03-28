<!-- BEGIN: main -->
<div id="nv-block-login" class="text-center">
    <button type="button" class="btn btn-primary btn-sm" data-toggle="loginForm">
        {GLANG.signin}
    </button>
    <!-- BEGIN: allowuserreg -->
    <a href="{USER_REGISTER}" class="btn btn-primary btn-sm">{GLANG.register}</a>
    <!-- END: allowuserreg -->
</div>
<!-- END: main -->

<!-- BEGIN: signed -->
<div class="content signed clearfix">
    <div class="nv-info" style="display:none"></div>
    <div class="userBlock">
        <div class="margin-bottom" style="display: flex;">
            <a class="margin-right-sm" title="{LANG.edituser}" href="{URL_MODULE}"><img src="{AVATA}" alt="{USER.full_name}" class="img-thumbnail bg-gainsboro" width="40" height="40" /></a>
            <span style="flex-grow: 1">
                <small class="lev-{LEVEL}">{WELCOME}</small><br />
                <strong>{USER.full_name}</strong>
            </span>
        </div>
        <ul class="nv-list-item sm margin-bottom-lg">
            <li>
                <i class="fa fa-caret-right margin-right-sm" aria-hidden="true"></i><a href="{URL_MODULE}">{LANG.user_info}</a>
            </li>
            <li>
                <i class="fa fa-caret-right margin-right-sm" aria-hidden="true"></i><a href="{URL_HREF}editinfo">{LANG.editinfo}</a>
            </li>
            <!-- BEGIN: allowopenid -->
            <li>
                <i class="fa fa-caret-right margin-right-sm" aria-hidden="true"></i><a href="{URL_HREF}editinfo/openid">{LANG.openid_administrator}</a>
            </li>
            <!-- END: allowopenid -->
            <!-- BEGIN: myapis -->
            <li>
                <i class="fa fa-caret-right margin-right-sm" aria-hidden="true"></i><a href="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}=myapi">{GLANG.myapis}</a>
            </li>
            <!-- END: myapis -->
        </ul>
        <!-- BEGIN: admintoolbar -->
        <div class="margin-top boder-top padding-top margin-bottom-lg">
            <p class="margin-bottom-sm"><strong>{GLANG.for_admin}</strong></p>
            <ul class="nv-list-item sm">
                <li>
                    <em class="fa fa-cog fa-horizon margin-right-sm"></em><a href="{NV_BASE_SITEURL}{NV_ADMINDIR}/index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}" title="{GLANG.admin_page}"><span>{GLANG.admin_page}</span></a>
                </li>
                <!-- BEGIN: is_modadmin -->
                <li>
                    <em class="fa fa-key fa-horizon margin-right-sm"></em><a href="{URL_ADMINMODULE}" title="{GLANG.admin_module_sector} {MODULENAME}"><span>{GLANG.admin_module_sector} {MODULENAME}</span></a>
                </li>
                <!-- END: is_modadmin -->
                <!-- BEGIN: is_spadadmin -->
                <li>
                    <em class="fa fa-arrows fa-horizon margin-right-sm"></em><a href="{URL_DBLOCK}" title="{LANG_DBLOCK}"><span>{LANG_DBLOCK}</span></a>
                </li>
                <!-- END: is_spadadmin -->
                <li>
                    <em class="fa fa-user fa-horizon margin-right-sm"></em><a href="{URL_AUTHOR}" title="{GLANG.admin_view}"><span>{GLANG.admin_view}</span></a>
                </li>
            </ul>
        </div>
        <!-- END: admintoolbar -->
        <div style="display:flex;align-items:center">
            <div class="small" title="{GLANG.current_login}">
                <em class="icon-enter margin-right-sm"></em>{USER.current_login_txt}
            </div>
            <div class="text-right" style="flex-grow: 1">
                <button type="button" class="btn btn-default btn-sm active" data-toggle="{URL_LOGOUT}">
                    <em class="icon-exit"></em>&nbsp;{GLANG.logout}&nbsp;
                </button>
            </div>
        </div>
    </div>
</div>
<!-- END: signed -->