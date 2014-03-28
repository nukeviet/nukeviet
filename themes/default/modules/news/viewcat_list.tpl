<!-- BEGIN: main -->
<!-- BEGIN: viewdescription -->
<div class="news_column">
	<div class="items clearfix">
		<h1>{CONTENT.title}</h1>
		<!-- BEGIN: image -->
		<img alt="{CONTENT.title}" src="{HOMEIMG1}" width="{IMGWIDTH1}" />
		<!-- END: image -->
		<h2>{CONTENT.description}</h2>
	</div>
</div>
<!-- END: viewdescription -->
<table class="table-list-news">
	<tbody>
		<!-- BEGIN: viewcatloop -->
		<tr>
			<td> {NUMBER} </td>
			<td><a title="{CONTENT.title}" href="{CONTENT.link}">{CONTENT.title}</a>
			<!-- BEGIN: adminlink -->
			<span style="padding-left:10px"> {ADMINLINK} </span>
			<!-- END: adminlink -->
			<!-- BEGIN: newday -->
			<span class="icon_new"></span>
			<!-- END: newday -->
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