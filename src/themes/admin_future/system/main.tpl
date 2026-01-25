{include file='header.tpl'}
<header class="header-outer border-bottom">
    <div class="header-inner d-flex">
        <div class="site-brand text-center ms-2 ms-md-0">
            <a class="logo" href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}">
                <img src="{$smarty.const.NV_BASE_SITEURL}themes/{$ADMIN_INFO.admin_theme}/images/logo.png" alt="{$GCONFIG.site_name}">
            </a>
            <a class="logo-sm" href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}">
                <img src="{$smarty.const.NV_BASE_SITEURL}themes/{$ADMIN_INFO.admin_theme}/images/logo-sm.png" alt="{$GCONFIG.site_name}">
            </a>
        </div>
        <div class="site-header flex-grow-1 flex-shrink-1 d-flex align-items-center justify-content-between px-2 px-sm-4">
            <div class="header-left">
                <a href="#" class="left-sidebar-toggle fs-4" data-toggle="left-sidebar" aria-label="{$LANG->getGlobal('toggle_left_sidebar')}" title="{$LANG->getGlobal('toggle_left_sidebar')}"><i class="fas fa-bars ico-vc"></i></a>
            </div>
            <div class="header-right d-flex position-relative ms-auto">
                <nav class="main-icons">
                    <ul class="d-flex list-unstyled my-0 ms-0 me-3">
                        <li>
                            <a title="{$LANG->getGlobal('go_clientsector')}" aria-label="{$LANG->getGlobal('go_clientsector')}" href="{$smarty.const.NV_BASE_SITEURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={if empty($SITE_MODS)}{$smarty.const.NV_LANG_DATA}{else}{$GCONFIG.site_lang}{/if}" class="fs-3"><i class="fas fa-home ico-vc"></i></a>
                        </li>
                        {if not empty($GCONFIG.notification_active) and !($MODULE_NAME eq 'siteinfo' and $OP eq 'notification')}
                        <li class="dropdown-center site-noti" id="main-notifications" data-enable="true">
                            <a title="{$LANG->getGlobal('site_info')}" aria-label="{$LANG->getGlobal('site_info')}" href="#" class="fs-3" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false" data-bs-offset="0,11"><i class="fas fa-bell ico-vc"></i><span class="indicator"></span></a>
                            <div class="dropdown-menu dropdown-menu-end pb-0">
                                <div class="noti-heading text-center border-bottom pb-2 fw-medium">
                                    {$LANG->getGlobal('inform_unread')} <span class="badge rounded-pill text-bg-info" data-count="0">..</span>
                                </div>
                                <div class="noti-body site-notis position-relative">
                                    <div class="position-relative noti-lists">
                                        <div class="noti-lists-inner">
                                        </div>
                                    </div>
                                    <div class="loader position-absolute bottom-0 start-50 translate-middle-x d-none"><i class="fa-solid fa-spinner fa-spin-pulse"></i></div>
                                </div>
                                <div class="noti-footer border-top d-flex flex-nowrap">
                                    <a class="w-50 fw-medium border-end text-center text-truncate p-2" href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}=siteinfo&amp;{$smarty.const.NV_OP_VARIABLE}=notification">{$LANG->getGlobal('view_all')}</a>
                                    <a class="w-50 fw-medium text-center text-truncate p-2 markall" href="#">{$LANG->getGlobal('mark_read_all')}</a>
                                </div>
                            </div>
                        </li>
                        {/if}
                        <li class="menu-sys" id="menu-sys">
                            <a title="{$LANG->getGlobal('sys_mods')}" aria-label="{$LANG->getGlobal('sys_mods')}" href="#" class="fs-3" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false" data-bs-display="static"><i class="fas fa-th ico-vc"></i></a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <div class="menu-sys-inner position-relative">
                                    <div class="menu-sys-items">
                                        <div class="row">
                                            {foreach from=$ADMIN_MODS key=mname item=mvalue}
                                            {if not empty($mvalue.custom_title)}
                                            {assign var=submenu value=submenu($mname) nocache}
                                            <div class="col-md-3 col-sm-6">
                                                <ul class="list-unstyled mb-4">
                                                    <li class="fs-4 fw-medium mb-2 border-bottom pb-1"><a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$mname}">{$mvalue.custom_title}</a></li>
                                                    {foreach from=$submenu key=mop item=mopname}
                                                    <li class="mb-1"><a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$mname}&amp;{$smarty.const.NV_OP_VARIABLE}={$mop}">{$mopname}</a></li>
                                                    {/foreach}
                                                </ul>
                                            </div>
                                            {/if}
                                            {/foreach}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <a title="{$LANG->getGlobal('theme_settings')}" aria-label="{$LANG->getGlobal('theme_settings')}" href="#" class="fs-3" data-toggle="right-sidebar"><i class="fas fa-cog ico-vc"></i></a title="{$LANG->getGlobal('sys_mods')}">
                        </li>
                    </ul>
                </nav>
                <div class="admin-info">
                    <a title="{$LANG->getGlobal('admin_account')}" aria-label="{$LANG->getGlobal('admin_account')}" href="#" class="admin-icon" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false" data-bs-display="static">
                        <span>
                            {if not empty($ADMIN_INFO.avata)}
                            <img alt="{$ADMIN_INFO.full_name}" src="{$ADMIN_INFO.avata}">
                            {elseif not empty($ADMIN_INFO.photo)}
                            <img alt="{$ADMIN_INFO.full_name}" src="{$smarty.const.NV_BASE_SITEURL}{$ADMIN_INFO.photo}">
                            {else}
                            <i class="fa-solid fa-circle-user ico-vc"></i>
                            {/if}
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <li class="px-2">
                            <div class="fw-medium fs-3 mb-2">{$ADMIN_INFO.full_name}</div>
                            <img alt="{$ADMIN_INFO.username}" src="{$smarty.const.NV_BASE_SITEURL}themes/{$ADMIN_INFO.admin_theme}/images/admin{$ADMIN_INFO.level}.png"> {$ADMIN_INFO.username}
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li class="px-2">
                            <i class="fa fa-caret-right fa-fw"></i> {$LANG->getGlobal('hello_admin2', date('H:i d/m/Y', $ADMIN_INFO.current_login), $ADMIN_INFO.current_ip)}
                        </li>
                        {if not empty($GCONFIG.admin_login_duration)}
                        <li><hr class="dropdown-divider"></li>
                        <li class="px-2">
                            <i class="fa fa-globe fa-spin fa-fw"></i> {$LANG->getGlobal('login_session_expire')} <span id="countdown" data-duration="{($ADMIN_INFO.current_login + $GCONFIG.admin_login_duration - $smarty.const.NV_CURRENTTIME) * 1000}"></span>
                        </li>
                        {/if}
                        {if not empty($ADMIN_INFO.last_login)}
                        <li><hr class="dropdown-divider"></li>
                        <li class="px-2">
                            <i class="fa fa-caret-right fa-fw"></i> {$LANG->getGlobal('hello_admin1', date('H:i d/m/Y', $ADMIN_INFO.last_login), $ADMIN_INFO.last_ip)}
                        </li>
                        {/if}
                        <li><hr class="dropdown-divider"></li>
                        <li class="px-2">
                            <a href="{$smarty.const.NV_BASE_SITEURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}=users">
                                <i class="fa fa-arrow-circle-right fa-fw"></i> {$LANG->getGlobal('account_settings')}
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li class="px-2">
                            <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}=authors&amp;id={$ADMIN_INFO.admin_id}">
                                <i class="fa fa-arrow-circle-right fa-fw"></i> {$LANG->getGlobal('your_admin_account')}
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li class="px-2">
                            <a href="#" data-toggle="admin-logout">
                                <i class="fa fa-power-off text-danger"></i> {$LANG->getGlobal('admin_logout_title')}
                            </a>
                        </li>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<nav class="left-sidebar border-end" id="left-sidebar">
    <div class="left-sidebar-wrapper">
        <div class="left-sidebar-in-sm border-bottom">
            <div class="d-flex mx-2 mx-sm-4 align-items-center fs-3">
                <div class="me-auto text-truncate fw-medium">
                    {$PAGE_TITLE}
                </div>
                <div class="ms-3">
                    <a href="#" data-toggle="left-sidebar-sm"><i class="fa-solid fa-bars"></i></a>
                </div>
                {if not empty($BREADCRUMBS) or isset($HELP_URLS[$OP]) or (isset($SITE_MODS[$MODULE_NAME]) and not empty($SITE_MODS[$MODULE_NAME].main_file))}
                <div class="ms-3">
                    <a href="#" data-toggle="breadcrumb"><i class="fa-solid fa-square-caret-down"></i></a>
                </div>
                {/if}
            </div>
        </div>
        <div class="left-sidebar-spacer">
            <div class="left-sidebar-scroll">
                <div class="left-sidebar-content">
                    <ul class="sidebar-elements">
                        {if !empty($SELECT_OPTIONS)}
                        <li class="parent open">
                            <a href="#"><i class="fas fa-hand-pointer icon" title="{$LANG->get('please_select')}" data-bs-trigger="hover" data-bs-placement="right"></i><span>{$LANG->get('please_select')}</span><span class="toggle"><i class="fas"></i></span></a>
                            <ul class="sub-menu">
                                <li class="title">{$LANG->get('please_select')}</li>
                                <li class="nav-items">
                                    <div class="nv-left-sidebar-scroller">
                                        <div class="content">
                                            <ul>
                                                {foreach from=$SELECT_OPTIONS key=seloptlink item=selopttitle}
                                                <li><a href="{$seloptlink}" title="{$selopttitle}"><span>{$selopttitle}</span></a></li>
                                                {/foreach}
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        <li>
                        {/if}
                        {if !empty($MOD_CURRENT)}
                        <li class="divider">{$LANG->get('interface_current_menu')}</li>
                        <li class="{if !empty($MOD_CURRENT['subs'])}parent {/if}active{if empty($CONFIG_THEME['collapsed_leftsidebar'])} open{/if}">
                            <a href="{$MOD_CURRENT.link}" title="{$MOD_CURRENT.title}"><i class="{$MOD_CURRENT.icon} icon" title="{$MOD_CURRENT.title}" data-bs-trigger="hover" data-bs-placement="right"></i><span>{$MOD_CURRENT.title}</span>{if !empty($MOD_CURRENT['subs'])}<span class="toggle"><i class="fas"></i></span>{/if}</a>
                            {if !empty($MOD_CURRENT['subs'])}
                            <ul class="sub-menu">
                                <li class="title">{$MOD_CURRENT.title}</li>
                                <li class="nav-items">
                                    <div class="nv-left-sidebar-scroller">
                                        <div class="content">
                                            <ul>
                                                <li class="f-link{if $MOD_CURRENT['active']} active{/if}" title="{$LANG->get('Home')}"><a href="{$MOD_CURRENT.link}">{$LANG->get('Home')}</a></li>
                                                {foreach from=$MOD_CURRENT['subs'] item=crrsub}
                                                {if not empty($crrsub['subs'])}
                                                <li class="parent{if $crrsub['active']} active{/if}{if $crrsub['open']} open{/if}">
                                                    <a href="{$crrsub.link}" title="{$crrsub.title}"><span>{$crrsub.title}</span><span class="toggle"><i class="fas"></i></span></a>
                                                    <ul class="sub-menu">
                                                        {foreach from=$crrsub['subs'] item=crrsublv2}
                                                        <li{if $crrsublv2['active']} class="active"{/if}><a href="{$crrsublv2.link}" title="{$crrsublv2.title}"><span>{$crrsublv2.title}</span></a></li>
                                                        {/foreach}
                                                    </ul>
                                                </li>
                                                {else}
                                                <li{if $crrsub['active']} class="active"{/if}><a href="{$crrsub.link}" title="{$crrsub.title}"><span>{$crrsub.title}</span></a></li>
                                                {/if}
                                                {/foreach}
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                            {/if}
                        </li>
                        {/if}
                        {if !empty($MOD_MENU)}
                        <li class="divider">{$LANG->get('interface_other_menu')}</li>
                        {foreach from=$MOD_MENU item=rowmenu}
                        <li{if !empty($rowmenu['subs'])} class="parent"{/if}>
                            <a href="{$rowmenu.link}" title="{$rowmenu.title}"><i class="{$rowmenu.icon} icon" title="{$rowmenu.title}" data-bs-trigger="hover" data-bs-placement="right"></i><span>{$rowmenu.title}</span>{if !empty($rowmenu['subs'])}<span class="toggle"><i class="fas"></i></span>{/if}</a>
                            {if !empty($rowmenu['subs'])}
                            <ul class="sub-menu">
                                <li class="title">{$rowmenu.title}</li>
                                <li class="nav-items">
                                    <div class="nv-left-sidebar-scroller">
                                        <div class="content">
                                            <ul>
                                                <li class="f-link"><a href="{$rowmenu.link}">{$LANG->get('Home')}</a></li>
                                                {foreach from=$rowmenu['subs'] item=smenutitle key=smenukey}
                                                {if is_array($smenutitle)}
                                                <li class="parent">
                                                    <a href="{$NV_BASE_ADMINURL}index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&amp;{$NV_NAME_VARIABLE}={$rowmenu.name}&amp;{$NV_OP_VARIABLE}={$smenukey}" title="{$smenutitle.title}"><span>{$smenutitle.title}</span><span class="toggle"><i class="fas"></i></span></a>
                                                    <ul class="sub-menu">
                                                        {foreach from=$smenutitle.submenu item=sublv2 key=keysublv2}
                                                        <li><a href="{$NV_BASE_ADMINURL}index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&amp;{$NV_NAME_VARIABLE}={$rowmenu.name}&amp;{$NV_OP_VARIABLE}={$keysublv2}" title="{$sublv2}"><span>{$sublv2}</span></a></li>
                                                        {/foreach}
                                                    </ul>
                                                </li>
                                                {else}
                                                <li><a href="{$NV_BASE_ADMINURL}index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&amp;{$NV_NAME_VARIABLE}={$rowmenu.name}&amp;{$NV_OP_VARIABLE}={$smenukey}" title="{$smenutitle}">{$smenutitle}</a></li>
                                                {/if}
                                                {/foreach}
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                            {/if}
                        </li>
                        {/foreach}
                        {/if}
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>
<div class="body">
    <section class="main-content">
        <div class="breadcrumb-wrap px-4 d-flex align-items-center justify-content-between">
            {if empty($BREADCRUMBS)}
            <h1 class="h3 page-title mb-0 text-truncate" title="{$PAGE_TITLE}">{$PAGE_TITLE}</h1>
            {else}
            <nav aria-label="breadcrumb" class="site-breadcrumb pe-1" id="breadcrumb">
                <ol class="breadcrumb flex-nowrap mb-0">
                    {foreach from=$BREADCRUMBS item=brcrb}
                    <li class="breadcrumb-item fw-medium{if not empty($brcrb.active)}" aria-current="page"{else}"{/if}>
                        {if not empty($brcrb.link)}
                        <a href="{$brcrb.link}">{$brcrb.title}</a>
                        {else}
                        {$brcrb.title}
                        {/if}
                    </li>
                    {/foreach}
                    <li class="breadcrumb-dropdown d-none ps-2">
                        <a href="#" data-toggle="popover" data-bs-content="&nbsp;" data-bs-custom-class="breadcrumb-popover" data-bs-html="true"><i class="fa-solid fa-circle-chevron-down"></i></a>
                    </li>
                </ol>
            </nav>
            {/if}
            <div class="go-clients d-flex align-items-center" id="go-clients">
                {if isset($HELP_URLS[$OP])}
                <div class="ms-3">
                    <a href="{$HELP_URLS[$OP]}" title="{$LANG->getGlobal('go_instrucion')}" target="_blank" data-bs-toggle="tooltip"><i class="fa-solid fa-book fa-lg"></i></a>
                </div>
                {/if}
                {if isset($SITE_MODS[$MODULE_NAME]) and not empty($SITE_MODS[$MODULE_NAME].main_file)}
                <div class="ms-3">
                    <a href="{$smarty.const.NV_BASE_SITEURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}" title="{$LANG->getGlobal('go_clientmod')}" target="_blank" data-bs-toggle="tooltip"><i class="fa-solid fa-globe fa-lg"></i></a>
                </div>
                {/if}
            </div>
        </div>
        <div class="p-4">
            {$MODULE_CONTENT}
        </div>
    </section>
