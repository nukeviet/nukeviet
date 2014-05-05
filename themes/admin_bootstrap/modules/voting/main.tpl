<!-- BEGIN: main -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th>{LANG.voting_id}</th>
				<th>{LANG.voting_title}</th>
				<th>{LANG.voting_stat}</th>
				<th>{LANG.voting_active}</th>
				<th>{LANG.voting_func}</th>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td class="center">{ROW.vid}</td>
				<td>{ROW.question}</td>
				<td>{ROW.totalvote} {LANG.voting_totalcm}</td>
				<td class="center">{ROW.status}</td>
				<td class="center">
					<em class="icon-edit icon-large">&nbsp;</em> <a href="{ROW.url_edit}">{GLANG.edit}</a> &nbsp;
					<em class="icon-trash icon-large">&nbsp;</em> <a href="javascript:void(0);" onclick="nv_del_content({ROW.vid}, '{ROW.checksess}')">{GLANG.delete}</a>
				</td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- END: main -->