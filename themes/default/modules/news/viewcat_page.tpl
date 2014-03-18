<!-- BEGIN: main -->
<!-- BEGIN: viewdescription -->
<div class="news_column">
	<div class="items clearfix">
		<h1>{CONTENT.title}</h1>
		<!-- BEGIN: image -->
		<img alt="{CONTENT.title}" src="{HOMEIMG1}" width="{IMGWIDTH1}" />
		<!-- END: image -->
		<h2>{CONTENT.description}</h2>
	</div>
</div>
<!-- END: viewdescription -->
<!-- BEGIN: viewcatloop -->
<div class="news_column">
	<div class="items clearfix">
		<!-- BEGIN: image -->
		<a href="{CONTENT.link}" title="{CONTENT.title}"><img alt="{HOMEIMGALT1}" src="{HOMEIMG1}" width="{IMGWIDTH1}" /></a>
		<!-- END: image -->
		<h3>
			<a href="{CONTENT.link}" title="{CONTENT.title}">{CONTENT.title}</a>
			<!-- BEGIN: newday -->
			<span class="icon_new"></span>
			<!-- END: newday -->
		</h3>
		<p>
			{CONTENT.hometext}
		</p>
		<!-- BEGIN: adminlink -->
		<p style="text-align : right;">
			{ADMINLINK}
		</p>
		<!-- END: adminlink -->
	</div>
</div>
<!-- END: viewcatloop -->
<!-- BEGIN: related -->
<hr/>
<h4>{ORTHERNEWS}</h4>
<ul class="related">
	<!-- BEGIN: loop -->
	<li>
		<a href="{RELATED.link}" title="{RELATED.title}">{RELATED.title} <span class="date">({RELATED.publtime}) </span></a>
		<!-- BEGIN: newday -->
		<span class="icon_new"></span>
		<!-- END: newday -->
	</li>
	<!-- END: loop -->
</ul>
<!-- END: related -->
<!-- BEGIN: generate_page -->
<div class="acenter">
	{GENERATE_PAGE}
</div>
<!-- END: generate_page -->
<!-- END: main -->