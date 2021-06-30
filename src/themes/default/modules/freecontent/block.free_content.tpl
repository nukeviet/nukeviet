<!-- BEGIN: main -->
<div class="panel-body">
	<div class="featured-products">
		<!-- BEGIN: loop -->
		<div class="row clearfix">
			<!-- BEGIN: title_text --><h3>{ROW.title}</h3><!-- END: title_text -->
			<!-- BEGIN: title_link --><h3><a href="{ROW.link}" title="{ROW.title}"<!-- BEGIN: target --> target="{ROW.target}"<!-- END: target -->>{ROW.title}</a></h3><!-- END: title_link -->
			<div class="col-xs-24 col-sm-5 col-md-8">
				<!-- BEGIN: image_only --><img title="{ROW.title}" alt="{ROW.title}" src="{ROW.image}" class="img-thumbnail"><!-- END: image_only -->
				<!-- BEGIN: image_link --><a href="{ROW.link}" title="{ROW.title}"<!-- BEGIN: target --> target="{ROW.target}"<!-- END: target -->><img src="{ROW.image}" title="{ROW.title}" alt="{ROW.title}" class="img-thumbnail"></a><!-- END: image_link -->
			</div>
			<div class="col-xs-24 col-sm-19 col-md-16">
				{ROW.description}
			</div>
		</div>
		<!-- END: loop -->
	</div>
</div>
<!-- END: main -->