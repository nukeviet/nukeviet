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
				<th class="text-center">{LANG.block_active}</th>
				<th>{LANG.block_func_list}</th>
				<th class="text-center">{LANG.functions}</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tfoot>
			<tr class="text-right">
				<td colspan="8">
					<em class="fa fa-edit fa-lg">&nbsp;</em> <a class="block_weight" href="javascript:void(0);">{LANG.block_weight}</a>&nbsp;&nbsp;&nbsp;&nbsp; 
					<em class="fa fa-plus-circle fa-lg">&nbsp;</em> <a class="block_content" href="javascript:void(0);">{LANG.block_add}</a>&nbsp;&nbsp;&nbsp;&nbsp; 
					<em class="fa fa-trash-o fa-lg">&nbsp;</em> <a class="delete_group" href="javascript:void(0);">{GLANG.delete}</a>&nbsp;&nbsp;&nbsp;&nbsp; 
					<em class="fa fa-check-square-o fa-lg">&nbsp;</em><a id="checkall" href="javascript:void(0);">{LANG.block_checkall}</a>&nbsp;&nbsp;&nbsp;&nbsp; 
					<em class="fa fa-square-o fa-lg">&nbsp;</em><a id="uncheckall" href="javascript:void(0);">{LANG.block_uncheckall}</a>
				</td>
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
					<option value="">&nbsp;</option>
					<!-- BEGIN: position -->
					<option value="{POSITION.key}"{POSITION.selected}>{POSITION.title}</option>
					<!-- END: position -->
				</select></td>
				<td>{ROW.title}</td>
				<td>{ROW.module} {ROW.file_name}</td>
				<td class="text-center"><input type="checkbox" name="active" title="{ROW.bid}" id="change_active_{ROW.bid}" {ROW.active} /></td>
				<td>
				<!-- BEGIN: all_func -->
				{LANG.add_block_all_module}
				<!-- END: all_func -->
				<!-- BEGIN: func_inmodule -->
				<a href="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}=blocks_func&amp;func={FUNCID_INLIST}&amp;module={FUNC_INMODULE}"><span style="font-weight:bold">{FUNC_INMODULE}</span>: {FUNCNAME_INLIST}</a>
				<br />
				<!-- END: func_inmodule -->
				</td>
				<td class="text-center">
					<em class="fa fa-edit fa-lg">&nbsp;</em> <a class="block_content" title="{ROW.bid}" href="javascript:void(0);">{GLANG.edit}</a>
				 	<em class="fa fa-trash-o fa-lg">&nbsp;</em> <a class="delete_block" title="{ROW.bid}" href="javascript:void(0);">{GLANG.delete}</a>
				 </td>
				<td><input type="checkbox" name="idlist" value="{ROW.bid}"/></td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<script type="text/javascript">
//<![CDATA[
var selectthemes = '{SELECTTHEMES}';
var blockredirect = '{BLOCKREDIRECT}';
var blockcheckss = '{CHECKSS}';
LANG.block_delete_per_confirm = '{LANG.block_delete_per_confirm}';
LANG.block_weight_confirm = '{LANG.block_weight_confirm}';
LANG.block_error_noblock = '{LANG.block_error_noblock}';
LANG.block_delete_confirm = '{LANG.block_delete_confirm}';
//]]>
</script>
<!-- END: main -->