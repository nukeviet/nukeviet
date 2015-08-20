<!-- BEGIN: main -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th colspan="8">
					<span class="pull-left text-middle">{LANG.block_select_module}&nbsp;&nbsp;</span>
					<select name="module" class="pull-left form-control w200">
						<option value="">{LANG.block_select_module}</option>
						<!-- BEGIN: module -->
						<option value="{MODULE.key}"{MODULE.selected}>{MODULE.title}</option>
						<!-- END: module -->
					</select> &nbsp;&nbsp;&nbsp;&nbsp;
					<em class="fa fa-plus-circle fa-lg">&nbsp;</em> <a class="block_content" href="javascript:void(0);">{LANG.block_add}</a> &nbsp;&nbsp;&nbsp;&nbsp;
        			<em class="fa fa-object-group fa-lg">&nbsp;</em><a href="{URL_DBLOCK}" title="{LANG_DBLOCK}"><span>{LANG_DBLOCK}</span></a> 
				</th>
			</tr>
			<tr>
				<th>{LANG.block_sort}</th>
				<th>{LANG.block_pos}</th>
				<th>{LANG.block_title}</th>
				<th>{LANG.block_file}</th>
				<th>{LANG.block_func_list}</th>
				<th class="text-center">{LANG.functions}</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tfoot>
			<tr class="text-right">
				<td colspan="7">
					<em class="fa fa-edit fa-lg">&nbsp;</em> <a class="block_weight" href="javascript:void(0);">{LANG.block_weight}</a>&nbsp;&nbsp;&nbsp;&nbsp; 
					<em class="fa fa-toggle-on fa-lg">&nbsp;</em> <a class="blocks_show_device" href="javascript:void(0);">{LANG.show_device}</a>&nbsp;&nbsp;&nbsp;&nbsp; 
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
<div class="modal fade" id="modal_show_device">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">{LANG.show_device}</h4>
			</div>
			<div class="modal-body">
				<div class="row form-horizontal showoption">
					<!-- BEGIN: active_device -->
						<label id="active_{ACTIVE_DEVICE.key}" style="padding-right: 20px">
							<input name="active_device" id="active_device_{ACTIVE_DEVICE.key}" type="checkbox" value="{ACTIVE_DEVICE.key}"{ACTIVE_DEVICE.checked}/>&nbsp;{ACTIVE_DEVICE.title}
						</label>
					<!-- END: active_device -->
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary submit">{GLANG.submit}</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">{GLANG.cancel}</button>
			</div>
		</div>
	</div>
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