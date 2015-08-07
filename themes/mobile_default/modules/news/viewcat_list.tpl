<!-- BEGIN: main -->
<!-- BEGIN: viewdescription -->
<div class="news_column">
	<div class="alert alert-info clearfix">
		<h3>{CONTENT.title}</h3>
		<!-- BEGIN: image -->
		<img alt="{CONTENT.title}" src="{HOMEIMG1}" width="{IMGWIDTH1}" class="img-thumbnail pull-left imghome" />
		<!-- END: image -->
		<p>{CONTENT.description}</p>
	</div>
</div>

<!-- END: viewdescription -->

<div class="list-group">
	<!-- BEGIN: viewcatloop -->
	<a class="show" href="{CONTENT.link}" title="{CONTENT.title}" title="{CONTENT.title}">
		<h4 class="list-group-item-heading">
			<span class="label label-default">{NUMBER}</span>&nbsp;&nbsp;{CONTENT.title}
			<!-- BEGIN: newday -->
			<span class="icon_new">&nbsp;</span>
			<!-- END: newday -->
		</h4>
	</a>
	<!-- END: viewcatloop -->
</div>

<!-- BEGIN: generate_page -->
<div class="text-center">
	{GENERATE_PAGE}
</div>
<!-- END: generate_page -->
<!-- END: main -->