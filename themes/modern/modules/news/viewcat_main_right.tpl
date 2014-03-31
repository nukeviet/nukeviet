<!-- BEGIN: main -->
<!-- BEGIN: listcat -->
<div class="box-border-shadow m-bottom">
	<div class="cat-box-header">
		<div class="cat-nav">
			<a class="rss" href="{CAT.rss}">Rss</a>
			<a title="{CAT.title}" class="current-cat" href="{CAT.link}">{CAT.title}</a>
			<!-- BEGIN: subcatloop -->
			<a title="{SUBCAT.title}" href="{SUBCAT.link}">{SUBCAT.title}</a>
			<!-- END: subcatloop -->
			<!-- BEGIN: subcatmore -->
			<a title="{MORE.title}" href="{MORE.link}">{MORE.title}</a>
			<!-- END: subcatmore -->
		</div>
	</div>
	<div class="cat-news{BG} clearfix">
		<div class="{WCT}">
			<div class="content-box clearfix">
				<div class="m-bottom">
					<h4>
						<a title="{CONTENT.title}" href="{CONTENT.link}">{CONTENT.title}</a>
						<!-- BEGIN: newday -->
						<span class="icon_new"></span>
						<!-- END: newday -->
					</h4>
					
					<p class="small">
						{LANG.pubtime}: {CONTENT.publtime} - {LANG.view}: {CONTENT.hitstotal} - {LANG.total_comment}: {CONTENT.hitscm}
					</p>
				</div>
				<!-- BEGIN: image -->
				<a title="{CONTENT.title}" href="{CONTENT.link}"><img class="s-border fl left" src="{HOMEIMG}" alt="{HOMEIMGALT}" width="{IMGWIDTH}"/></a>
				<!-- END: image -->
				<p>
					{CONTENT.hometext}
				</p>
				<!-- BEGIN: adminlink -->
				<p style="text-align : right;">
					{ADMINLINK}
				</p>
				<!-- END: adminlink -->
				<div class="aright">
					<a title="{LANG.more}" class="more" href="{CONTENT.link}">{LANG.more}</a>
				</div>
			</div>
		</div>
		<div class="ot-news fr">
			<!-- BEGIN: related -->
			<ul>
				<!-- BEGIN: loop -->
				<li class="{CLASS}">
					<a title="{OTHER.title}" href="{OTHER.link}">{OTHER.title}</a>
				</li>
				<!-- END: loop -->
			</ul>
			<!-- END: related -->
		</div>
		<div class="clear"></div>
	</div>
</div>
<!-- END: listcat -->
<!-- END: main -->