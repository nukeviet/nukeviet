<!-- BEGIN: main -->
<p>
	<a href="{TOPPIC_LINK}" title="{TOPPIC_TITLE}"><strong>{TOPPIC_TITLE}</strong><a/>
	<br />
	{TOPPIC_DESCRIPTION}
</p>
<!-- BEGIN: topic -->
<!-- BEGIN: homethumb -->
<a href="{TOPIC.link}"><img class="fl" src="{TOPIC.src}" width="{TOPIC.width}"/></a>
<!-- END: homethumb -->
<h3><a href="{TOPIC.link}" title="{TOPIC.title}">{TOPIC.title}</a></h3>
<p>
	<span class="smll">{TIME} | {DATE}</span>
</p>
<p>
	{TOPIC.hometext}
</p>
<!-- BEGIN: adminlink -->
<p>
	{ADMINLINK}
</p>
<!-- END: adminlink -->
<div class="hr"></div>
<!-- END: topic -->
<!-- BEGIN: other -->
<ul class="list">
	<!-- BEGIN: loop -->
	<li>
		<a href="{TOPIC_OTHER.link}">{TOPIC_OTHER.title}</a><span class="smll">({TOPIC_OTHER.publtime})</span>
	</li>
	<!-- END: loop -->
</ul>
<!-- END: other -->
<!-- END: main -->