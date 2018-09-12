{include file='header.tpl'}
<body>
    <div class="wrapper main-fixed-sidebar{if not empty($CONFIG_THEME['collapsed_leftsidebar'])} main-collapsible-sidebar-collapsed{/if}">
        <header class="navbar navbar-expand fixed-top main-header">
            <nav class="container-fluid">
                <div class="main-navbar-header">
                    <a href="{$THEME_SITE_HREF}" class="logo"><img src="{$NV_BASE_SITEURL}themes/{$NV_ADMIN_THEME}/images/logo.png" alt="{$NV_SITE_NAME}"></a>
                    <a href="{$THEME_SITE_HREF}" class="logo-sm"><img src="{$NV_BASE_SITEURL}themes/{$NV_ADMIN_THEME}/images/logo-sm.png" alt="{$NV_SITE_NAME}"></a>
                </div>
                <div class="main-right-navbar">
                    <ul class="nav navbar-nav float-right main-user-nav">
                        <li class="nav-item dropdown item-avatar">
                            <a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle"><img src="{$ADMIN_PHOTO}" alt="{$ADMIN_USERNAME}"><span class="user-name">{$ADMIN_USERNAME}</span></a>
                            <div role="menu" class="dropdown-menu">
                                <div class="admin-info">
                                    <div class="admin-name">{$ADMIN_USERNAME}</div>
                                    <div class="admin-lev">
                                        {for $lev=1 to $ADMIN_LEV_LOOP}
                                        <i class="fas fa-star fa-fw"></i>
                                        {/for}
                                    </div>
                                    <div class="last-login">{$HELLO_ADMIN}</div>
                                </div>
                                <a href="{$ADMIN_LINK_INFO}" class="dropdown-item"><i class="icon fas fa-user-circle"></i>{$LANG->get('your_account')}</a>
                                <a href="javascript:void(0);" onclick="nv_admin_logout();" class="dropdown-item" title="{$LANG->get('admin_logout_title')}"><i class="icon fas fa-power-off"></i>{$LANG->get('logout')}</a>
                            </div>
                        </li>
                    </ul>
                    <div class="menu-toggle">
                        <a href="#" class="main-toggle-left-sidebar-btn"><i class="fas fa-bars"></i></a>
                    </div>
                    <ul class="nav navbar-nav float-right main-icons-nav">
                        <li class="nav-item dropdown">
                            <a href="{$NV_GO_CLIENTSECTOR_URL}" role="button" aria-expanded="false" class="nav-link" title="{$LANG->get('go_clientsector')}"><i class="fas fa-home"></i></a>
                        </li>
                        {if $NOTIFICATION_ACTIVE}
                        <li class="nav-item dropdown" id="main-notifications">
                            <a href="#" role="button" aria-expanded="false" class="nav-link icon-notifications" data-toggle="dropdown"><i class="fas fa-bell"></i><span class="indicator d-none"></span></a>
                            <ul class="dropdown-menu main-notifications">
                                <li>
                                    <div class="title">{$LANG->get('notification')}<span class="badge badge-pill"></span></div>
                                    <div class="list">
                                        <div class="nv-notification-scroller">
                                            <div class="content"></div>
                                        </div>
                                        <div class="loader text-center"><i class="fas fa-spinner fa-spin"></i></div>
                                    </div>
                                    <div class="footer"><a href="{$NV_GO_ALL_NOTIFICATION}">{$LANG->get('view_all')}</a></div>
                                </li>
                            </ul>
                        </li>
                        {/if}
                        <li class="nav-item dropdown">
                            <a href="#" role="button" aria-expanded="false" class="nav-link" data-toggle="dropdown"><i class="fas fa-th"></i></a>
                            <div class="dropdown-menu main-sys-menu">
                                <div class="list">
                                    <div class="nv-scroller">
                                        <div class="content">
                                            {foreach from=$SYS_MENU item=rowmenu}
                                            <div class="row">
                                                {foreach from=$rowmenu item=menu}
                                                <div class="col-md-3 col-sm-6">
                                                    <ul class="sys-menu">
                                                        <li class="lead"><a href="{$menu.link}">{$menu.title}</a></li>
                                                        {if $menu['issubs']}
                                                        {foreach from=$menu['subs'] item=smenu}
                                                        <li><a href="{$smenu.link}">{$smenu.title}</a></li>
                                                        {/foreach}
                                                        {/if}
                                                    </ul>
                                                </div>
                                                {/foreach}
                                            </div>
                                            {/foreach}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a href="#" role="button" aria-expanded="false" class="nav-link nv-toggle-right-sidebar"><i class="fas fa-cog"></i></a>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>

        <aside class="main-left-sidebar">
            <section class="left-sidebar-wrapper">
                <a href="#" class="left-sidebar-toggle">{$PAGE_TITLE}</a>
                <div class="left-sidebar-spacer">
                    <div class="left-sidebar-scroll">
                        <div class="left-sidebar-content">
                            <ul class="sidebar-elements">
                                {if !empty($SELECT_OPTIONS)}
                                <li class="parent open">
                                    <a href="#"><i class="fas fa-hand-pointer icon" title="{$LANG->get('please_select')}"></i><span>{$LANG->get('please_select')}</span><span class="toggle"><i class="fas"></i></span></a>
                                    <ul class="sub-menu">
                                        <li class="title">{$LANG->get('please_select')}</li>
                                        <li class="nav-items">
                                            <div class="nv-left-sidebar-scroller">
                                                <div class="content">
                                                    <ul>
                                                        {foreach from=$SELECT_OPTIONS key=seloptlink item=selopttitle}
                                                        <li><a href="{$seloptlink}"><span>{$selopttitle}</span></a></li>
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
                                <li class="{if !empty($MOD_CURRENT['subs'])}parent {/if}active{if not $CONFIG_THEME['collapsed_leftsidebar']} open{/if}">
                                    <a href="{$MOD_CURRENT.link}"><i class="{$MOD_CURRENT.icon} icon" title="{$MOD_CURRENT.title}"></i><span>{$MOD_CURRENT.title}</span>{if !empty($MOD_CURRENT['subs'])}<span class="toggle"><i class="fas"></i></span>{/if}</a>
                                    {if !empty($MOD_CURRENT['subs'])}
                                    <ul class="sub-menu">
                                        <li class="title">{$MOD_CURRENT.title}</li>
                                        <li class="nav-items">
                                            <div class="nv-left-sidebar-scroller">
                                                <div class="content">
                                                    <ul>
                                                        <li class="f-link{if $MOD_CURRENT['active']} active{/if}"><a href="{$MOD_CURRENT.link}">{$LANG->get('Home')}</a></li>
                                                        {foreach from=$MOD_CURRENT['subs'] item=crrsub}
                                                        {if not empty($crrsub['subs'])}
                                                        <li class="parent{if $crrsub['active']} active{/if}{if $crrsub['open']} open{/if}">
                                                            <a href="{$crrsub.link}"><span>{$crrsub.title}</span><span class="toggle"><i class="fas"></i></span></a>
                                                            <ul class="sub-menu">
                                                                {foreach from=$crrsub['subs'] item=crrsublv2}
                                                                <li{if $crrsublv2['active']} class="active"{/if}><a href="{$crrsublv2.link}"><span>{$crrsublv2.title}</span></a></li>
                                                                {/foreach}
                                                            </ul>
                                                        </li>
                                                        {else}
                                                        <li{if $crrsub['active']} class="active"{/if}><a href="{$crrsub.link}"><span>{$crrsub.title}</span></a></li>
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
                                    <a href="{$rowmenu.link}"><i class="{$rowmenu.icon} icon" title="{$rowmenu.title}"></i><span>{$rowmenu.title}</span>{if !empty($rowmenu['subs'])}<span class="toggle"><i class="fas"></i></span>{/if}</a>
                                    {if !empty($rowmenu['subs'])}
                                    <ul class="sub-menu">
                                        <li class="title">{$rowmenu.title}</li>
                                        <li class="nav-items">
                                            <div class="nv-left-sidebar-scroller">
                                                <div class="content">
                                                    <ul>
                                                        <li class="f-link"><a href="{$rowmenu.link}">{$LANG->get('Home')}</a></li>
                                                        {foreach from=$rowmenu['subs'] item=smenutitle key=smenukey}
                                                        <li><a href="{$NV_BASE_ADMINURL}index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&amp;{$NV_NAME_VARIABLE}={$rowmenu.name}&amp;{$NV_OP_VARIABLE}={$smenukey}">{$smenutitle}</a></li>
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
            </section>
        </aside>

        <div class="main-content-wrapper">
            <div class="main-content">
                <div class="page-head">
                    <div class="page-head-title">
                        {if not empty($BREADCRUMBS)}
                        <nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb page-head-nav">
                                {foreach from=$BREADCRUMBS item=row}
                                <li class="breadcrumb-item{if not empty($row['active'])} active{/if}">{if not empty($row['link']) and empty($row['active'])}<a href="{$row.link}">{$row.title}</a>{else}{$row.title}{/if}</li>
                                {/foreach}
                            </ol>
                        </nav>
                        {else}
                        <h2 title="{$PAGE_TITLE}">{$PAGE_TITLE}</h2>
                        {/if}
                    </div>
                    {if isset($NV_GO_CLIENTMOD) or isset($NV_URL_INSTRUCTION)}
                    <div class="module-btns">
                        {if isset($NV_URL_INSTRUCTION)}
                        <a target="_blank" href="{$NV_URL_INSTRUCTION}" title="{$LANG->get('go_instrucion')}"><i class="fas fa-book"></i></a>
                        {/if}
                        {if isset($NV_GO_CLIENTMOD)}
                        <a target="_blank" href="{$NV_GO_CLIENTMOD}" title="{$LANG->get('go_clientmod')}"><i class="fas fa-globe-asia"></i></a>
                        {/if}
                    </div>
                    {/if}
                </div>
                <div class="page-body">
                    {$THEME_ERROR_INFO}
                    {$MODULE_CONTENT}
                </div>
            </div>
            <div class="main-footer">
                <div class="stat-img">
                    <a title="NUKEVIET CMS" href="https://nukeviet.vn/" target="_blank"><img alt="NUKEVIET CMS" src="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/images/banner_nukeviet_88x15.jpg" class="imgstatnkv"></a>
                </div>
                <div class="memory-info">
                    {if $memory_time_usage}
                    [MEMORY_TIME_USAGE]
                    {/if}
                </div>
                <div class="copyright">
                    {$NV_COPYRIGHT}
                </div>
            </div>
        </div>

        <nav class="main-right-sidebar">
            <div class="sb-content">
                <div class="tab-navigation">
                    <ul class="nav nav-tabs nav-justified" role="tablist">
                        {if !empty($ARRAY_LANGS)}
                        <li class="nav-item" role="presentation"><a class="nav-link active" href="#main-admin-tab-languages" aria-controls="main-admin-tab-languages" role="tab" data-toggle="tab">{$LANG->get('mod_language')}</a></li>
                        {/if}
                        <li class="nav-item" role="presentation"><a class="nav-link" href="#main-admin-tab-settings" aria-controls="main-admin-tab-settings" role="tab" data-toggle="tab">{$LANG->get('theme_cfg')}</a></li>
                    </ul>
                </div>
                <div class="tab-panel">
                    <div class="tab-content">
                        {if !empty($ARRAY_LANGS)}
                        <div class="tab-pane tab-languages active" id="main-admin-tab-languages" role="tabpanel">
                            <div class="languages-wrapper">
                                <div class="nv-scroller">
                                    <div class="category-title">{$LANG->get('langinterface')}</div>
                                    <ul class="languages-list">
                                        {foreach from=$ARRAY_LANGS key=langkey item=langname}
                                        <li>
                                            <label class="custom-control custom-radio">
                                                <input class="custom-control-input nv-choose-lang" value="{$NV_BASE_ADMINURL}index.php?langinterface={$langkey}&amp;{$NV_LANG_VARIABLE}={$NV_LANG_DATA}" type="radio" name="mainlanginterface"{if $langkey eq $NV_LANG_INTERFACE} checked="checked"{/if}><span class="custom-control-label">{$langname}</span>
                                            </label>
                                        </li>
                                        {/foreach}
                                    </ul>
                                    <div class="category-title">{$LANG->get('langdata')}</div>
                                    <ul class="languages-list">
                                        {foreach from=$ARRAY_LANGS key=langkey item=langname}
                                        <li>
                                            <label class="custom-control custom-radio">
                                                <input class="custom-control-input nv-choose-lang" value="{$NV_BASE_ADMINURL}index.php?langinterface={$NV_LANG_INTERFACE}&amp;{$NV_LANG_VARIABLE}={$langkey}" type="radio" name="mainlangdata"{if $langkey eq $NV_LANG_DATA} checked="checked"{/if}><span class="custom-control-label">{$langname}</span>
                                            </label>
                                        </li>
                                        {/foreach}
                                    </ul>
                                </div>
                            </div>
                        </div>
                        {/if}
                        <div class="tab-pane tab-settings" id="main-admin-tab-settings" role="tabpanel" data-action="siteinfo">
                            <div class="settings-wrapper">
                                <div class="nv-scroller">
                                    <div class="category-title">{$LANG->get('theme_cfg_title')}</div>
                                    <ul class="settings-list">
                                        <li>
                                            <div class="switch-button switch-button-sm">
                                                <input type="checkbox"{if not empty($CONFIG_THEME['collapsed_leftsidebar'])} checked="checked"{/if} name="nv_collapsed_leftsidebar" id="nv_collapsed_leftsidebar"><span><label for="nv_collapsed_leftsidebar"></label></span>
                                            </div>
                                            <span class="name">{$LANG->get('theme_cfg_collapsed_leftsidebar')}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</body>
{include file='footer.tpl'}
