<!-- BEGIN: main -->
{FILE "header.tpl"}
<div class="contentwrapper">
	[HEADER]
	<div class="span-5">
		[LEFT]
	</div>
	<div class="span-19 last">
		<!-- BEGIN: mod_title -->
        <div class="breadcrumbs">
            <div itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
                <a title="{LANG.Home}" href="{NV_BASE_SITEURL}" itemprop="url"><span itemprop="title" class="home">{LANG.Home}</span></a>
            </div>
            <!-- BEGIN: breakcolumn -->
            <div itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
                › <a href="{BREAKCOLUMN.link}" title="{BREAKCOLUMN.title}" itemprop="url"><span itemprop="title">{BREAKCOLUMN.title}</span></a>
            </div>
            <!-- END: breakcolumn -->
            <div class="clear"></div>
        </div>
		<!-- END: mod_title -->
		[TOP]
		{MODULE_CONTENT}
		[BOTTOM]
	</div>
	<div class="clear"></div>
	[FOOTER]
</div>
{FILE "footer.tpl"}
<!-- END: main -->