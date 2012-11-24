<!-- BEGIN: main -->
	<div class="news_grid">
		<!-- BEGIN: cattitle -->
			<h3 class="cat"><a title="{CAT.title}" href="{CAT.link}">{CAT.title}</a></h3>
		<!-- END: cattitle -->
		<!-- BEGIN: viewcatloop -->
			<div class="item fl" style="width: 33%">
				<div class="item_content">
					<a title="{CONTENT.title}" href="{CONTENT.link}">
						<img alt="{HOMEIMGALT1}" src="{HOMEIMG1}" width="{IMGWIDTH1}" />
					</a>
					<h2><a title="{CONTENT.title}" href="{CONTENT.link}">{CONTENT.title}</a></h2>
					<!-- BEGIN: adminlink -->
					<span class="admintab">
						{ADMINLINK}
					</span>
					<!-- END: adminlink -->					
				</div>
			</div>
		<!-- END: viewcatloop -->
		<div class="clear"></div>				
	</div>
	
	<!-- BEGIN: generate_page -->
	<div class="generate_page">
		{GENERATE_PAGE}
	</div>
	<!-- END: generate_page -->	
<!-- END: main -->