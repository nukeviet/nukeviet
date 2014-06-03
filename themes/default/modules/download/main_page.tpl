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
					<h3><a title="{itemcat.title}" href="{itemcat.more_link}"><strong>{itemcat.title}</strong></a></h3>
					<ul class="list-inline">
						<li><em class="fa fa-user">&nbsp;</em> {LANG.author_name}: {itemcat.author_name}</li>
						<li><em class="fa fa-eye">&nbsp;</em> {LANG.view_hits}: {itemcat.view_hits}</li>
						<li><em class="fa fa-download">&nbsp;</em> {LANG.download_hits}: {itemcat.download_hits}</li>
						<li><em class="fa fa-comments">&nbsp;</em> {LANG.comment_hits}: {itemcat.comment_hits}</li>	
					</ul>
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
					<a title="{LANG.readmore}" href="{itemcat.more_link}">{LANG.readmore}</a> <em class="fa fa-sign-out">&nbsp;</em>
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