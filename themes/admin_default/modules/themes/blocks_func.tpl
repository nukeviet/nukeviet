<!-- BEGIN: main -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover form-inline">
		<thead>
			<tr>
				<td colspan="7">
				<label class="control-label">{LANG.block_select_module}:</label>
				<select name="module" class="form-control">
					<option value="">{LANG.block_select_module}</option>
					<!-- BEGIN: module -->
					<option value="{MODULE.key}"{MODULE.selected}>{MODULE.title}</option>
					<!-- END: module -->
				</select>
				<label class="control-label">{LANG.block_func}:</label>
				<select name="function" class="form-control">
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
				<td colspan="7" class="text-right">
					<em class="fa fa-plus-circle fa-lg">&nbsp;</em> <a class="block_content_fucs" href="javascript:void(0);">{LANG.block_add}</a>&nbsp;&nbsp;
					<em class="fa fa-trash-o fa-lg">&nbsp;</em> <a class="delete_group_fucs" href="javascript:void(0);">{GLANG.delete}</a>&nbsp;&nbsp;
					<em class="fa fa-check-square-o fa-lg">&nbsp;</em><a id="checkall" href="javascript:void(0);">{LANG.block_checkall}</a>&nbsp;&nbsp;
					<em class="fa fa-square-o fa-lg">&nbsp;</em><a id="uncheckall" href="javascript:void(0);">{LANG.block_uncheckall}</a>&nbsp;&nbsp;
				</td>
			</tr>
		</tfoot>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td>
				<select class="order_func form-control" title="{ROW.bid}">
					<!-- BEGIN: order -->
					<option value="{ORDER.key}"{ORDER.selected}>{ORDER.key}</option>
					<!-- END: order -->
				</select></td>
				<td>
				<select name="listpos_funcs" title="{ROW.bid}" class="form-control">
					<!-- BEGIN: position -->
					<option value="{POSITION.key}"{POSITION.selected}>{POSITION.title}</option>
					<!-- END: position -->
				</select></td>
				<td>{ROW.title}</td>
				<td>{ROW.module} {ROW.file_name}</td>
				<td class="text-center">{ROW.active}</td>
				<td class="text-center"><em class="fa fa-edit fa-lg">&nbsp;</em> <a class="block_content_fucs" title="{ROW.bid}" href="javascript:void(0);">{GLANG.edit}</a> <em class="fa fa-trash-o fa-lg">&nbsp;</em> <a class="delete_block_fucs" title="{ROW.bid}" href="javascript:void(0);">{GLANG.delete}</a></td>
				<td class="text-center"><label><input type="checkbox" name="idlist" value="{ROW.bid}"/></label></td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<script type="text/javascript">
//<![CDATA[
var blockredirect = '{BLOCKREDIRECT}';
var func_id = '{FUNC_ID}';
var selectedmodule = '{SELECTEDMODULE}';
var blockcheckss = '{CHECKSS}';
LANG.block_delete_per_confirm = '{LANG.block_delete_per_confirm}';
LANG.block_weight_confirm = '{LANG.block_weight_confirm}';
LANG.block_error_noblock = '{LANG.block_error_noblock}';
LANG.block_delete_confirm = '{LANG.block_delete_confirm}';
LANG.block_change_pos_warning = '{LANG.block_change_pos_warning}';
LANG.block_change_pos_warning2 = '{LANG.block_change_pos_warning2}';
//]]>
</script>
<!-- END: main -->