<!-- BEGIN: main -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<td colspan="7"> {LANG.block_select_module}:
				<select name="module">
					<option value="">{LANG.block_select_module}</option>
					<!-- BEGIN: module -->
					<option value="{MODULE.key}"{MODULE.selected}>{MODULE.title}</option>
					<!-- END: module -->
				</select> {LANG.block_func}
				<select name="function">
					<option value="">{LANG.block_select_function}</option>
					<!-- BEGIN: function -->
					<option value="{FUNCTION.key}"{FUNCTION.selected}>{FUNCTION.title}</option>
					<!-- END: function -->
				</select></th>
			</tr>
			<tr>
				<th>{LANG.block_sort}</th>
				<th>{LANG.block_pos}</th>
				<th>{LANG.block_title}</th>
				<th>{LANG.block_file}</th>
				<td class="text-center">{LANG.block_active}</th>
				<td class="text-center">{LANG.functions}</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tfoot>
			<tr class="aright">
				<td colspan="7"><em class="fa fa-plus-sign fa-lg">&nbsp;</em> <a class="block_content" href="javascript:void(0);">{LANG.block_add}</a>&nbsp;&nbsp; <em class="fa fa-trash-o fa-lg">&nbsp;</em> <a class="delete_group" href="javascript:void(0);">{GLANG.delete}</a><span style="width: 100px; display: inline-block;">&nbsp;</span><span> <a id="checkall" href="javascript:void(0);">{LANG.block_checkall}</a>&nbsp;&nbsp; <a id="uncheckall" href="javascript:void(0);">{LANG.block_uncheckall}</a> </span></td>
			</tr>
		</tfoot>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td>
				<select class="order" title="{ROW.bid}">
					<!-- BEGIN: order -->
					<option value="{ORDER.key}"{ORDER.selected}>{ORDER.key}</option>
					<!-- END: order -->
				</select></td>
				<td>
				<select name="listpos" title="{ROW.bid}">
					<!-- BEGIN: position -->
					<option value="{POSITION.key}"{POSITION.selected}>{POSITION.title}</option>
					<!-- END: position -->
				</select></td>
				<td>{ROW.title}</td>
				<td>{ROW.module} {ROW.file_name}</td>
				<td class="text-center">{ROW.active}</td>
				<td class="text-center"><em class="fa fa-edit fa-lg">&nbsp;</em> <a class="block_content" title="{ROW.bid}" href="javascript:void(0);">{GLANG.edit}</a> <em class="fa fa-trash-o fa-lg">&nbsp;</em> <a class="delete" title="{ROW.bid}" href="javascript:void(0);">{GLANG.delete}</a></td>
				<td class="text-center"><label><input type="checkbox" name="idlist" value="{ROW.bid}"/></label></td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<script type="text/javascript">
	//<![CDATA[
	$(function() {
		$("a.block_content").click(function() {
			var bid = parseInt($(this).attr("title"));
			Shadowbox.open({
				content : '<iframe src="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}=themes&amp;{NV_OP_VARIABLE}=block_content&amp;bid=' + bid + '&amp;blockredirect={BLOCKREDIRECT}" border="1" frameborder="0" style="width:780px;height:450px"></iframe>',
				player : "html",
				height : 450,
				width : 780
			});
		});
		$("select[name=module]").change(function() {
			var module = $(this).val();
			window.location = "{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=blocks_func&module=" + module;
		});
		$("select[name=function]").change(function() {
			var module = $("select[name=module]").val();
			var func = $(this).val();
			window.location = "{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=blocks_func&module=" + module + "&func=" + func;
		});
		$("select.order").change(function() {
			$("select.order").attr({
				"disabled" : ""
			});
			var order = $(this).val();
			var bid = $(this).attr("title");
			$.ajax({
				type : "POST",
				url : "{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=blocks_change_order",
				data : "func_id={FUNC_ID}&order=" + order + "&bid=" + bid,
				success : function(data) {
					window.location = "{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=blocks_func&func={FUNC_ID}&module={SELECTEDMODULE}";
				}
			});
		});
		$("a.delete").click(function() {
			var bid = parseInt($(this).attr("title"));
			if (bid > 0 && confirm("{LANG.block_delete_per_confirm}")) {
				$.post("{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}=themes&{NV_OP_VARIABLE}=blocks_del", "bid=" + bid, function(theResponse) {
					alert(theResponse);
					window.location = "{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=blocks_func&func={FUNC_ID}";
				});
			}
		});
		$("a.delete_group").click(function() {
			var list = [];
			$("input[name=idlist]:checked").each(function() {
				list.push($(this).val());
			});
			if (list.length < 1) {
				alert("{LANG.block_error_noblock}");
				return false;
			}
			if (confirm("{LANG.block_delete_confirm}")) {
				$.ajax({
					type : "POST",
					url : "{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=blocks_del_group",
					data : "list=" + list,
					success : function(data) {
						alert(data);
						window.location = "{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=blocks_func&func={FUNC_ID}";
					}
				});
			}
			return false;
		});
		$("#checkall").click(function() {
			$("input[name=idlist]:checkbox").each(function() {
				$(this).prop("checked", true);
			});
		});
		$("#uncheckall").click(function() {
			$("input[name=idlist]:checkbox").each(function() {
				$(this).prop("checked", false);
			});
		});
		$("select[name=listpos]").change(function() {
			var pos = $(this).val();
			var bid = $(this).attr("title");
			if (confirm("{LANG.block_change_pos_warning} " + bid + " {LANG.block_change_pos_warning2}")) {
				$.ajax({
					type : "POST",
					url : "{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=blocks_change_pos",
					data : "bid=" + bid + "&pos=" + pos,
					success : function(data) {
						alert(data);
						window.location = "{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=blocks_func&func={FUNC_ID}";
					}
				});
			}
		});
	});
	//]]>
</script>
<!-- END: main -->