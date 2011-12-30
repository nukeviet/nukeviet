<!-- BEGIN: main -->
<link rel="stylesheet" type="text/css"	href="{NV_BASE_SITEURL}themes/{BLOCK_THEME}/css/nav_menu.css" />
<div class="nav">
    <ul class="nav fl">
        <li class="home">
            <a title="{LANG.Home}" href="{THEME_SITE_HREF}"><span>{LANG.Home}</span></a>
        </li>
        <!-- BEGIN: top_menu -->
        <li {TOP_MENU.current}>
            <a title="{TOP_MENU.title}" href="{TOP_MENU.link}"><span><strong>&bull;</strong>{TOP_MENU.title} 
                </span></a>
            <!-- BEGIN: sub -->
            <ul class="subnav">
                <!-- BEGIN: item -->
                <li>
                    <a title="{SUB.title}" href="{SUB.link}">&raquo; {SUB.title}</a>
                </li>
                <!-- END: item -->
            </ul>
            <!-- END: sub -->
        </li>
        <!-- END: top_menu -->
    </ul>
    <div class="rss fr">
        <a title="RSS" href="{THEME_RSS_INDEX_HREF}">&nbsp;</a>
    </div>
</div>
<!-- END: main -->