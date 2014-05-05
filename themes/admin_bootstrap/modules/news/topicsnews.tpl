<!-- BEGIN: main -->
<div id="module_show_list">
	<!-- BEGIN: data -->
	<table class="tab1">
		<thead>
			<tr>
				<td class="w20">&nbsp;</td>
				<td>{LANG.name}</td>
				<td class="w100">&nbsp;</td>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="3"><span> <a id="checkall" href="javascript:void(0);">{LANG.checkall}</a>&nbsp;&nbsp; <a id="uncheckall" href="javascript:void(0);">{LANG.uncheckall}</a>&nbsp;&nbsp; </span><span style="width:100px;display:inline-block">&nbsp;</span> <em class="icon-trash icon-large">&nbsp;</em> <a class="delete" href="{URL_DELETE}">{LANG.topic_del}</a></td>
			</tr>
		</tfoot>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td><input type="checkbox" name="newsid" value="{ROW.id}"/></td>
				<td class="left"><a target="_blank" href="{ROW.link}">{ROW.title}</a></td>
				<td class="center">{ROW.delete}</td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
	<!-- END: data -->
	<!-- BEGIN: empty -->
	<div class="quote">
		<blockquote>
			<span>{LANG.topic_nonews}</span>
		</blockquote>
	</div>
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