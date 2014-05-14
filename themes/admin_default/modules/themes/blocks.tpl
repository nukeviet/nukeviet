<!-- BEGIN: main -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th colspan="8"> <span class="pull-left text-middle">{LANG.block_select_module}&nbsp;&nbsp;</span>
				<select name="module" class="form-control w200">
					<option value="">{LANG.block_select_module}</option>
					<!-- BEGIN: module -->
					<option value="{MODULE.key}"{MODULE.selected}>{MODULE.title}</option>
					<!-- END: module -->
				</select></th>
			</tr>
			<tr>
				<th>{LANG.block_sort}</th>
				<th>{LANG.block_pos}</th>
				<th>{LANG.block_title}</th>
				<th>{LANG.block_file}</th>
				<td class="text-center">{LANG.block_active}</th>
				<th>{LANG.block_func_list}</th>
				<td class="text-center">{LANG.functions}</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tfoot>
			<tr class="text-right">
				<td colspan="8"><em class="fa fa-edit fa-lg">&nbsp;</em> <a class="block_weight" href="javascript:void(0);">{LANG.block_weight}</a>&nbsp;&nbsp;&nbsp;&nbsp; <em class="fa fa-plus-circle fa-lg">&nbsp;</em> <a class="block_content" href="javascript:void(0);">{LANG.block_add}</a>&nbsp;&nbsp;&nbsp;&nbsp; <em class="fa fa-trash-o fa-lg">&nbsp;</em> <a class="delete_group" href="javascript:void(0);">{GLANG.delete}</a><span style="width:100px;display:inline-block">&nbsp;</span><span> <em class="fa fa-check-square-o fa-lg">&nbsp;</em><a id="checkall" href="javascript:void(0);">{LANG.block_checkall}</a>&nbsp;&nbsp; <em class="fa fa-square-o fa-lg">&nbsp;</em><a id="uncheckall" href="javascript:void(0);">{LANG.block_uncheckall}</a> </span></td>
			</tr>
		</tfoot>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td>
				<select class="form-control order" title="{ROW.bid}">
					<!-- BEGIN: weight -->
					<option value="{WEIGHT.key}"{WEIGHT.selected}>{WEIGHT.key}</option>
					<!-- END: weight -->
				</select></td>
				<td>
				<select name="listpos" title="{ROW.bid}" class="form-control">
					<!-- BEGIN: position -->
					<option value="{POSITION.key}"{POSITION.selected}>{POSITION.title}</option>
					<!-- END: position -->
				</select></td>
				<td>{ROW.title}</td>
				<td>{ROW.module} {ROW.file_name}</td>
				<td class="text-center">{ROW.active}</td>
				<td>
				<!-- BEGIN: all_func -->
				{LANG.add_block_all_module}
				<!-- END: all_func -->
				<!-- BEGIN: func_inmodule -->
				<a href="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}=blocks_func&amp;func={FUNCID_INLIST}&amp;module={FUNC_INMODULE}"><span style="font-weight:bold">{FUNC_INMODULE}</span>: {FUNCNAME_INLIST}</a>
				<br />
				<!-- END: func_inmodule -->
				</td>
				<td class="text-center"><em class="fa fa-edit fa-lg">&nbsp;</em> <a class="block_content" title="{ROW.bid}" href="javascript:void(0);">{GLANG.edit}</a> <em class="fa fa-trash-o fa-lg">&nbsp;</em> <a class="delete" title="{ROW.bid}" href="javascript:void(0);">{GLANG.delete}</a></td>
				<td><input type="checkbox" name="idlist" value="{ROW.bid}"/></td>
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
			nv_open_browse("{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}=themes&{NV_OP_VARIABLE}=block_content&selectthemes={SELECTTHEMES}&bid=" + bid + "&blockredirect={BLOCKREDIRECT}", "ChangeBlock", 800, 500, "resizable=no,scrollbars=yes,toolbar=no,location=no,status=no");
		});
		$("select.order").change(function() {
			$("select.order").attr({
				"disabled" : ""
			});
			var order = $(this).val();
			var bid = $(this).attr("title");
			$.ajax({
				type : "POST",
				url : "{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=blocks_change_order_group",
				data : "order=" + order + "&bid=" + bid,
				success : function(data) {
					window.location = "{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=blocks";
				}
			});
		});
		$("select[name=module]").change(function() {
			var module = $(this).val();
			window.location = "{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=blocks_func&module=" + module;
		});
		$("a.delete").click(function() {
			var bid = parseInt($(this).attr("title"));
			if (bid > 0 && confirm("{LANG.block_delete_per_confirm}")) {
				$.post("{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}=themes&{NV_OP_VARIABLE}=blocks_del", "bid=" + bid, function(theResponse) {
					alert(theResponse);
					window.location = "{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=blocks";
				});
			}
		});
		$("a.block_weight").click(function() {
			if (confirm("{LANG.block_weight_confirm}")) {
				$.post("{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}=themes&{NV_OP_VARIABLE}=blocks_reset_order", "checkss={CHECKSS}", function(theResponse) {
					alert(theResponse);
					window.location = "{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=blocks";
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
						window.location = "{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=blocks";
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
			$.ajax({
				type : "POST",
				url : "{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=blocks_change_pos",
				data : "bid=" + bid + "&pos=" + pos,
				success : function(data) {
					alert(data);
					window.location = "{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=blocks";
				}
			});
		});
	});
	//]]>
</script>
<!-- END: main -->