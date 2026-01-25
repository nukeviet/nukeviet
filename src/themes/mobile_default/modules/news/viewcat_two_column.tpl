<!-- BEGIN: main -->
<!-- BEGIN: h1 -->
<h1 class="hidden d-none">{PAGE_TITLE}</h1>
<!-- END: h1 -->
<!-- BEGIN: catcontent -->
{CATCONTENT_HTML}
<!-- END: catcontent -->
<!-- BEGIN: loopcat -->
<!-- BEGIN: block_topcat -->
<div class="block-top clear">
    {BLOCK_TOPCAT}
</div>
<!-- END: block_topcat -->
<div class="news_column two_column col-md-12">
	<div class="panel panel-default clearfix">
		<div class="panel-heading">
			<h2><a title="{CAT.title}" href="{CAT.link}"><span>{CAT.title}</span></a></h2>
		</div>

		<div class="panel-body">
			<!-- BEGIN: content -->
			<h3>
				<a href="{CONTENT.link}" title="{CONTENT.title}">{CONTENT.title}</a>
				<!-- BEGIN: newday -->
				<span class="icon_new">&nbsp;</span>
				<!-- END: newday -->
			</h3>
			<div class="text-muted">
				<ul class="list-unstyled list-inline">
					<li><em class="fa fa-clock-o">&nbsp;</em> {CONTENT.publtime}</li>
					<li><em class="fa fa-eye">&nbsp;</em> {CONTENT.hitstotal}</li>
					<li><em class="fa fa-comment-o">&nbsp;</em> {CONTENT.hitscm}</li>
				</ul>
			</div>
			<!-- BEGIN: image -->
			<a href="{CONTENT.link}" title="{CONTENT.title}"><img alt="{HOMEIMGALT01}" src="{HOMEIMG01}" width="{IMGWIDTH0}" class="img-thumbnail pull-left imghome" /></a>
			<!-- END: image -->
			<div class="text-justify">
				{CONTENT.hometext}
			</div>
			<!-- BEGIN: adminlink -->
			<p class="text-right">
				{ADMINLINK}
			</p>
			<!-- END: adminlink -->
			<!-- END: content -->
			<ul class="related">
				<!-- BEGIN: other -->
				<li class="{CLASS}">
					<a class="show" href="{CONTENT.link}" title="{CONTENT.title}">{CONTENT.title}</a>
				</li>
				<!-- END: other -->
			</ul>
		</div>
	</div>
</div>
<!-- BEGIN: block_bottomcat -->
<div class="bottom-cat clear">
    {BLOCK_BOTTOMCAT}
</div>
<!-- END: block_bottomcat -->
<!-- END: loopcat -->
<div class="clear"></div>
<script type="text/javascript">
$(window).on('load', function() {
    $.each( $('.two_column .panel-body'), function(k,v){
        if( k % 2 == 0 ){
            var height1 = $($('.two_column .panel-body')[k]).height();
            var height2 = $($('.two_column .panel-body')[k+1]).height();
            var height = ( height1 > height2 ? height1 : height2 );
            $($('.two_column .panel-body')[k]).height( height );
            $($('.two_column .panel-body')[k+1]).height( height );
        }
    });
});
</script>
<!-- END: main -->
