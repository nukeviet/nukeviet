<!-- BEGIN: main -->
<!-- BEGIN: listcat -->
<div class="news_column">
	<div class="panel panel-default clearfix">
		<div class="panel-heading">
			<ul class="list-inline" style="margin: 0">
				<li><a title="{CAT.title}" href="{CAT.link}"><span>{CAT.title}</span></a></li>
				<!-- BEGIN: subcatloop -->
				<li class="hidden-xs"><a title="{SUBCAT.title}" href="{SUBCAT.link}">{SUBCAT.title}</a></li>
				<!-- END: subcatloop -->
				<!-- BEGIN: subcatmore -->
				<li class="pull-right hidden-xs"><a title="{MORE.title}" href="{MORE.link}"><em class="fa fa-sign-out"></em></a></li>
				<!-- END: subcatmore -->
			</ul>
		</div>
		
		<div class="panel-body">
			<!-- BEGIN: image -->
			<a href="{CONTENT.link}" title="{CONTENT.title}"><img alt="{HOMEIMGALT}" id="imghome" src="{HOMEIMG}" width="{IMGWIDTH}" class="img-thumbnail pull-left" /></a>
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
		
			<!-- BEGIN: related -->
			<ul class="related">
				<!-- BEGIN: loop -->
				<li class="{CLASS}">
					<a title="{OTHER.title}" href="{OTHER.link}">{OTHER.title}</a>
				</li>
				<!-- END: loop -->
			</ul>
			<!-- END: related -->
		</div>
	</div>
</div>
<!-- END: listcat -->
<!-- END: main -->