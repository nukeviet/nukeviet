<!-- BEGIN: main -->
<!-- BEGIN: viewdescription -->
<div class="listz-news">
	<h1>{CONTENT.title}</h1>
	<!-- BEGIN: image -->
	<img class="s-border fl left" alt="{CONTENT.title}" src="{HOMEIMG1}" width="{IMGWIDTH1}" />
	<!-- END: image -->
	<h2>{CONTENT.description}</h2>
</div>
<!-- END: viewdescription -->
<!-- BEGIN: viewcatloop -->
<div class="box-border-shadow m-bottom listz-news">
	<div class="content-box">
		<!-- BEGIN: image -->
		<a href="{CONTENT.link}" title="{CONTENT.title}"><img class="s-border fl left" alt="{HOMEIMGALT1}" src="{HOMEIMG1}" width="{IMGWIDTH1}"/></a>
		<!-- END: image -->
		<h4>
			<a href="{CONTENT.link}" title="{CONTENT.title}">{CONTENT.title}</a>
			<!-- BEGIN: newday -->
			<span class="icon_new"></span>
			<!-- END: newday -->
		</h4>
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
		<div class="clear"></div>
	</div>
	<div class="info small">
		{LANG.pubtime}: {CONTENT.publtime} | {LANG.view}: {CONTENT.hitstotal} | {LANG.total_comment}: {CONTENT.hitscm} | {LANG.category}:
		<!-- BEGIN: cat -->
		<a title="{CAT.title}" href="{CAT.link}" class="highlight">{CAT.title}</a>
		<!-- BEGIN: comma -->
		,
		<!-- END: comma -->
		<!-- END: cat -->
	</div>
</div>
<!-- END: viewcatloop -->
<!-- BEGIN: related -->
<div class="other-news">
	<h4>{ORTHERNEWS}</h4>
	<ul>
		<!-- BEGIN: loop -->
		<li>
			<a href="{RELATED.link}" title="{RELATED.title}">{RELATED.title}</a>
			<span class="small date">({RELATED.publtime})</span>
			<!-- BEGIN: newday -->
			<span class="icon_new"></span>
			<!-- END: newday -->
		</li>
		<!-- END: loop -->
	</ul>
</div>
<!-- END: related -->
<!-- BEGIN: generate_page -->
<div class="acenter">
	{GENERATE_PAGE}
</div>
<!-- END: generate_page -->
<!-- END: main -->