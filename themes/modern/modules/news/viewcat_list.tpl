<!-- BEGIN: main -->
<!-- BEGIN: viewdescription -->
<div class="listz-news">
	<h1>{CONTENT.title}</h1>
	<!-- BEGIN: image -->
	<img class="s-border fl left" alt="{CONTENT.title}" src="{HOMEIMG1}" width="{IMGWIDTH1}" />
	<!-- END: image -->
	<h2>{CONTENT.description}</h2>
</div>
<!-- END: viewdescription -->
<table class="table-list-news">
	<tbody>
		<!-- BEGIN: cattitle -->
		<tr>
			<th colspan="2"><a title="{CAT.title}" href="{CAT.link}">{CAT.title}</a></th>
		</tr>
		<!-- END: cattitle -->
		<!-- BEGIN: viewcatloop -->
		<tr>
			<td> {NUMBER} </td>
			<td>
				<a title="{CONTENT.title}" href="{CONTENT.link}">{CONTENT.title}</a>
				<!-- BEGIN: newday -->
				<span class="icon_new"></span>
				<!-- END: newday -->
				<!-- BEGIN: adminlink -->
				<span class="aright"> {ADMINLINK} </span>
				<!-- END: adminlink -->
			</td>
		</tr>
		<!-- END: viewcatloop -->
	</tbody>
</table>
<!-- BEGIN: generate_page -->
<div class="acenter">
	{GENERATE_PAGE}
</div>
<!-- END: generate_page -->
<!-- END: main -->