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
			<a class="thumbnail" href="{itemcat.more_link}"> <img src="{itemcat.imagesrc}" alt="{itemcat.title}" /> </a>
		</div>
		<!-- END: image -->
		<h4><a href="{itemcat.more_link}">{itemcat.title}</a></h4>
		<small>
			<span class="glyphicon glyphicon-zoom-in"></span> {LANG.viewcat_view_hits} {itemcat.view_hits}
			<span class="count-down"><span class="glyphicon glyphicon-download-alt"></span> {LANG.viewcat_download_hits} {itemcat.download_hits}</span>
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
			<div class="right"><span class="glyphicon glyphicon-floppy-save"></span> {LANG.viewcat_download_hits} {loop.download_hits}</div>
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
			<a href="#" class="thumbnail"><img src="{listpostcat.imagesrc}" alt="{listpostcat.title}" /></a>
		</div>
		<!-- END: image -->
		<h4><a title="{listpostcat.title}" href="{listpostcat.more_link}">{listpostcat.title}</a></h4>
		<p>
			{listpostcat.introtext}
		</p>
	</div>
	<div class="panel-footer">
		<span class="glyphicon glyphicon-time"></span> {LANG.uploadtime}: {listpostcat.uploadtime}
		<span class="count-comments"><span class="glyphicon glyphicon-zoom-in"></span> {LANG.view_hits} {listpostcat.view_hits}</span>
		<span class="count-down"><span class="glyphicon glyphicon-download-alt"></span> {LANG.download_hits} {listpostcat.download_hits}</span>
	</div>
</div>
<!-- END: listpostcat -->
<!-- BEGIN: generate_page -->
<div class="page-nav m-bottom aright">
	{GENERATE_PAGE}
</div>
<!-- END: generate_page -->
<!-- END: main -->