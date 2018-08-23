<!doctype html>
<html lang="{$LANG->get('Content_Language')}" xmlns="http://www.w3.org/1999/xhtml" prefix="og: http://ogp.me/ns#">
    <head>
        <title>{$NV_SITE_TITLE}</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="{$SITE_DESCRIPTION}">
        <meta name="author" content="{$NV_SITE_COPYRIGHT}">
        <meta name="generator" content="{$NV_SITE_NAME}">
        <meta name="robots" content="noindex, nofollow">
        <link rel="shortcut icon" href="{$SITE_FAVICON}">
        <link rel="stylesheet" href="{$NV_BASE_SITEURL}themes/{$NV_ADMIN_THEME}/fonts/fonts.css">
        <link rel="stylesheet" href="{$NV_BASE_SITEURL}themes/{$NV_ADMIN_THEME}/libs/fontawesome/css/all.min.css">
        <link rel="stylesheet" href="{$NV_BASE_SITEURL}themes/{$NV_ADMIN_THEME}/libs/perfect-scrollbar/css/perfect-scrollbar.css">
        <link rel="stylesheet" href="{$NV_BASE_SITEURL}themes/{$NV_ADMIN_THEME}/libs/gritter/css/jquery.gritter.css">
        <link rel="stylesheet" href="{$NV_BASE_SITEURL}themes/{$NV_ADMIN_THEME}/css/nv.core.css">
        <link rel="stylesheet" href="{$NV_BASE_SITEURL}themes/{$NV_ADMIN_THEME}/css/nv.style.css">
        {if isset($NV_CSS_MODULE_THEME)}
        <link rel="stylesheet" href="{$NV_CSS_MODULE_THEME}" type="text/css">
        {/if}

        <script type="text/javascript">
        var  nv_base_siteurl = '{$NV_BASE_SITEURL}',
             nv_lang_data = '{$NV_LANG_DATA}',
             nv_lang_interface = '{$NV_LANG_INTERFACE}',
             nv_name_variable = '{$NV_NAME_VARIABLE}',
             nv_fc_variable = '{$NV_OP_VARIABLE}',
             nv_lang_variable = '{$NV_LANG_VARIABLE}',
             nv_module_name = '{$MODULE_NAME}',
             nv_my_ofs = {$NV_SITE_TIMEZONE_OFFSET},
             nv_my_abbr = '{$NV_CURRENTTIME}',
             nv_cookie_prefix = '{$NV_COOKIE_PREFIX}',
             nv_check_pass_mstime = '{$NV_CHECK_PASS_MSTIME}',
             nv_safemode = {$NV_SAFEMODE},
             nv_area_admin = 1;
        </script>
        <script type="text/javascript" src="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/language/{$NV_LANG_INTERFACE}.js"></script>
        <script type="text/javascript" src="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/global.js"></script>
        <script type="text/javascript" src="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/admin.js"></script>

        <script src="{$NV_BASE_SITEURL}themes/{$NV_ADMIN_THEME}/js/bootstrap.bundle.min.js"></script>
        <script src="{$NV_BASE_SITEURL}themes/{$NV_ADMIN_THEME}/libs/perfect-scrollbar/js/perfect-scrollbar.min.js"></script>
        <script src="{$NV_BASE_SITEURL}themes/{$NV_ADMIN_THEME}/libs/gritter/js/jquery.gritter.min.js"></script>

        {if isset($NV_JS_MODULE)}
        <script src="{$NV_JS_MODULE}"></script>
        {/if}

        <!--[if lt IE 9]>
        <script src="{$NV_BASE_SITEURL}themes/{$NV_ADMIN_THEME}/js/html5shiv.min.js"></script>
        <script src="{$NV_BASE_SITEURL}themes/{$NV_ADMIN_THEME}/js/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <div class="wrapper main-fixed-sidebar">
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
                            <li class="nav-item dropdown">
                                <a href="#" role="button" aria-expanded="false" class="nav-link" data-toggle="dropdown"><i class="fas fa-bell"></i><span class="indicator"></span></a>
                                <ul class="dropdown-menu main-notifications">
                                    <li>
                                        <div class="title">Thông báo<span class="badge badge-pill">4</span></div>
                                        <div class="list">
                                            <div class="nv-scroller">
                                                <div class="content">
                                                    <ul>
                                                        <li class="notification notification-unread">
                                                            <a href="#">
                                                                <div class="image"><img src="assets/images/avatar2.png" alt="Avatar"></div>
                                                                <div class="notification-info">
                                                                    <div class="text"><span class="user-name">vuthao</span> vừa gửi liên hệ với tiêu đề <strong>Thử nghiệm NukeViet</strong>.</div>
                                                                    <span class="date">Cách đây 2 phút</span>
                                                                </div>
                                                            </a>
                                                        </li>
                                                        <li class="notification">
                                                            <a href="#">
                                                                <div class="image"><img src="assets/images/avatar3.png" alt="Avatar"></div>
                                                                <div class="notification-info">
                                                                    <div class="text"><span class="user-name">thehung</span> đăng bài viết <strong>Phát triển NukeViet 5.0</strong> chờ duyệt.</div>
                                                                    <span class="date">Cách đây hơn 1 tiếng</span>
                                                                </div>
                                                            </a>
                                                        </li>
                                                        <li class="notification">
                                                            <a href="#">
                                                                <div class="image"><img src="assets/images/avatar4.png" alt="Avatar"></div>
                                                                <div class="notification-info">
                                                                    <div class="text"><span class="user-name">hoaquynhtim99</span> đăng tài liệu chờ duyệt <strong>Tài liệu cho nhà phát triển</strong>.</div>
                                                                    <span class="date">1 ngày trước</span>
                                                                </div>
                                                            </a>
                                                        </li>
                                                        <li class="notification">
                                                            <a href="#">
                                                                <div class="image"><img src="assets/images/avatar5.png" alt="Avatar"></div>
                                                                <div class="notification-info">
                                                                    <div class="text">Cronjob <span class="user-name">Gửi liên hệ</span> bị đình chỉ hoạt động do lỗi.</div>
                                                                    <span class="date">13:20 20/06/2018</span>
                                                                </div>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="footer"><a href="#">Xem tất cả</a></div>
                                    </li>
                                </ul>
                            </li>
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
                                    <li class="{if !empty($MOD_CURRENT['subs'])}parent {/if}active open">
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
                                                            <li{if $crrsub['active']} class="active"{/if}><a href="{$crrsub.link}"><span>{$crrsub.title}</span></a></li>
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
                                                            <li><a href="{$smenukey}">{$smenutitle}</a></li>
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
                    <div class="page-head clearfix">
                        <h2 class="page-head-title">{$PAGE_TITLE}</h2>
                        <nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb page-head-nav">
                                <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                                <li class="breadcrumb-item"><a href="#">Các thành phần UI</a></li>
                                <li class="breadcrumb-item active">Cards</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="page-body">
                        {$MODULE_CONTENT}
                    </div>
                </div>
                <div class="main-footer">
                    <div class="stat-img">
                        <a title="NUKEVIET CMS" href="http://nukeviet.vn" target="_blank"><img alt="NUKEVIET CMS" src="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/images/banner_nukeviet_88x15.jpg" class="imgstatnkv"></a>
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
                            <li class="nav-item" role="presentation"><a class="nav-link" href="#main-admin-tab-settings" aria-controls="main-admin-tab-settings" role="tab" data-toggle="tab">Thiết lập</a></li>
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
                            <div class="tab-pane tab-settings" id="main-admin-tab-settings" role="tabpanel">
                                <div class="settings-wrapper">
                                    <div class="nv-scroller">
                                        <div class="category-title">Kiểu giao diện</div>
                                        <ul class="settings-list">
                                            <li>
                                                <div class="switch-button switch-button-sm">
                                                    <input type="checkbox" checked="" name="st1" id="st1"><span><label for="st1"></label></span>
                                                </div>
                                                <span class="name">Cố định header</span>
                                            </li>
                                            <li>
                                                <div class="switch-button switch-button-sm">
                                                    <input type="checkbox" checked="" name="st2" id="st2"><span><label for="st2"></label></span>
                                                </div>
                                                <span class="name">Cố định menu trái</span>
                                            </li>
                                            <li>
                                                <div class="switch-button switch-button-sm">
                                                    <input type="checkbox" checked="" name="st3" id="st3"><span><label for="st3"></label></span>
                                                </div>
                                                <span class="name">Thu gọn menu trái</span>
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
    <script src="{$NV_BASE_SITEURL}themes/{$NV_ADMIN_THEME}/js/nv.core.js"></script>
    <script src="{$NV_BASE_SITEURL}themes/{$NV_ADMIN_THEME}/js/nv.main.js"></script>
</html>
