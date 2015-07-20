	<noscript>
		<div class="alert alert-danger">{LANG.nojs}</div>
	</noscript>
	<div id="wraper">
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
                    <div class="pull-right">
                        <div id="social-icons">
                            [SOCIAL_ICONS]
                        </div>
                        <div id="search">
                            <form action="{NV_BASE_SITEURL}" method="get" onsubmit="return {THEME_SEARCH_SUBMIT_ONCLICK}">
                                <div class="input-group">
                                    <input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}" />
                                    <input type="hidden" name="{NV_NAME_VARIABLE}" value="seek" />
                                    <input type="text" class="form-control" name="q" id="topmenu_search_query" maxlength="{THEME_SEARCH_QUERY_MAX_LENGTH}" placeholder="{LANG.search}..."/>
                                    <span class="input-group-btn"><button class="btn btn-info" type="submit" id="topmenu_search_submit"><em class="fa fa-lg fa-search"></em></button></span>
                                </div>
                            </form>
                        </div>
                    </div>
				</div>
			</div>
		</header>
		<nav>
			<div class="container">
				<div class="row">
					[MENU_SITE]
				</div>
			</div>
		</nav>
		<section>
			<div class="container" id="body">
				<!-- BEGIN: breadcrumbs -->
				<ol class="breadcrumb">
					<li class="home" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><em class="fa fa-lg fa-home">&nbsp;</em><a href="{THEME_SITE_HREF}" itemprop="url" title="{LANG.Home}"><span itemprop="title">{LANG.Home}</span></a></li>
					<!-- BEGIN: loop --><li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="{BREADCRUMBS.link}" itemprop="url" title="{BREADCRUMBS.title}"><span itemprop="title">{BREADCRUMBS.title}</span></a></li><!-- END: loop -->
				</ol>
				<!-- END: breadcrumbs -->
				[THEME_ERROR_INFO]