<!-- BEGIN: main -->
<ul>
	<!-- BEGIN: loop -->
	<li class="clearfix">
		<!-- BEGIN: img -->
		<a href="{ROW.link}" title="{ROW.title}"><img src="{ROW.thumb}" alt="{ROW.title}" width="{ROW.blockwidth}" class="img-thumbnail pull-left"/></a>
		<!-- END: img -->
		<a {TITLE} class="show" href="{ROW.link}" data-content="{ROW.hometext}" data-img="{ROW.thumb}" data-rel="block_tooltip">{ROW.title}</a>
	</li>
	<!-- END: loop -->
</ul>
<!-- BEGIN: tooltip -->
<script type="text/javascript" data-show="after">
$(document).ready(function() {$("[data-rel='block_tooltip'][data-content!='']").tooltip({
	placement: "{TOOLTIP_POSITION}",
	html: true,
	title: function(){return ( $(this).data('img') == '' ? '' : '<img class="img-thumbnail pull-left margin_image" src="' + $(this).data('img') + '" width="90" />' ) + '<p class="text-justify">' + $(this).data('content') + '</p><div class="clearfix"></div>';}
});});
</script>
<!-- END: tooltip -->
<!-- END: main -->
