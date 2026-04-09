<!-- BEGIN: main -->
<!-- BEGIN: level0 -->
<link rel="stylesheet" type="text/css" href="{NV_STATIC_URL}themes/{CSS_TEMPLATE}/css/global.vertmenu.css" />
<!-- END: level0 -->
<ul id="menu-{MENUID}-{PARENTID}" <!-- BEGIN: level0_1 -->class="vert-menu mb-3"<!-- END: level0_1 --><!-- BEGIN: level1 -->class="collapse<!-- BEGIN: is_show --> in<!-- END: is_show -->"<!-- END: level1 -->>
    <!-- BEGIN: loop -->
        <li <!-- BEGIN: is_active -->class="active"<!-- END: is_active -->>
            <a href="{ENTRY.link}" title="{ENTRY.title}"{ENTRY.target}>{ENTRY.strip_title}</a>
            <!-- BEGIN: sub -->
            <span class="arrow<!-- BEGIN: collapsed --> collapsed<!-- END: collapsed -->" data-toggle="collapse" data-target="#menu-{MENUID}-{ENTRY.id}" aria-controls="menu-{MENUID}-{ENTRY.id}" aria-expanded="<!-- BEGIN: expanded -->true<!-- END: expanded --><!-- BEGIN: no_expanded -->false<!-- END: no_expanded -->"></span>
            {SUBMENU}
            <!-- END: sub -->
        </li>
    <!-- END: loop -->
</ul>
<!-- END: main -->
