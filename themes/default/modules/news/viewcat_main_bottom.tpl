<!-- BEGIN: main -->
<!-- BEGIN: listcat -->
<div class="news_column">
	<div class="panel panel-default clearfix">
		<div class="panel-heading">
			<ul class="list-inline" style="margin: 0">
				<li><a title="{CAT.title}" href="{CAT.link}"><span>{CAT.title}</span></a></li>
				<!-- BEGIN: subcatloop -->
				<li class="hidden-xs"><a title="{SUBCAT.title}" href="{SUBCAT.link}">{SUBCAT.title}</a></li>
				<!-- END: subcatloop -->
				<!-- BEGIN: subcatmore -->
				<li class="pull-right hidden-xs"><a title="{MORE.title}" href="{MORE.link}"><em class="fa fa-sign-out">&nbsp;</em></a></li>
				<!-- END: subcatmore -->
			</ul>
		</div>

		<div class="panel-body">
			<!-- BEGIN: image -->
			<a href="{CONTENT.link}" title="{CONTENT.title}"><img alt="{HOMEIMGALT}" src="{HOMEIMG}" width="{IMGWIDTH}" class="img-thumbnail pull-left imghome" /></a>
			<!-- END: image -->
			<h3>
				<a href="{CONTENT.link}" title="{CONTENT.title}">{CONTENT.title}</a>
				<!-- BEGIN: newday -->
				<span class="icon_new">&nbsp;</span>
				<!-- END: newday -->
			</h3>
			<p class="text-justify">
				{CONTENT.hometext}
			</p>
			<!-- BEGIN: adminlink -->
			<p class="text-right">
				{ADMINLINK}
			</p>
			<!-- END: adminlink -->

			<!-- BEGIN: related -->
			<ul class="related">
				<!-- BEGIN: loop -->
				<li class="{CLASS}">
					<a class="show" href="{OTHER.link}" data-content="{OTHER.hometext}" data-img="{OTHER.imghome}" data-rel="tooltip">{OTHER.title}</a>
				</li>
				<!-- END: loop -->
			</ul>
			<!-- END: related -->
		</div>
	</div>
</div>
<!-- END: listcat -->
<!-- BEGIN: tooltip -->
<script type="text/javascript">
$(document).ready(function() {$("[data-rel='tooltip']").tooltip({
	placement: "{TOOLTIP_POSITION}",
	html: true,
	title: function(){return '<img class="img-thumbnail pull-left margin_image" src="' + $(this).data('img') + '" width="90" /><p class="text-justify">' + $(this).data('content') + '</p><div class="clearfix"></div>';}
});});
</script>
<!-- END: tooltip -->
<!-- END: main -->