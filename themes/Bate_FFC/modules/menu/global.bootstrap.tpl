<!-- BEGIN: submenu -->
<ul class="dropdown-menu">
    <!-- BEGIN: loop -->
    <li<!-- BEGIN: submenu --> class="dropdown-submenu"<!-- END: submenu -->><!-- BEGIN: icon --><img src="{SUBMENU.icon}" alt="{SUBMENU.note}" />&nbsp;<!-- END: icon --><a href="{SUBMENU.link}" title="{SUBMENU.note}"{SUBMENU.target}>{SUBMENU.title_trim}</a><!-- BEGIN: item --> {SUB} <!-- END: item --></li>
    <!-- END: loop -->
</ul>
<!-- END: submenu -->

<!-- BEGIN: main -->
<div class="navbar navbar-default navbar-static-top" role="navigation">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#menu-site-default">
            <span class="sr-only">&nbsp;</span> <span class="icon-bar">&nbsp;</span> <span class="icon-bar">&nbsp;</span> <span class="icon-bar">&nbsp;</span>
        </button>
    </div>
    <div class="collapse navbar-collapse" id="menu-site-default">
        <ul class="nav navbar-nav">
            <!-- BEGIN: top_menu -->
            <li {TOP_MENU.current} role="presentation"><a class="dropdown-toggle" {TOP_MENU.dropdown_data_toggle} href="{TOP_MENU.link}" role="button" aria-expanded="false" title="{TOP_MENU.note}"{TOP_MENU.target}> <!-- BEGIN: icon --> <img src="{TOP_MENU.icon}" alt="{TOP_MENU.note}" />&nbsp; <!-- END: icon --> {TOP_MENU.title_trim}<!-- BEGIN: has_sub --> <strong class="caret">&nbsp;</strong>
                <!-- END: has_sub --></a> <!-- BEGIN: sub --> {SUB} <!-- END: sub --></li>
            <!-- END: top_menu -->
            <li><div class="headerSearch col-xs-24">
                            <div class="input-group" style="padding-top:5px;">
                                <input type="text" class="form-control"style="with:300px;" placeholder="{LANG.search}..."><span class="input-group-btn"><button type="button" class="btn btn-info" data-url="{THEME_SEARCH_URL}" data-minlength="{NV_MIN_SEARCH_LENGTH}" data-click="y"><em class="fa fa-search fa-lg"></em></button></span>
                            </div>
                        </div></li>
        </ul>
    </div>
</div>
<script type="text/javascript" data-show="after">
    $(function() {
        checkWidthMenu();
        $(window).resize(checkWidthMenu);
    });
</script>
<!-- END: main -->