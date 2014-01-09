<!-- BEGIN: main -->
<table class="tab1">
	<thead>
		<tr class="center">
			<td>&nbsp;</td>
			<td>{LANG.comment_email}</td>
			<td>{LANG.comment_content}</td>
			<td>{LANG.comment_topic}</td>
			<td>{LANG.comment_status}</td>
			<td class="w100">{LANG.comment_funcs}</td>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="3">
				<i class="icon-check icon-large">&nbsp;</i> <a id="checkall" href="javascript:void(0);">{LANG.comment_checkall}</a> &nbsp;&nbsp;
				<i class="icon-check-empty icon-large">&nbsp;</i> <a id="uncheckall" href="javascript:void(0);">{LANG.comment_uncheckall}</a>
				<span style="width:100px;display:inline-block">&nbsp;</span>
				<i class="icon-ok-circle icon-large">&nbsp;</i> 
				<a href="{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}=active_comment">{LANG.comment_disable}</a> 
				<i class="icon-ok icon-large">&nbsp;</i> 
				<a href="{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}=active_comment">{LANG.comment_enable}</a> 
				<i class="icon-trash icon-large">&nbsp;</i> 
				<a href="{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}=del_comment">{LANG.comment_delete}</a> 
			</td>
			<td colspan="3" class="center">
			<!-- BEGIN: generate_page -->
			{GENERATE_PAGE}
			<!-- END: generate_page -->
			</td>
		</tr>
	</tfoot>
	<tbody>
		<!-- BEGIN: loop -->
		<tr>
			<td class="center"><input name="commentid" type="checkbox" value="{ROW.cid}"/></td>
			<td>{ROW.email}</td>
			<td>{ROW.content}</td>
			<td><a target="_blank" href="{ROW.link}">{ROW.title}</a></td>
			<td class="center">{ROW.status}</td>
			<td class="center">
				<i class="icon-edit icon-large">&nbsp;</i> <a href="{ROW.linkedit}">{LANG.comment_edit}</a> &nbsp; 
				<i class="icon-trash icon-large">&nbsp;</i> <a href="{ROW.linkdelete}">{LANG.comment_delete}</a>
			</td>
		</tr>
		<!-- END: loop -->
	</tbody>
</table>
<script type="text/javascript">
	//<![CDATA[
	$("#checkall").click(function() {
		$("input:checkbox").each(function() {
			$(this).prop("checked", true);
		});
	});
	$("#uncheckall").click(function() {
		$("input:checkbox").each(function() {
			$(this).prop("checked", false);
		});
	});
	$("a.enable").click(function() {
		var list = [];
		$("input[name=commentid]:checked").each(function() {
			list.push($(this).val());
		});
		if (list.length < 1) {
			alert("{LANG.comment_nocheck}");
			return false;
		}
		$.ajax({
			type : "POST",
			url : "index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=active_comment",
			data : "list=" + list + "&active=1",
			success : function(data) {
				alert(data);
				window.location = "index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=comment";
			}
		});
		return false;
	});
	$("a.disable").click(function() {
		var list = [];
		$("input[name=commentid]:checked").each(function() {
			list.push($(this).val());
		});
		if (list.length < 1) {
			alert("{LANG.comment_nocheck}");
			return false;
		}
		$.ajax({
			type : "POST",
			url : "index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=active_comment",
			data : "list=" + list + "&active=0",
			success : function(data) {
				alert(data);
				window.location = "index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=comment";
			}
		});
		return false;
	});
	$("a.delete").click(function() {
		var list = [];
		$("input[name=commentid]:checked").each(function() {
			list.push($(this).val());
		});
		if (list.length < 1) {
			alert("{LANG.comment_nocheck}");
			return false;
		}
		if (confirm("{LANG.comment_delete_confirm}")) {
			$.ajax({
				type : "POST",
				url : "index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=del_comment",
				data : "list=" + list,
				success : function(data) {
					alert(data);
					window.location = "index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=comment";
				}
			});
		}
		return false;
	});
	$("a.deleteone").click(function() {
		if (confirm("{LANG.comment_delete_confirm}")) {
			var url = $(this).attr("href");
			$.ajax({
				type : "POST",
				url : url,
				data : "",
				success : function(data) {
					alert(data);
					window.location = "index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=comment";
				}
			});
		}
		return false;
	});
	//]]>
</script>
<!-- END: main -->