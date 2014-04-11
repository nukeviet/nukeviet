<!-- BEGIN: main -->
<!-- BEGIN: viewdescription -->
<div class="news_column">
	<div class="alert alert-info clearfix">
		<h3>{CONTENT.title}</h3>
		<!-- BEGIN: image -->
		<img alt="{CONTENT.title}" id="imghome" src="{HOMEIMG1}" width="{IMGWIDTH1}" class="img-thumbnail pull-left" />
		<!-- END: image -->
		<p class="text-justify">{CONTENT.description}</p>
	</div>
</div>
<!-- END: viewdescription -->
<div class="news_column">
	<!-- BEGIN: catcontent -->
	<!-- BEGIN: content -->
	<div class="panel panel-default clearfix">
		<div class="panel-body">
			<!-- BEGIN: image -->
			<a href="{NEWSTOP.link}" title="{NEWSTOP.title}"><img alt="{HOMEIMGALT0}" id="imghome" src="{HOMEIMG0}" width="{IMGWIDTH0}" class="img-thumbnail pull-left" /></a>
			<!-- END: image -->
			<h3>
				<a href="{NEWSTOP.link}" title="{NEWSTOP.title}">{NEWSTOP.title}</a>
				<!-- BEGIN: newday -->
				<span class="icon_new"></span>
				<!-- END: newday -->
			</h3>
			<p class="text-justify">
				{NEWSTOP.hometext}
			</p>
			<!-- BEGIN: adminlink -->
			<p class="text-right">
				{ADMINLINK}
			</p>
			<!-- END: adminlink -->
		</div>
		<!-- END: content -->
		<ul class="related">
			<!-- BEGIN: other -->
			<li>
				<a title="{NEWSTOP.title}" href="{NEWSTOP.link}">{NEWSTOP.title}</a>
			</li>
			<!-- END: other -->
		</ul>
	</div>
	<!-- END: catcontent -->
</div>
<!-- BEGIN: loopcat -->
<div class="news_column col-md-6">
	<div class="panel panel-default clearfix">
		<div class="panel-heading">
			<a title="{CAT.title}" href="{CAT.link}"><span>{CAT.title}</span></a>
		</div>
		
		<div class="panel-body">
			<!-- BEGIN: content -->
			<h3>
				<a href="{CONTENT.link}" title="{CONTENT.title}">{CONTENT.title}</a>
				<!-- BEGIN: newday -->
				<span class="icon_new"></span>
				<!-- END: newday -->
			</h3>
			<!-- BEGIN: image -->
			<a href="{CONTENT.link}" title="{CONTENT.title}"><img alt="{HOMEIMGALT01}" id="imghome" src="{HOMEIMG01}" width="{IMGWIDTH01}" class="img-thumbnail pull-left" /></a>
			<!-- END: image -->
			<p class="text-justify">
				{CONTENT.hometext}
			</p>
			<!-- BEGIN: adminlink -->
			<p class="text-right">
				{ADMINLINK}
			</p>
			<!-- END: adminlink -->
			<!-- END: content -->
			<ul class="related">
				<!-- BEGIN: other -->
				<li class="{CLASS}">
					<a title="{CONTENT.title}" href="{CONTENT.link}">{CONTENT.title}</a>
				</li>
				<!-- END: other -->
			</ul>
		</div>
	</div>
</div>
<!-- BEGIN: clear -->
<div class="clear"></div>
<!-- END: clear -->
<!-- END: loopcat -->
<div class="clear"></div>
<!-- END: main -->