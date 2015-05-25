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
	<a class="show" href="{CONTENT.link}" data-content="{CONTENT.hometext}" data-img="{CONTENT.imghome}" data-rel="tooltip" title="{CONTENT.title}">
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
<!-- BEGIN: tooltip -->
<script type="text/javascript">
	$(document).ready(function() {
		$("[data-rel='tooltip'][data-content!='']").removeAttr("title");
		$("[data-rel='tooltip'][data-content!='']").tooltip({
			placement: "{TOOLTIP_POSITION}",
			html: true,
			title: function(){return ( $(this).data('img') == '' ? '' : '<img class="img-thumbnail pull-left margin_image" src="' + $(this).data('img') + '" width="90" />' ) + '<p class="text-justify">' + $(this).data('content') + '</p><div class="clearfix"></div>';}
		});
	});
</script>
<!-- END: tooltip -->
<!-- END: main -->