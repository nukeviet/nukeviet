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

<!-- BEGIN: featuredloop -->
<div class="news_column">
<div class="panel panel-default">
		<div class="panel-body featured">
			<!-- BEGIN: image -->
			<a href="{CONTENT.link}" title="{CONTENT.title}" {CONTENT.target_blank}><img  alt="{HOMEIMGALT1}" src="{HOMEIMG1}" width="150px" class="img-thumbnail pull-left imghome" /></a>
			<!-- END: image -->
			<h2>
				<a href="{CONTENT.link}" title="{CONTENT.title}" {CONTENT.target_blank}>{CONTENT.title}</a>
			</h2>
			<div class="text-muted">
				<ul class="list-unstyled list-inline">
					<li>
						<em class="fa fa-clock-o">&nbsp;</em> {CONTENT.publtime}
					</li>
					<li>
						<em class="fa fa-eye">&nbsp;</em> {LANG.view}: {CONTENT.hitstotal}
					</li>
					<!-- BEGIN: comment -->
					<li>
						<em class="fa fa-comment-o">&nbsp;</em> {LANG.total_comment}: {CONTENT.hitscm}
					</li>
					<!-- END: comment -->
				</ul>
			</div>
			<div class="text-justify">
				{CONTENT.hometext}
			</div>
			<!-- BEGIN: adminlink -->
			<p class="text-right">
				{ADMINLINK}
			</p>
			<!-- END: adminlink -->
		</div>
	</div>
</div>
<!-- END: featuredloop -->

<!-- BEGIN: viewcatloop -->
<div class="col-sm-12 col-md-8">
	<div class="thumbnail">
		<a title="{CONTENT.title}" href="{CONTENT.link}" {CONTENT.target_blank}><img alt="{HOMEIMGALT1}" src="{HOMEIMG1}" width="{IMGWIDTH1}" class="img-thumbnail"/></a>
		<div class="caption text-center">
			<h4><a class="show" href="{CONTENT.link}" {CONTENT.target_blank} <!-- BEGIN: tooltip -->data-content="{CONTENT.hometext_clean}" data-img="" data-rel="tooltip" data-placement="{TOOLTIP_POSITION}"<!-- END: tooltip --> title="{CONTENT.title}">{CONTENT.title}</a></h4>
			<span>{ADMINLINK}</span>
		</div>
	</div>
</div>
<!-- END: viewcatloop -->
<div class="clear">&nbsp;</div>

<!-- BEGIN: generate_page -->
<div class="text-center">
	{GENERATE_PAGE}
</div>
<!-- END: generate_page -->
<script type="text/javascript">
$(window).on('load', function() {	
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