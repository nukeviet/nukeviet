	<noscript>
		<div class="alert alert-danger">{LANG.nojs}</div>
	</noscript>
    <div class="body-bg">
	<div class="wraper">
		<header>
			<div class="container">
				<div id="header" class="row">
				    <div class="logo">
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
                        <h1>{SITE_NAME}</h1>
                        <h2>{SITE_DESCRIPTION}</h2>
                    </div>
                    <div class="clearfix"></div>
                    <!-- BEGIN: breadcrumbs -->
                    <h3 class="breadcrumb">
                        <span class="hidden" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="{THEME_SITE_HREF}" itemprop="url" title="{LANG.Home}"><span itemprop="title">{LANG.Home}</span></a></span><!-- BEGIN: loop --><span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="{BREADCRUMBS.link}" itemprop="url" title="{BREADCRUMBS.title}"><span class="txt" itemprop="title">{BREADCRUMBS.title}</span></a></span><!-- END: loop -->
                    </h3>
                    <!-- END: breadcrumbs -->
				</div>
			</div>
		</header>
		<nav class="second-nav" id="menusite">
			<div class="container">
				<div class="row">
                    <div class="bg radius-top-left radius-top-right box-shadow">
					[MENU_SITE]
                    </div>
				</div>
                <div class="headerSearch">
                    <div class="input-group">
                        <input type="text" class="form-control" maxlength="{THEME_SEARCH_QUERY_MAX_LENGTH}" placeholder="{LANG.search}...">
                        <span class="input-group-btn"><button type="button" class="btn btn-info" data-url="{THEME_SEARCH_URL}" data-minlength="{THEME_SEARCH_QUERY_MIN_LENGTH}" data-click="y"><em class="fa fa-search fa-lg"></em></button></span>
                    </div>
                </div>
			</div>
		</nav>
        <nav class="header-nav">
            <div class="container">
                <div class="social-icons">
                [SOCIAL_ICONS]
                </div>
                <div class="contactDefault">
                [CONTACT_DEFAULT]
                </div>
                <div id="tip" data-content="">
                    <div class="bg bg-gradient"></div>
                </div>
            </div>
        </nav>
		<section>
			<div class="container" id="body">
				[THEME_ERROR_INFO]