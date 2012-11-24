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
	<!-- BEGIN: loop -->
	<tbody{ROW.class}>
		<tr>
			<td align="center">{ROW.vid}</td>
			<td>{ROW.question}</td>
			<td>{ROW.totalvote} {LANG.voting_totalcm}</td>
			<td align="center">{ROW.status}</td>
			<td align="center"><span class="edit_icon"><a href="{ROW.url_edit}">{GLANG.edit}</a></span>
			&nbsp;-&nbsp;<span class="delete_icon"><a href="javascript:void(0);" onclick="nv_del_content({ROW.vid}, '{ROW.checksess}')">{GLANG.delete}</a></span></td>
		</tr>
	</tbody>
	<!-- END: loop -->
</table>
<!-- END: main -->