</div>
<aside class="right-sidebar border-start" id="right-sidebar">
    <div class="right-sidebar-inner">
        <div class="px-3">
            {if not empty($LANG_ADMIN)}
            <div class="mb-4">
                <div class="fw-medium border-bottom pb-2 mb-2">{$LANG->getGlobal('langinterface')}</div>
                {foreach from=$LANG_ADMIN key=lang item=langname}
                <div class="form-check mb-1">
                    <input class="form-check-input" type="radio" id="langinterface-{$lang}" value="{$lang}" name="gsitelanginterface"{if $lang eq $smarty.const.NV_LANG_INTERFACE} checked="checked"{/if}>
                    <label class="form-check-label" for="langinterface-{$lang}">{$langname}</label>
                </div>
                {/foreach}
            </div>
            <div class="mb-4">
                <div class="fw-medium border-bottom pb-2 mb-3">{$LANG->getGlobal('langdata')}</div>
                {foreach from=$LANG_ADMIN key=lang item=langname}
                <div class="form-check mb-1">
                    <input class="form-check-input" type="radio" id="langdata-{$lang}" value="{$lang}" name="gsitelangdata"{if $lang eq $smarty.const.NV_LANG_DATA} checked="checked"{/if}>
                    <label class="form-check-label" for="langdata-{$lang}">{$langname}</label>
                </div>
                {/foreach}
            </div>
            {/if}
            <div class="mb-4 color-mode" id="site-color-mode" data-busy="0">
                <div class="fw-medium border-bottom pb-2 mb-3">{$LANG->getGlobal('color_mode')}</div>
                <div class="mb-2">
                    <a href="#" class="d-block{if $TCONFIG.color_mode eq 'light'} active{/if}" data-mode="light"><i class="fa-solid fa-sun fa-fw" data-icon="fa-sun"></i> {$LANG->getGlobal('color_mode_light')}</a>
                </div>
                <div class="mb-2">
                    <a href="#" class="d-block{if $TCONFIG.color_mode eq 'dark'} active{/if}" data-mode="dark"><i class="fa-solid fa-moon fa-fw" data-icon="fa-moon"></i> {$LANG->getGlobal('color_mode_dark')}</a>
                </div>
                <div class="mb-2">
                    <a href="#" class="d-block{if $TCONFIG.color_mode eq 'auto'} active{/if}" data-mode="auto"><i class="fa-solid fa-circle-half-stroke fa-fw" data-icon="fa-circle-half-stroke"></i> {$LANG->getGlobal('color_mode_auto')}</a>
                </div>
            </div>
            <div class="mb-4 color-mode" id="site-text-direction" data-busy="0">
                <div class="fw-medium border-bottom pb-2 mb-3">{$LANG->getGlobal('text_direction')}</div>
                <div class="mb-2">
                    <div class="row">
                        <div class="col-6">
                            <input type="radio" name="g_themedir" value="ltr" class="btn-check" id="theme-dir-ltr" autocomplete="off"{if $TCONFIG.dir eq 'ltr'} checked="checked"{/if}>
                            <label class="btn btn-outline-primary d-block" for="theme-dir-ltr"><i class="fa-solid fa-align-left" data-icon="fa-align-left"></i> {$LANG->getGlobal('text_direction_ltr')}</label>
                        </div>
                        <div class="col-6">
                            <input type="radio" name="g_themedir" value="rtl" class="btn-check" id="theme-dir-rtl" autocomplete="off"{if $TCONFIG.dir eq 'rtl'} checked="checked"{/if}>
                            <label class="btn btn-outline-primary d-block" for="theme-dir-rtl"><i class="fa-solid fa-align-right" data-icon="fa-align-right"></i> {$LANG->getGlobal('text_direction_rtl')}</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</aside>
