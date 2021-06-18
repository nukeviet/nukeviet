<!-- BEGIN: main -->
<!-- BEGIN: viewdescription -->
<div class="news_column">
	<div class="alert alert-info clearfix">
		<h1>{CONTENT.title}</h1>
		<!-- BEGIN: image -->
		<img alt="{CONTENT.title}" src="{HOMEIMG1}" width="60" class="img-thumbnail pull-left imghome" />
		<!-- END: image -->
		<p>{CONTENT.description}</p>
	</div>
</div>
<!-- END: viewdescription -->
<!-- BEGIN: viewcatloop -->
<div class="news_column">
	<!-- BEGIN: featured -->
	<div class="panel panel-default">
		<div class="panel-body featured">
			<!-- BEGIN: image -->
			<a href="{CONTENT.link}" title="{CONTENT.title}"><img  alt="{HOMEIMGALT1}" src="{HOMEIMG1}" width="60" class="img-thumbnail pull-left imghome" /></a>
			<!-- END: image -->
			<h2>
				<a href="{CONTENT.link}" title="{CONTENT.title}">{CONTENT.title}</a>
				<!-- BEGIN: newday --><span class="icon_new">&nbsp;</span><!-- END: newday -->
			</h2>
			<div class="text-muted">
				<ul class="list-unstyled list-inline">
					<li>
						<em class="fa fa-clock-o">&nbsp;</em> {CONTENT.publtime}
					</li>
					<li>
						<em class="fa fa-eye">&nbsp;</em> {CONTENT.hitstotal}
					</li>
					<li>
						<em class="fa fa-comment-o">&nbsp;</em> {CONTENT.hitscm}
					</li>
				</ul>
			</div>
			{CONTENT.hometext}
			<!-- BEGIN: adminlink -->
			<p class="text-right">
				{ADMINLINK}
			</p>
			<!-- END: adminlink -->
		</div>
	</div>
	<!-- END: featured -->
	<!-- BEGIN: news -->
	<div class="panel panel-default">
		<div class="panel-body">
			<!-- BEGIN: image -->
			<a href="{CONTENT.link}" title="{CONTENT.title}"><img  alt="{HOMEIMGALT1}" src="{HOMEIMG1}" width="60" class="img-thumbnail pull-left imghome" /></a>
			<!-- END: image -->
			<h3>
				<a href="{CONTENT.link}" title="{CONTENT.title}">{CONTENT.title}</a>
				<!-- BEGIN: newday -->
				<span class="icon_new">&nbsp;</span>
				<!-- END: newday -->
			</h3>
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
		</div>
	</div>
	<!-- END: news -->
</div>
<!-- END: viewcatloop -->
<!-- BEGIN: related -->
<hr/>
<h4>{ORTHERNEWS}</h4>
<ul class="related">
	<!-- BEGIN: loop -->
	<li>
		<em class="fa fa-angle-right">&nbsp;</em><a href="{RELATED.link}" title="{RELATED.title}">{RELATED.title} <em>({RELATED.publtime}) </em></a>
		<!-- BEGIN: newday -->
		<span class="icon_new">&nbsp;</span>
		<!-- END: newday -->
	</li>
	<!-- END: loop -->
</ul>
<!-- END: related -->
<!-- BEGIN: generate_page -->
<div class="clearfix"></div>
<div class="text-center">
	{GENERATE_PAGE}
</div>
<!-- END: generate_page -->
<!-- END: main -->