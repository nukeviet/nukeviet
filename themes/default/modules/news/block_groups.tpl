<!-- BEGIN: main -->
<style type="text/css">
.other_blocknews ul{margin:0;padding:0}
.other_blocknews ul li{margin-bottom: 10px}
.other_blocknews img{margin-right: 5px}
</style>
<div class="other_blocknews">
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
</div>
<!-- BEGIN: tooltip -->
<script type="text/javascript">
$(document).ready(function() {$("[data-rel='block_tooltip']").tooltip({
	placement: "{TOOLTIP_POSITION}",
	html: true,
	title: function(){return '<img class="img-thumbnail pull-left margin_image" src="' + $(this).data('img') + '" width="90" /><p class="text-justify">' + $(this).data('content') + '</p><div class="clearfix"></div>';}
});});
</script>
<!-- END: tooltip -->
<!-- END: main -->