<footer class="site-footer border-top px-4 d-flex align-items-center justify-content-between">
    <div class="site-copyright text-truncate me-3">
        {if $smarty.const.NV_IS_SPADMIN and $ADMIN_INFO.level eq 1}
        <div class="memory-time-usage text-truncate" title="[MEMORY_TIME_USAGE]">[MEMORY_TIME_USAGE]</div>
        {/if}
        <div class="fw-medium text-truncate" title="{$LANG->getGlobal('copyright', $GCONFIG.site_name)}">{$LANG->getGlobal('copyright', $GCONFIG.site_name)}</div>
    </div>
    <div class="img-stat">
        <a title="NUKEVIET CMS" href="https://nukeviet.vn" target="_blank"><img alt="NUKEVIET CMS" src="{$smarty.const.NV_BASE_SITEURL}{$smarty.const.NV_ASSETS_DIR}/images/banner_nukeviet_88x15.jpg" width="88" height="15" class="imgstatnkv"></a>
    </div>
</footer>
<div id="admin-session-timeout" class="nv-offcanvas text-bg-warning p-3">
    {$LANG->getGlobal('timeoutsess_nouser')}, <a data-toggle="cancel" href="#">{$LANG->getGlobal('timeoutsess_click')}</a>. {$LANG->getGlobal('timeoutsess_timeout')}: <span data-toggle="sec"> 60 </span> {$LANG->getGlobal('sec')}
</div>
{include file='footer.tpl'}
