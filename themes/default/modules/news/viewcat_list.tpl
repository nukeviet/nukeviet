<!-- BEGIN: main -->
<table class="table-list-news">
	<tbody>
		<!-- BEGIN: cattitle -->
		<tr>
			<th colspan="2">
				<a title="{CAT.title}" href="{CAT.link}">{CAT.title}</a>
			</th>
		</tr>		
		<!-- END: cattitle -->
		<!-- BEGIN: viewcatloop -->
		<tr>
			<td>
				{NUMBER}
			</td>
			<td>
				<a title="{CONTENT.title}" href="{CONTENT.link}">{CONTENT.title}</a>
				<!-- BEGIN: adminlink -->
				<span style="padding-left:10px">
					{ADMINLINK}
				</span>
				<!-- END: adminlink -->                        
			</td>
		</tr>
		<!-- END: viewcatloop -->
	</tbody>
</table>
<!-- BEGIN: generate_page -->
<div class="generate_page">
	{GENERATE_PAGE}
</div>
<!-- END: generate_page -->	
<!-- END: main -->