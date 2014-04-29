<!-- BEGIN: main -->
<!-- BEGIN: viewdescription -->
<div class="news_column">
	<div class="alert alert-info clearfix">
		<h3>{CONTENT.title}</h3>
		<!-- BEGIN: image -->
		<img alt="{CONTENT.title}" src="{HOMEIMG1}" width="{IMGWIDTH1}" class="img-thumbnail pull-left imghome" />
		<!-- END: image -->
		<p class="text-justify">{CONTENT.description}</p>
	</div>
</div>
<!-- END: viewdescription -->

<!-- BEGIN: viewcatloop -->
<div class="col-sm-6 col-md-4">
	<div class="thumbnail">
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

<script type="text/javascript">
$(window).load(function(){
    $.each( $('.thumbnail'), function(k,v){
        var height1 = $($('.thumbnail')[k]).height();
        var height2 = $($('.thumbnail')[k+1]).height();
        var height = ( height1 > height2 ? height1 : height2 );
        $($('.thumbnail')[k]).height( height );
        $($('.thumbnail')[k+1]).height( height );
    });
});
</script>

<!-- END: main -->