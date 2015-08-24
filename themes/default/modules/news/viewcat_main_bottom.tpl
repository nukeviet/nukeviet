<!-- BEGIN: main -->
<!-- BEGIN: listcat -->
<div class="news_column">
	<div class="panel panel-default clearfix">
		<div class="panel-heading">
			<ul class="list-inline sub-list-icon" style="margin: 0">
				<li><h4><a title="{CAT.title}" href="{CAT.link}"><span>{CAT.title}</span></a></h4></li>
				<!-- BEGIN: subcatloop -->
				<li class="hidden-xs"><h4><a class="dimgray" title="{SUBCAT.title}" href="{SUBCAT.link}">{SUBCAT.title}</a></h4></li>
				<!-- END: subcatloop -->
				<!-- BEGIN: subcatmore -->
				<li class="pull-right hidden-xs"><h4><a class="dimgray" title="{MORE.title}" href="{MORE.link}"><em class="fa fa-sign-out">&nbsp;</em></a></h4></li>
				<!-- END: subcatmore -->
			</ul>
		</div>

		<div class="panel-body">
			<!-- BEGIN: image -->
			<a href="{CONTENT.link}" title="{CONTENT.title}"><img alt="{HOMEIMGALT}" src="{HOMEIMG}" width="{IMGWIDTH}" class="img-thumbnail pull-left imghome" /></a>
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
					<li><em class="fa fa-eye">&nbsp;</em> {LANG.view}: {CONTENT.hitstotal}</li>
					<li><em class="fa fa-comment-o">&nbsp;</em> {LANG.total_comment}: {CONTENT.hitscm}</li>
				</ul>
			</div>
			<p>
				{CONTENT.hometext}
			</p>
			<!-- BEGIN: adminlink -->
			<p class="text-right">
				{ADMINLINK}
			</p>
			<!-- END: adminlink -->

			<!-- BEGIN: related -->
			<ul class="related">
				<!-- BEGIN: loop -->
				<li class="{CLASS}">
					<a class="show h4" href="{OTHER.link}" data-content="{OTHER.hometext}" data-img="{OTHER.imghome}" data-rel="tooltip" data-placement="{TOOLTIP_POSITION}" title="{OTHER.title}">{OTHER.title}</a>
				</li>
				<!-- END: loop -->
			</ul>
			<!-- END: related -->
		</div>
	</div>
</div>
<!-- END: listcat -->
<!-- END: main -->
