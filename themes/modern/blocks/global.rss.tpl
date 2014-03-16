<!-- BEGIN: main  -->
<ul class="list_item">
	<!-- BEGIN: loop -->
	<li class="{DATA.class}">
		<a {DATA.target} title="{DATA.title}" href="{DATA.link}"><b>{DATA.text}</b></a>
		<br />
		<!-- BEGIN: pubDate -->
		<em>{DATA.pubDate}</em>
		<br />
		<!-- END: pubDate -->
		<!-- BEGIN: description -->
		{DATA.description}
		<!-- END: description -->
	</li>
	<!-- END: loop -->
</ul>
<!-- END: main -->