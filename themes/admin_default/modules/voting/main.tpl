<!-- BEGIN: main -->
<table class="tab1">
	<thead>
		<tr>
			<td>{LANG.voting_id}</td>
			<td>{LANG.voting_title}</td>
			<td>{LANG.voting_stat}</td>
			<td>{LANG.voting_active}</td>
			<td>{LANG.voting_func}</td>
		</tr>
	</thead>
	<tbody>
		<!-- BEGIN: loop -->
		<tr>
			<td class="center">{ROW.vid}</td>
			<td>{ROW.question}</td>
			<td>{ROW.totalvote} {LANG.voting_totalcm}</td>
			<td class="center">{ROW.status}</td>
			<td class="center"><a class="edit_icon" href="{ROW.url_edit}">{GLANG.edit}</a> &nbsp;-&nbsp;<a class="delete_icon" href="javascript:void(0);" onclick="nv_del_content({ROW.vid}, '{ROW.checksess}')">{GLANG.delete}</a></td>
		</tr>
		<!-- END: loop -->
	</tbody>
</table>
<!-- END: main -->