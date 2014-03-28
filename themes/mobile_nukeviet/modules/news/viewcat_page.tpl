<!-- BEGIN: main -->
<!-- BEGIN: viewcatloop -->
<!-- BEGIN: image -->
<a href="{CONTENT.link}"><img class="fl" src="{HOMEIMG1}" width="{IMGWIDTH1}" /></a>
<!-- END: image -->
<h3>
	<a href="{CONTENT.link}">{CONTENT.title}</a>
	<!-- BEGIN: newday -->
	<span class="icon_new"></span>
	<!-- END: newday -->	
</h3>
<p>
	{CONTENT.hometext}
</p>
<!-- BEGIN: adminlink -->
<p>
	{ADMINLINK}
</p>
<!-- END: adminlink -->
<div class="clear hr"></div>
<!-- END: viewcatloop -->
<!-- BEGIN: related -->
<h2>{ORTHERNEWS}</h2>
<ul class="list">
	<!-- BEGIN: loop -->
	<li>
		<a href="{RELATED.link}">{RELATED.title} <span class="smll">({RELATED.publtime})</span></a>
		<!-- BEGIN: newday -->
		<span class="icon_new"></span>
		<!-- END: newday -->
	</li>
	<!-- END: loop -->
</ul>
<!-- END: related -->
<!-- END: main -->