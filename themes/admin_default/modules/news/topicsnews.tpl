<!-- BEGIN: main -->
<div id="module_show_list">
	<!-- BEGIN: data -->
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<td class="w20">&nbsp;</th>
					<th>{LANG.name}</th>
                    <th class="text-center">{LANG.content_publ_date}</th>
                    <th>{LANG.status}</th>
                    <th class="text-center">
                       <em title="{LANG.hitstotal}" class="fa fa-eye">&nbsp;</em>
                    </th>
                    <th class="text-center">
                       <em title="{LANG.numcomments}" class="fa fa-comment-o">&nbsp;</em>
                    </th>
					<td class="w100">&nbsp;</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="3"><em class="fa fa-check-square-o fa-lg">&nbsp;</em> <a id="checkall" href="javascript:void(0);">{LANG.checkall}</a>&nbsp;&nbsp; <em class="fa fa-square-o ">&nbsp;</em> <a id="uncheckall" href="javascript:void(0);">{LANG.uncheckall}</a>&nbsp;&nbsp; </span><span style="width:100px;display:inline-block">&nbsp;</span> <em class="fa fa-trash-o fa-lg">&nbsp;</em> <a id="delete-topic" href="{URL_DELETE}">{LANG.topic_del}</a></td>
					<td colspan="4">{GENERATE_PAGE}</td>
				</tr>
			</tfoot>
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td><input type="checkbox" name="newsid" value="{ROW.id}"/></td>
					<td class="text-left"><a target="_blank" href="{ROW.link}">{ROW.title}</a></td>
                    <td>{ROW.publtime}</td>
                    <td title="{ROW.status}">{ROW.status}</td>
                    <td class="text-center">{ROW.hitstotal}</td>
                    <td class="text-center">{ROW.hitscm}</td>
                    <td class="text-center">{ROW.delete}</td>
				</tr>
				<!-- END: loop -->
			</tbody>
		</table>
	</div>
	<!-- END: data -->
	<!-- BEGIN: empty -->
	<div class="alert alert-warning">{LANG.topic_nonews}</div>
	<!-- END: empty -->
</div>
<script type="text/javascript">
var LANG = [];
var CFG = [];
LANG.topic_nocheck = '{LANG.topic_nocheck}';
LANG.topic_delete_confirm = '{LANG.topic_delete_confirm}';
CFG.topicid = '{TOPICID}';
</script>
<!-- END: main -->