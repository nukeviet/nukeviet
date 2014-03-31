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
<div class="box-border-shadow m-bottom t-news">
	<div class="content-box clearfix">
		<!-- BEGIN: catcontent -->
		<!-- BEGIN: image -->
		<a href="{CONTENT.link}" title="{CONTENT.title}"><img class="s-border fl left" alt="{HOMEIMGALT0}" src="{HOMEIMG0}" width="{IMGWIDTH0}"/></a>
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
		<!-- BEGIN: adminlink -->
		<p style="text-align : right;">
			{ADMINLINK}
		</p>
		<!-- END: adminlink -->
		<div class="aright">
			<a title="{LANG.more}" class="more" href="{CONTENT.link}">{LANG.more}</a>
		</div>
		<!-- END: catcontent -->
	</div>
	<div class="other-news">
		<ul>
			<!-- BEGIN: catcontentloop -->
			<li>
				<a title="{CONTENT.title}" href="{CONTENT.link}">{CONTENT.title}</a>
				<span class="small">{CONTENT.publtime}</span>
				<!-- BEGIN: newday -->
				<span class="icon_new"></span>
				<!-- END: newday -->
			</li>
			<!-- END: catcontentloop -->
		</ul>
	</div>
</div>

<!-- BEGIN: generate_page -->
<div class="acenter">
	{GENERATE_PAGE}
</div>
<!-- END: generate_page -->
<!-- END: main -->