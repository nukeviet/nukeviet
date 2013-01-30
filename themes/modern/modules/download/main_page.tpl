<!-- BEGIN: main -->
<!-- BEGIN: catbox -->
<div class="box-border-shadow m-bottom">
	<div class="cat-box-header">
		<div class="cat-nav">
			<a title="{catbox.title}" class="current-cat" href="{catbox.link}">{catbox.title}</a>
			<!-- BEGIN: subcatbox -->
			<!-- BEGIN: listsubcat -->
			<a title="{listsubcat.title}" href="{listsubcat.link}">{listsubcat.title}</a>
			<!-- END: listsubcat -->
			<!-- BEGIN: more -->
			<a class="more d-more" title="{LANG.categories_viewall}" href="{MORE}">{LANG.categories_viewall}</a>
			<!-- END: more -->
			<!-- END: subcatbox -->
		</div>
	</div>
	<div class="cat-news clearfix">
		<div class="news-full">
			<!-- BEGIN: itemcat -->
			<div class="content-box clearfix">
				<div class="m-bottom">
					<h4><a title="{itemcat.title}" href="{itemcat.more_link}">{itemcat.title}</a></h4>
					<p class="small">
						{LANG.author_name}: {itemcat.author_name} - <span class="count-comments">{LANG.view_hits}: {itemcat.view_hits}</span> - <span class="count-down">{LANG.download_hits}: {itemcat.download_hits}</span>
					</p>
				</div>
				<!-- BEGIN: image -->
				<a title="{itemcat.title}" href="{itemcat.more_link}"> <img class="s-border fl left" style="width:120px;height:107px" src="{itemcat.imagesrc}" alt="{itemcat.title}"/> </a>
				<!-- END: image -->
				<p>
					{itemcat.introtext}
				</p>
				<!-- BEGIN: adminlink -->
				<p style="text-align : right;">
					{ADMINLINK}
				</p>
				<!-- END: adminlink -->
				<div class="aright">
					<a title="{LANG.readmore}" class="more" href="{itemcat.more_link}">{LANG.readmore}</a>
				</div>
			</div>
			<!-- END: itemcat -->
		</div>
		<div class="ot-news-full">
			<!-- BEGIN: related -->
			<ul>
				<!-- BEGIN: loop -->
				<li>
					<a title="{loop.title}" href="{loop.more_link}">{loop.title}</a>
				</li>
				<!-- END: loop -->
			</ul>
			<!-- END: related -->
		</div>
	</div>
</div>
<!-- END: catbox -->
<!-- END: main -->