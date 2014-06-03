<!-- BEGIN: main -->
<!-- BEGIN: listsubcat -->
<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title"><a href="{listsubcat.link}">{listsubcat.title}</a></h4>
	</div>
	<!-- BEGIN: itemcat -->
	<div class="panel-body">
		<!-- BEGIN: image -->
		<div class="col-xs-6 col-md-2">
			<a href="{itemcat.more_link}"> <img src="{itemcat.imagesrc}" alt="{itemcat.title}" class="img-thumbnail" /> </a>
		</div>
		<!-- END: image -->
		<h4><a href="{itemcat.more_link}">{itemcat.title}</a></h4>
		<small>
			<em class="fa fa-eye">&nbsp;</em> {LANG.viewcat_view_hits} {itemcat.view_hits}
			<span class="count-down"><em class="fa fa-download">&nbsp;</em> {LANG.viewcat_download_hits} {itemcat.download_hits}</span>
		</small>
		<p>
			{itemcat.introtext}
		</p>
		<p class="pull-right">
			<a href="{itemcat.more_link}">{LANG.readmore}...</a>
		</p>
	</div>
	<!-- BEGIN: related -->
	<ul class="list-group">
		<!-- BEGIN: loop -->
		<li class="list-group-item">
			<a href="{loop.more_link}">{loop.title}</a>
			<div class="right"><em class="fa fa-download">&nbsp;</em> {LANG.viewcat_download_hits} {loop.download_hits}</div>
		</li>
		<!-- END: loop -->
	</ul>
	<!-- END: related -->
	<!-- END: itemcat -->
</div>
<!-- END: listsubcat -->

<!-- BEGIN: listpostcat -->
<div class="panel panel-default">
	<div class="clearfix panel-body">
		<!-- BEGIN: is_admin -->
		<div class="pull-right">
			(<a href="{listpostcat.edit_link}">{GLANG.edit}</a>)
		</div>
		<!-- END: is_admin -->
		<!-- BEGIN: image -->
  		<div class="col-xs-6 col-md-2">
			<a href="{listpostcat.more_link}"><img src="{listpostcat.imagesrc}" alt="{listpostcat.title}" class="img-thumbnail" /></a>
		</div>
		<!-- END: image -->
		<h4><a title="{listpostcat.title}" href="{listpostcat.more_link}">{listpostcat.title}</a></h4>
		<p>
			{listpostcat.introtext}
		</p>
	</div>
	<div class="panel-footer">
		<ul class="list-inline">
			<li><em class="fa fa-upload">&nbsp;</em> {LANG.uploadtime}: {listpostcat.uploadtime}</li>
			<li><em class="fa fa-eye">&nbsp;</em> {LANG.view_hits} {listpostcat.view_hits}</li>
			<li><em class="fa fa-download">&nbsp;</em> {LANG.download_hits} {listpostcat.download_hits}</li>
			<li><em class="fa fa-comments">&nbsp;</em> {LANG.comment_hits}: {listpostcat.comment_hits}</li>	
		</ul>
	</div>
</div>
<!-- END: listpostcat -->
<!-- BEGIN: generate_page -->
<div class="page-nav m-bottom aright">
	{GENERATE_PAGE}
</div>
<!-- END: generate_page -->
<!-- END: main -->