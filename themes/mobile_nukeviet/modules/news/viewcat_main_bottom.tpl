<!-- BEGIN: main -->
<!-- BEGIN: listcat -->
<ul class="hd">
	<li class="cr">
		<a href="{CAT.link}">{CAT.title}</a>
	<li>
		<!-- BEGIN: subcatloop -->
	<li>
		<a href="{SUBCAT.link}">{SUBCAT.title}</a>
	</li>
	<!-- END: subcatloop -->
</ul>
<div class="clear"></div>
<div class="cbox">
	<!-- BEGIN: image -->
	<a href="{CONTENT.link}"><img class="fl" src="{HOMEIMG}" width="{IMGWIDTH}" /></a>
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
	<div class="clear"></div>
	<!-- BEGIN: related -->
	<ul class="list">
		<!-- BEGIN: loop -->
		<li class="{CLASS}">
			<a href="{OTHER.link}">{OTHER.title}</a>
		</li>
		<!-- END: loop -->
	</ul>
	<!-- END: related -->

</div>
<!-- END: listcat -->
<!-- END: main -->