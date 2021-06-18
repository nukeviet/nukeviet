<noscript>
    <div class="alert alert-danger">{LANG.nojs}</div>
</noscript>
<div id="mobilePage">
    <header class="first-child">
        <div id="header" class="clearfix">
            <div class="logo">
                <a title="{SITE_NAME} - {SITE_DESCRIPTION}" href="{THEME_SITE_HREF}"><img src="{LOGO_SRC}" alt="{SITE_NAME}"></a>
                <h1 class="hidden">{SITE_NAME}</h1>
                <h2 class="hidden">{SITE_DESCRIPTION}</h2>
            </div>
            <div class="site-buttons">
                <div class="list-none">
                    [MENU_SITE]
                </div>
            </div>
        </div>
    </header>
    <div class="tip">
        <div id="tip" data-content=""></div>
    </div>
    <div class="wrap">
        <section>
            <div id="body">
                <!-- BEGIN: breadcrumbs -->
                <div itemscope itemtype="https://schema.org/BreadcrumbList">
                    <div class="breadcrumbs clearfix">
                        <span class="home-icon" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="{THEME_SITE_HREF}" itemprop="item" title="{LANG.Home}"><span itemprop="name"><em class="fa fa-home fa-lg"></em><span class="hidden">{LANG.Home}</span></span></a><i class="hidden" itemprop="position" content="1"></i></span>
                        <a class="toggle" onclick="showSubBreadcrumbs(this,event);"><em class="fa fa-angle-right"></em></a>
                        <ol class="breadcrumb"></ol>
                    </div>
                    <ol class="sub-breadcrumbs"></ol>
                    <ol class="temp-breadcrumbs hidden">
                        <!-- BEGIN: loop --><li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="{BREADCRUMBS.link}" itemprop="item" title="{BREADCRUMBS.title}"><span itemprop="name">{BREADCRUMBS.title}</span></a><i class="hidden" itemprop="position" content="{BREADCRUMBS.position}"></i></li><!-- END: loop -->
                    </ol>
                </div>
                <!-- END: breadcrumbs -->
                [THEME_ERROR_INFO]
