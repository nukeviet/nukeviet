<!-- BEGIN: main -->
<div id="users">
	<h2 class="line padding_0" style="margin-bottom:5px">{LANG.listusers}</h2>
	<div class="uinfo">
		<table class="tab1">
			<thead>
				<tr>
					<td style="witdh:20%;"><a href="{username}">{LANG.account}</a></td>
					<td style="witdh:20%;"><a href="{gender}">{LANG.gender}</a></td>
					<td style="witdh:20%;"><a href="{regdate}">{LANG.regdate}</a></td>
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
	</div>
</div>
<!-- END: main -->