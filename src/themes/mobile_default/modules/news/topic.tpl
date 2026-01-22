<!-- BEGIN: main -->
<!-- BEGIN: topicdescription -->
<div class="news_column">
	<div class="alert alert-info clearfix">
		<h1>{TOPPIC_TITLE}</h1>
		<!-- BEGIN: image -->
		<img alt="{TOPPIC_TITLE}" src="{HOMEIMG1}" width="{IMGWIDTH1}" class="img-thumbnail pull-left imghome" />
		<!-- END: image -->
		<p>{TOPPIC_DESCRIPTION}</p>
	</div>
</div>
<!-- END: topicdescription -->
<!-- BEGIN: author_list_title -->
<h2 class="author-article-title">
    {AUTHOR_LIST_TITLE}
</h2>
<!-- END: author_list_title -->
<!-- BEGIN: topic -->
<div class="news_column panel panel-default">
	<div class="panel-body">
		<!-- BEGIN: homethumb -->
		<a href="{TOPIC.link}" title="{TOPIC.title}"><img alt="{TOPIC.alt}" src="{TOPIC.src}" width="{TOPIC.width}" class="img-thumbnail pull-left imghome" /></a>
		<!-- END: homethumb -->
		<!-- BEGIN: h2 -->
        <h2 class="h3"><a href="{TOPIC.link}" title="{TOPIC.title}">{TOPIC.title}</a></h2>
        <!-- END: h2 -->
        <!-- BEGIN: h3 -->
        <h3><a href="{TOPIC.link}" title="{TOPIC.title}">{TOPIC.title}</a></h3>
        <!-- END: h3 -->
		<p>
			<em class="fa fa-clock-o">&nbsp;</em><em>{TIME} {DATE}</em>
		</p>
		{TOPIC.hometext}
		<!-- BEGIN: adminlink -->
		<p class="text-right">
			{ADMINLINK}
		</p>
		<!-- END: adminlink -->
	</div>
</div>
<!-- END: topic -->
<!-- BEGIN: other -->
<ul class="related">
	<!-- BEGIN: loop -->
	<li>
		<a title="{TOPIC_OTHER.title}" href="{TOPIC_OTHER.link}">{TOPIC_OTHER.title}</a>
		<em>({TOPIC_OTHER.publtime})</em>
	</li>
	<!-- END: loop -->
</ul>
<!-- END: other -->

<!-- BEGIN: generate_page -->
<div class="text-center">
	{GENERATE_PAGE}
</div>
<!-- END: generate_page -->
<!-- END: main -->