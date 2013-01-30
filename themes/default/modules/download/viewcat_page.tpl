<!-- BEGIN: main -->
<!-- BEGIN: listsubcat -->
<div class="box-border-shadow m-bottom">
	<div class="cat-nav">
		<a class="{listsubcat.current}" href="{listsubcat.link}">{listsubcat.title}</a>
	</div>
	<!-- BEGIN: itemcat -->
	<div class="content-box clearfix download">
		<!-- BEGIN: image -->
		<a href="{itemcat.more_link}"> <img src="{itemcat.imagesrc}" alt="{itemcat.title}" style="width:120px;height:107px" class="s-border left fl" /> </a>
		<!-- END: image -->
		<h4><a href="{itemcat.more_link}">{itemcat.title}</a></h4>
		<p class="small">
			<span class="count-comments">{LANG.viewcat_view_hits} {itemcat.view_hits}</span>
			<span class="count-down">{LANG.viewcat_download_hits} {itemcat.download_hits}</span>
		</p>
		<p>
			{itemcat.introtext}
		</p>
		<p class="aright">
			<a class="more" href="{itemcat.more_link}">{LANG.readmore}</a>
		</p>
		<div class="clear"></div>
		<!-- BEGIN: related -->
		<ul class="other-down">
			<!-- BEGIN: loop -->
			<li class="clearfix">
				<a href="{loop.more_link}">{loop.title}</a>
				<span class="small">- {LANG.viewcat_download_hits} {loop.download_hits}</span>
			</li>
			<!-- END: loop -->
		</ul>
		<!-- END: related -->
	</div>
	<!-- END: itemcat -->
</div>
<!-- END: listsubcat -->
<!-- BEGIN: listpostcat -->
<div class="box-border m-bottom down-o">
	<div class="clearfix content-box">
		<!-- BEGIN: is_admin -->
		<div class="fr">
			(<a href="{listpostcat.edit_link}" class="r_down">{GLANG.edit}</a>)
		</div>
		<!-- END: is_admin -->
		<!-- BEGIN: image -->
		<img src="{listpostcat.imagesrc}" alt="{listpostcat.title}" class="s-border left fl" style="width:60px;height:54px"/>
		<!-- END: image -->
		<h4><a title="{listpostcat.title}" href="{listpostcat.more_link}">{listpostcat.title}</a></h4>
		<p>
			{listpostcat.introtext}
		</p>
		<div class="clear"></div>
		<div class="info small">
			{LANG.uploadtime}: {listpostcat.uploadtime} <span class="count-comments">{LANG.view_hits} {listpostcat.view_hits}</span>
			<span class="count-down">{LANG.download_hits} {listpostcat.download_hits}</span>
		</div>
	</div>
</div>
<!-- END: listpostcat -->
<!-- BEGIN: generate_page -->
<div class="page-nav m-bottom aright">
	{GENERATE_PAGE}
</div>
<!-- END: generate_page -->
<!-- END: main -->