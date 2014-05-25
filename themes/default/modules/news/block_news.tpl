<!-- BEGIN: main -->
<ul class="listnews">
	<!-- BEGIN: newloop -->
	<li class="clearfix">
		<!-- BEGIN: imgblock -->
		<a title="{blocknews.title}" href="{blocknews.link}"><img src="{blocknews.imgurl}" alt="{blocknews.title}" width="{blocknews.width}" class="img-thumbnail pull-left"/></a>
		<!-- END: imgblock -->
		<a {TITLE} class="show" href="{blocknews.link}" data-content="{blocknews.hometext}" data-img="{blocknews.imgurl}" data-rel="block_news_tooltip">{blocknews.title}</a>
	</li>
	<!-- END: newloop -->
</ul>
<!-- BEGIN: tooltip -->
<script type="text/javascript">
$(document).ready(function() {$("[data-rel='block_news_tooltip']").tooltip({
	placement: "{TOOLTIP_POSITION}",
	html: true,
	title: function(){return '<img class="img-thumbnail pull-left margin_image" src="' + $(this).data('img') + '" width="90" /><p class="text-justify">' + $(this).data('content') + '</p><div class="clearfix"></div>';}
});});
</script>
<!-- END: tooltip -->
<!-- END: main -->