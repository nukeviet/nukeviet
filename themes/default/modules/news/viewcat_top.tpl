<!-- BEGIN: main -->
<!-- BEGIN: viewdescription -->
<div class="news_column">
	<div class="alert alert-info clearfix">
		<h3>{CONTENT.title}</h3>
		<!-- BEGIN: image -->
		<img alt="{CONTENT.title}" src="{HOMEIMG1}" width="{IMGWIDTH1}" id="imghome" class="img-thumbnail pull-left" />
		<!-- END: image -->
		<p class="text-justify">{CONTENT.description}</p>
	</div>
</div>
<!-- END: viewdescription -->
<div class="news_column">
	<div class="panel panel-default">
		<div class="panel-body">
			<!-- BEGIN: catcontent -->
				<!-- BEGIN: image -->
				<a href="{CONTENT.link}" title="{CONTENT.title}"><img id="imghome" alt="{HOMEIMGALT0}" src="{HOMEIMG0}" width="{IMGWIDTH0}" class="img-thumbnail pull-left" /></a>
				<!-- END: image -->
				<h3>
					<a href="{CONTENT.link}" title="{CONTENT.title}">{CONTENT.title}</a>
					<!-- BEGIN: newday -->
					<span class="icon_new"></span>
					<!-- END: newday -->
				</h3>
				<p class="text-justify">
					{CONTENT.hometext}
				</p>
				<!-- BEGIN: adminlink -->
				<p class="text-right">
					{ADMINLINK}
				</p>
				<!-- END: adminlink -->
				<hr />
			<!-- END: catcontent -->
			<ul class="related">
				<!-- BEGIN: catcontentloop -->
				<li>
					<em class="fa fa-angle-right">&nbsp;</em><a title="{CONTENT.title}" href="{CONTENT.link}">{CONTENT.title}</a>
					<!-- BEGIN: newday -->
					<span class="icon_new"></span>
					<!-- END: newday -->
				</li>
				<!-- END: catcontentloop -->
			</ul>
		</div>
	</div>
</div>
<!-- BEGIN: generate_page -->
<div class="text-center">
	{GENERATE_PAGE}
</div>
<!-- END: generate_page -->
<!-- END: main -->