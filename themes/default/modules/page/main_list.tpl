<!-- BEGIN: main -->
<div class="page">
	<!-- BEGIN: loop -->
	<div class="panel panel-default">
		<div class="panel-body">
			<!-- BEGIN: image -->
			<div class="pull-left img-thumbnail" style="margin: 0 7px 7px 0">
				<a href="{DATA.link}" title="{DATA.title}"><img src="{DATA.image}" alt="{DATA.imagealt}" width="100" /></a>
			</div>
			<!-- END: image -->
			<h3><a href="{DATA.link}" title="{DATA.title}">{DATA.title}</a></h3>
			<p>{DATA.description}</p>
		</div>
	</div>
	<!-- END: loop -->
	<div class="text-center">{GENERATE_PAGE}</div>
</div>
<!-- END: main -->