<!-- BEGIN: main -->
<!-- BEGIN: is_addfile_allow -->
<a href="{UPLOAD}" class="link_upload m-bottom">{LANG.upload}</a>
<!-- END: is_addfile_allow -->
<strong>{LANG.categories}</strong>
<ul id="navmenu-v" class="clearfix">
	<!-- BEGIN: catparent -->
	<li>
		<a href="{catparent.link}">{catparent.title}</a>
		<!-- BEGIN: subcatparent -->
		<ul>
			<!-- BEGIN: loopsubcatparent -->
			<li>
				<a href="{loopsubcatparent.link}">{loopsubcatparent.title}</a>
			</li>
			<!-- END: loopsubcatparent -->
		</ul>
		<!-- END: subcatparent -->
	</li>
	<!-- END: catparent -->
</ul>
<!-- END: main -->