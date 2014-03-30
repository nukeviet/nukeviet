<!-- BEGIN: main -->
<!-- BEGIN: catbox -->
<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
		<a title="{catbox.title}" href="{catbox.link}">{catbox.title}</a> 
		<!-- BEGIN: subcatbox -->
		<!-- BEGIN: listsubcat -->
		<span class="divider">></span> <a title="{listsubcat.title}" href="{listsubcat.link}">{listsubcat.title}</a>
		<!-- END: listsubcat -->
		<!-- BEGIN: more -->
		<em class="pull-right"><small><a title="{LANG.categories_viewall}" href="{MORE}">{LANG.categories_viewall}</a></small></em>
		<!-- END: more -->
		<!-- END: subcatbox -->
		</h4>
	</div>
	<div class="panel-body">
			<!-- BEGIN: itemcat -->
				<div>
					<h4><a title="{itemcat.title}" href="{itemcat.more_link}">{itemcat.title}</a></h4>
					<small class="note"><span class="glyphicon glyphicon-user"></span> {LANG.author_name}: {itemcat.author_name} - <span class="glyphicon glyphicon-zoom-in"></span> {LANG.view_hits}: {itemcat.view_hits} - <span class="glyphicon glyphicon-download-alt"></span> {LANG.download_hits}: {itemcat.download_hits}</small>
				</div>
				<!-- BEGIN: image -->
				<div class="col-xs-6 col-md-3">
					<a class="thumbnail" title="{itemcat.title}" href="{itemcat.more_link}"> <img src="{itemcat.imagesrc}" alt="{itemcat.title}"/> </a>
				</div>
				<!-- END: image -->
				<p>{itemcat.introtext}</p>
				<!-- BEGIN: adminlink -->
				<p class="text-right">
					<a href="{EDIT}">{GLANG.edit}</a> &divide; <a href="{DEL}" onclick="nv_del_row(this,{itemcat.id});return false;">{GLANG.delete}</a>
				</p>
				<!-- END: adminlink -->
				<div class="text-right">
					<a title="{LANG.readmore}" href="{itemcat.more_link}">{LANG.readmore}</a> <span class="glyphicon glyphicon-chevron-right"></span>
				</div>
			<!-- END: itemcat -->
	</div>
	<!-- BEGIN: related -->
	<ul class="list-group">
		<!-- BEGIN: loop -->
		<li class="list-group-item"><a title="{loop.title}" href="{loop.more_link}">{loop.title}</a></li>
		<!-- END: loop -->
	</ul>
	<!-- END: related -->
</div>
<!-- END: catbox -->
<!-- END: main -->