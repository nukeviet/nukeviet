<!-- BEGIN: main -->
<!-- BEGIN: topicdescription -->
<div class="listz-news clearfix m-bottom">
	<h1>{TOPPIC_TITLE}</h1>
	<!-- BEGIN: image -->
	<img class="s-border fl left" alt="{TOPPIC_TITLE}" src="{HOMEIMG1}" width="{IMGWIDTH1}" />
	<!-- END: image -->
	<h2>{TOPPIC_DESCRIPTION}</h2>
</div>
<!-- END: topicdescription -->
<!-- BEGIN: topic -->
<div class="box-border-shadow m-bottom listz-news">
	<div class="content-box clearfix">
		<!-- BEGIN: homethumb -->
		<a href="{TOPIC.link}" title="{TOPIC.title}"><img class="s-border fl left" alt="{TOPIC.alt}" src="{TOPIC.src}" width="{TOPIC.width}"/></a>
		<!-- END: homethumb -->
		<h4><a href="{TOPIC.link}" title="{TOPIC.title}">{TOPIC.title}</a></h4>
		<p>
			<span class="time">{LANG.pubtime}: {TIME} - {DATE}</span>
		</p>
		<p>
			{TOPIC.hometext}
		</p>
		<!-- BEGIN: adminlink -->
		<p style="text-align : right;">
			{ADMINLINK}
		</p>
		<!-- END: adminlink -->
	</div>
</div>
<!-- END: topic -->
<!-- BEGIN: other -->
<div class="other-news">
	<ul>
		<!-- BEGIN: loop -->
		<li>
			<a title="{TOPIC_OTHER.title}" href="{TOPIC_OTHER.link}">{TOPIC_OTHER.title}</a>
			<span class="date">({TOPIC_OTHER.publtime})</span>
		</li>
		<!-- END: loop -->
	</ul>
</div>
<!-- END: other -->
<!-- BEGIN: generate_page -->
<div class="acenter">
	{GENERATE_PAGE}
</div>
<!-- END: generate_page -->
<!-- END: main -->