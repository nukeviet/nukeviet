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
<div class="news_grid">
	<!-- BEGIN: viewcatloop -->
	<div class="item fl" style="width: 33%">
		<div class="item_content">
			<a title="{CONTENT.title}" href="{CONTENT.link}"> <img alt="{HOMEIMGALT1}" src="{HOMEIMG1}" width="{IMGWIDTH1}"/> </a>
			<h2>
				<a title="{CONTENT.title}" href="{CONTENT.link}">{CONTENT.title}</a>
				<!-- BEGIN: newday -->
				<span class="icon_new"></span>
				<!-- END: newday -->
			</h2>
			<!-- BEGIN: adminlink -->
			<span class="admintab"> {ADMINLINK} </span>
			<!-- END: adminlink -->
		</div>
	</div>
	<!-- END: viewcatloop -->
	<div class="clear"></div>
</div>
<!-- BEGIN: generate_page -->
<div class="acenter">
	{GENERATE_PAGE}
</div>
<!-- END: generate_page -->
<!-- END: main -->