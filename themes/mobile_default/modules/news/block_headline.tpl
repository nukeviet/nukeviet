<!-- BEGIN: main -->
<link type="text/css" rel="stylesheet" href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/jquery.ui.tabs.css" />
<link type="text/css" rel="stylesheet" href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/contentslider.css" />
<div id="topnews" class="panel panel-default clearfix" style="display:none">
	<div class="row">
		<!-- BEGIN: hots_news_img -->
		<div class="col-md-12">
			<div id="slider1" class="sliderwrapper">
				<!-- BEGIN: loop -->
				<div class="contentdiv clearfix">
					<a title="{HOTSNEWS.title}" href="{HOTSNEWS.link}"><img class="img-responsive" id="slImg{HOTSNEWS.imgID}" src="{PIX_IMG}" alt="{HOTSNEWS.image_alt}" /></a><h3><a title="{HOTSNEWS.title}" href="{HOTSNEWS.link}">{HOTSNEWS.title}</a></h3>
				</div>
				<!-- END: loop -->
			</div>
			<div id="paginate-slider1" class="pagination">&nbsp;</div>
		</div>
		<!-- END: hots_news_img -->
		<div id="tabs" class="col-md-12 tabs">
			<ul>
				<!-- BEGIN: loop_tabs_title -->
				<li>
					<a href="#tabs-{TAB_TITLE.id}"><span><span>{TAB_TITLE.title}</span></span></a>
				</li>
				<!-- END: loop_tabs_title -->
			</ul>
			<div class="clear">&nbsp;</div>
			<!-- BEGIN: loop_tabs_content -->
			<div id="tabs-{TAB_TITLE.id}">
				<!-- BEGIN: content -->
				<ul class="lastest-news">
					<!-- BEGIN: loop -->
					<li>
						<a class="show" href="{LASTEST.link}" title="{LASTEST.title}">{LASTEST.title}</a>
					</li>
					<!-- END: loop -->
				</ul>
				<!-- END: content -->
			</div>
			<!-- END: loop_tabs_content -->
		</div>
	</div>
</div>
<!-- END: main -->