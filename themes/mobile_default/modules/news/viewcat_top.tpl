<!-- BEGIN: main -->
<!-- BEGIN: viewdescription -->
<div class="news_column">
	<div class="alert alert-info clearfix">
		<h1>{CONTENT.title}</h1>
		<!-- BEGIN: image -->
		<img alt="{CONTENT.title}" src="{HOMEIMG1}" width="{IMGWIDTH1}" id="imghome" class="img-thumbnail pull-left" />
		<!-- END: image -->
		<p>{CONTENT.description}</p>
	</div>
</div>
<!-- END: viewdescription -->
<div class="news_column">
	<div class="panel panel-default">
		<div class="panel-body featured">
			<!-- BEGIN: catcontent -->
				<!-- BEGIN: image -->
				<a href="{CONTENT.link}" title="{CONTENT.title}"><img id="imghome" alt="{HOMEIMGALT0}" src="{HOMEIMG0}" width="60" class="img-thumbnail pull-left imghome" /></a>
				<!-- END: image -->
				<h2>
					<a href="{CONTENT.link}" title="{CONTENT.title}">{CONTENT.title}</a>
					<!-- BEGIN: newday -->
					<span class="icon_new"></span>
					<!-- END: newday -->
				</h2>
				<div class="text-muted">
					<ul class="list-unstyled list-inline">
						<li><em class="fa fa-clock-o">&nbsp;</em> {CONTENT.publtime}</li>
						<li><em class="fa fa-eye">&nbsp;</em> {CONTENT.hitstotal}</li>
						<li><em class="fa fa-comment-o">&nbsp;</em> {CONTENT.hitscm}</li>
					</ul>
				</div>
				{CONTENT.hometext}
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