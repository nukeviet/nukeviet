<!-- BEGIN: main -->
<!-- BEGIN: viewdescription -->
<div class="news_column">
	<div class="alert alert-info clearfix">
		<h3>{CONTENT.title}</h3>
		<!-- BEGIN: image -->
		<img alt="{CONTENT.title}" id="imghome" src="{HOMEIMG1}" width="{IMGWIDTH1}" class="img-thumbnail pull-left" />
		<!-- END: image -->
		<p class="text-justify">{CONTENT.description}</p>
	</div>
</div>
<!-- END: viewdescription -->

<!-- BEGIN: viewcatloop -->
<div class="col-sm-6 col-md-4">
	<div class="thumbnail" style="min-height: 190px;max-height: 190px;">
		<a title="{CONTENT.title}" href="{CONTENT.link}"><img alt="{HOMEIMGALT1}" src="{HOMEIMG1}" width="{IMGWIDTH1}" class="img-thumbnail"/></a>
		<div class="caption text-center">
			<h4><a title="{CONTENT.title}" href="{CONTENT.link}">{CONTENT.title}</a></h4>
			<span>{ADMINLINK}</span>
		</div>
	</div>
</div>
<!-- END: viewcatloop -->
<div class="clear"></div>

<!-- BEGIN: generate_page -->
<div class="text-center">
	{GENERATE_PAGE}
</div>
<!-- END: generate_page -->
<!-- END: main -->