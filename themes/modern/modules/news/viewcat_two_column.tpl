<!-- BEGIN: main -->
<!-- BEGIN: viewdescription -->
<div class="listz-news clearfix m-bottom">
	<h1>{CONTENT.title}</h1>
	<!-- BEGIN: image -->
	<img class="s-border fl left" alt="{CONTENT.title}" src="{HOMEIMG1}" width="{IMGWIDTH1}" />
	<!-- END: image -->
	<h2>{CONTENT.description}</h2>
</div>
<!-- END: viewdescription -->
<!-- BEGIN: catcontent -->
<div class="box-border-shadow m-bottom t-news">
	<!-- BEGIN: content -->
	<div class="content-box clearfix">
		<h4>
			<a href="{NEWSTOP.link}" title="{NEWSTOP.title}">{NEWSTOP.title}</a>
			<!-- BEGIN: newday -->
			<span class="icon_new"></span>
			<!-- END: newday -->
		</h4>
		<!-- BEGIN: image -->
		<a href="{NEWSTOP.link}" title="{NEWSTOP.title}"><img class="s-border fl left" alt="{HOMEIMGALT0}" src="{HOMEIMG0}" width="{IMGWIDTH0}"/></a>
		<!-- END: image -->
		<p>
			{NEWSTOP.hometext}
		</p>
		<!-- BEGIN: adminlink -->
		<p style="text-align : right;">
			{ADMINLINK}
		</p>
		<!-- END: adminlink -->
		<div class="aright">
			<a title="{LANG.more}" class="more" href="{NEWSTOP.link}">{LANG.more}</a>
		</div>
	</div>
	<!-- END: content -->
	<div class="other-news">
		<ul>
			<!-- BEGIN: other -->
			<li>
				<a title="{NEWSTOP.title}" href="{NEWSTOP.link}">{NEWSTOP.title}</a>
				<span class="small">{NEWSTOP.publtime}</span>
				<!-- BEGIN: newday -->
				<span class="icon_new"></span>
				<!-- END: newday -->
			</li>
			<!-- END: other -->
		</ul>
	</div>
</div>
<!-- END: catcontent -->
<!-- BEGIN: loopcat -->
<div id="catid-{ID}" class="m-bottom box50{FLOAT}">
	<div class="box-border-shadow">
		<div class="cat-nav">
			<a class="rss" href="{CAT.rss}">RSS</a>
			<a class="current-cat" title="{CAT.title}" href="{CAT.link}">{CAT.title}</a>
		</div>
		<div class="content-box news-cat-two-column">
			<!-- BEGIN: content -->
			<div class="m-bottom">
				<h4>
					<a href="{CONTENT.link}" title="{CONTENT.title}">{CONTENT.title}</a>
					<!-- BEGIN: newday -->
					<span class="icon_new"></span>
					<!-- END: newday -->
				</h4>
				<p class="small">
					{LANG.pubtime}: {CONTENT.publtime} - {LANG.view}: {CONTENT.hitstotal} - {LANG.total_comment}: {CONTENT.hitscm}
				</p>
			</div>
			<!-- BEGIN: image -->
			<a href="{CONTENT.link}" title="{CONTENT.title}"><img class="s-border fl left" alt="{HOMEIMGALT01}" src="{HOMEIMG01}" width="{IMGWIDTH01}"/></a>
			<!-- END: image -->
			<p>
				{CONTENT.hometext}
			</p>
			<div class="aright">
				<a title="{LANG.more}" class="more" href="{CONTENT.link}">{LANG.more}</a>
			</div>
			<!-- BEGIN: adminlink -->
			<p style="text-align : right;">
				{ADMINLINK}
			</p>
			<!-- END: adminlink -->
			<!-- END: content -->
			<ul class="other-news">
				<!-- BEGIN: other -->
				<li>
					<a title="{CONTENT.title}" href="{CONTENT.link}">{CONTENT.title}</a>
					<!-- BEGIN: newday -->
					<span class="icon_new"></span>
					<!-- END: newday -->
				</li>
				<!-- END: other -->
			</ul>
		</div>
	</div>
</div>
<!-- BEGIN: clear -->
<div class="clear"></div>
<!-- END: clear -->
<!-- END: loopcat -->
<script type="text/javascript">
	$(window).load(function() {
		$.each($('.news-cat-two-column'), function(k, v) {
			if (k % 2 == 0) {
				var height1 = $($('.news-cat-two-column')[k]).height();
				var height2 = $($('.news-cat-two-column')[k + 1]).height();
				var height = (height1 > height2 ? height1 : height2 );
				$($('.news-cat-two-column')[k]).height(height);
				$($('.news-cat-two-column')[k + 1]).height(height);
			}
		});
	}); 
</script>
<!-- END: main -->