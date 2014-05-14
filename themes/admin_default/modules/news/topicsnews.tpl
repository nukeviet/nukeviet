<!-- BEGIN: main -->
<div id="module_show_list">
	<!-- BEGIN: data -->
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<td class="w20">&nbsp;</th>
					<th>{LANG.name}</th>
					<td class="w100">&nbsp;</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="3"><em class="fa fa-check-square-o fa-lg">&nbsp;</em> <a id="checkall" href="javascript:void(0);">{LANG.checkall}</a>&nbsp;&nbsp; <em class="fa fa-square-o ">&nbsp;</em> <a id="uncheckall" href="javascript:void(0);">{LANG.uncheckall}</a>&nbsp;&nbsp; </span><span style="width:100px;display:inline-block">&nbsp;</span> <em class="fa fa-trash-o fa-lg">&nbsp;</em> <a class="delete" href="{URL_DELETE}">{LANG.topic_del}</a></td>
				</tr>
			</tfoot>
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td><input type="checkbox" name="newsid" value="{ROW.id}"/></td>
					<td class="text-left"><a target="_blank" href="{ROW.link}">{ROW.title}</a></td>
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
$('#checkall').click(function() {
	$('input:checkbox').each(function() {
		$(this).attr('checked', 'checked');
	});
});
$('#uncheckall').click(function() {
	$('input:checkbox').each(function() {
		$(this).removeAttr('checked');
	});
});
$('a.delete').click(function() {
	var list = [];
	$('input[name=newsid]:checked').each(function() {
		list.push($(this).val());
	});
	if (list.length < 1) {
		alert('{LANG.topic_nocheck}');
		return false;
	}
	if (confirm('{LANG.topic_delete_confirm}')) {
		$.ajax({
			type : 'POST',
			url : 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=topicdelnews',
			data : 'list=' + list,
			success : function(data) {
				alert(data);
				window.location = 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=topicsnews&topicid={TOPICID}';
			}
		});
	}
	return false;
});
</script>
<!-- END: main -->