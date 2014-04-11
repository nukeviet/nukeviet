<!-- BEGIN: main -->
<table class="tab1">
	<thead>
		<tr>
			<td><a href="{username}">{LANG.account}</a></td>
			<td style="witdh:50px"><a href="{gender}">{LANG.gender}</a></td>
			<td style="witdh:100px"><a href="{regdate}">{LANG.regdate}</a></td>
		</tr>
	</thead>
	<!-- BEGIN: list -->
	<tbody>
		<tr>
			<td><a href="{USER.link}"> {USER.username}
			<!-- BEGIN: fullname -->
			&nbsp;( {USER.full_name} )
			<!-- END: fullname -->
			</a></td>
			<td>{USER.gender}</td>
			<td class="fl">{USER.regdate}</td>
		</tr>
	</tbody>
	<!-- END: list -->
	<!-- BEGIN: generate_page -->
	<tfoot>
		<tr>
			<td colspan="3">{GENERATE_PAGE}</td>
		</tr>
	</tfoot>
	<!-- END: generate_page -->
</table>
<!-- END: main -->