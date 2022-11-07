<!-- BEGIN: main -->
<div id="blocklist">
    <div class="row">
        <div class="col-xs-14">
            <select name="module" class="form-control d-inline-block margin-bottom w200">
                <option value="">{LANG.block_select_module}</option>
                <!-- BEGIN: module -->
                <option value="{MODULE.key}" {MODULE.selected}>{MODULE.title}</option>
                <!-- END: module -->
            </select>
            <!-- BEGIN: function -->
            <select name="function" class="form-control d-inline-block margin-bottom w200">
                <option value="">{LANG.block_select_function}</option>
                <!-- BEGIN: func -->
                <option value="{FUNCTION.key}" {FUNCTION.selected}>{FUNCTION.title}</option>
                <!-- END: func -->
            </select>
            <!-- END: function -->
        </div>
        <div class="col-xs-10 text-right">
            <a href="javascript:void(0);" class="btn btn-primary block_content add margin-bottom"><em class="fa fa-plus-circle fa-lg"></em>&nbsp;{LANG.block_add}</a>
            <a href="{URL_DBLOCK}" title="{LANG_DBLOCK}" class="btn btn-primary margin-bottom"><em class="fa fa-object-group fa-lg"></em>&nbsp;{LANG_DBLOCK}</a>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered">
            <tbody class="thead">
                <tr>
                    <th style="width:1%"><input type="checkbox" name="checkall" class="form-control checkall" /></th>
                    <th class="text-center text-nowrap" style="width:1%">{LANG.block_sort}</th>
                    <th class="text-center text-nowrap">{LANG.block_title}</th>
                    <th class="text-center text-nowrap">{LANG.of_module}</th>
                    <th class="text-center text-nowrap">{LANG.block_func_list}</th>
                    <th class="text-center text-nowrap">{LANG.dtime_type}</th>
                    <th class="text-center text-nowrap" style="width:1%">{LANG.status}</th>
                    <th class="text-center text-nowrap" style="width:1%">{LANG.functions}</th>
                </tr>
            </tbody>
            <tbody>
                <!-- BEGIN: loop -->
                <!-- BEGIN: tbody -->
            </tbody>
            <tbody>
                <!-- END: tbody -->
                <!-- BEGIN: tbody2 -->
                <tr>
                    <td colspan="8" class="block_pos">{LANG.block_pos}: <strong>{POSITION_NAME}</strong></td>
                </tr>
                <!-- END: tbody2 -->
                </tr>
                <tr class="item" data-id="{ROW.bid}" data-checkss="{ROW.checkss}">
                    <td style="width:1%"><input type="checkbox" name="idlist" value="{ROW.bid}" class="form-control checkitem" style="margin-top:8px" /></td>
                    <td style="width:1%">
                        <select class="form-control w50 {ROW.order_func}">
                            <!-- BEGIN: weight -->
                            <option value="{WEIGHT.key}" {WEIGHT.selected}>{WEIGHT.key}</option>
                            <!-- END: weight -->
                        </select>
                    </td>
                    <td>
                        <div style="margin-top:6px"><strong>{ROW.title}</strong></div>
                    </td>
                    <td>
                        <div style="margin-top:6px">{ROW.module} ({ROW.file_name})</div>
                    </td>
                    <td style="width: 25%">
                        <!-- BEGIN: all_func -->
                        <div style="margin-top:6px">{LANG.add_block_all_module}</div>
                        <!-- END: all_func -->
                        <!-- BEGIN: func_inmodule -->
                        <!-- BEGIN: more -->
                        <div><button type="button" class="btn btn-default viewlist">{LANG.click_to_view}</button></div><!-- END: more -->
                        <ul class="list-unstyled mb-0 funclist" <!-- BEGIN: more2 --> style="display: none;"
                            <!-- END: more2 -->>
                            <!-- BEGIN: item -->
                            <li><a href="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}=blocks&amp;func={FUNCID_INLIST}&amp;module={FUNC_INMODULE}"><span style="font-weight:bold">{FUNC_INMODULE}</span>: {FUNCNAME_INLIST}</a></li><!-- END: item -->
                        </ul>
                        <!-- END: func_inmodule -->
                    </td>
                    <td>
                        <div style="margin-top:6px">{ROW.dtime_type_format}</div>
                    </td>
                    <td class="text-center text-nowrap" style="width:1%">
                        <select name="act" class="form-control act">
                            <!-- BEGIN: status -->
                            <option value="{STATUS.val}" {STATUS.sel}>{STATUS.name}</option>
                            <!-- END: status -->
                        </select>
                    </td>
                    <td style="width: 1%">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default block_content edit"><em class="fa fa-edit"></em> {GLANG.edit}</button>
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li><a role="button" class="change_pos_block" href="javascript:void(0)"><em class="fa fa-th-large fa-fw"></em> {LANG.block_change_pos}</a></li>
                                <li><a role="button" class="delete_block" href="javascript:void(0)"><em class="fa fa-trash-o fa-fw"></em> {GLANG.delete}</a></li>
                            </ul>
                        </div>
                        <div class="modal fade change_pos" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <div class="modal-title"><strong>{LANG.block_change_pos}</strong></div>
                                    </div>
                                    <div class="modal-body">
                                        <select name="listpos" class="form-control" data-default="{ROW.position}">
                                            <!-- BEGIN: position -->
                                            <option value="{POSITION.key}" {POSITION.selected}>{POSITION.title}</option>
                                            <!-- END: position -->
                                        </select>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">{GLANG.close}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <!-- END: loop -->
            </tbody>
            <tfoot>
                <tr>
                    <td style="width:1%"><input type="checkbox" name="checkall" class="form-control checkall" style="margin-top:8px" /></td>
                    <td colspan="7">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="input-group w250">
                                    <select name="action" class="form-control d-inline-block">
                                        <option value="bls_act">{LANG.act_1}</option>
                                        <option value="bls_deact">{LANG.act_0}</option>
                                        <option value="blocks_show_device">{LANG.show_device}</option>
                                        <option value="delete_group">{GLANG.delete}</option>
                                    </select>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default bl_action">{GLANG.submit}</button>
                                    </span>
                                </div>
                            </div>
                            <div class="col-xs-12 text-right">
                                <button type="button" class="btn btn-primary block_weight"><i class="fa fa-refresh"></i> {LANG.block_weight}</button>
                            </div>
                        </div>
                    </td>
                </tr>
            </tfoot>
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
                            <input name="active_device" id="active_device_{ACTIVE_DEVICE.key}" type="checkbox" value="{ACTIVE_DEVICE.key}" {ACTIVE_DEVICE.checked} />&nbsp;{ACTIVE_DEVICE.title}
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
</div>
<script>
    //<![CDATA[
    var selectthemes = '{SELECTTHEMES}';
    var blockredirect = '{BLOCKREDIRECT}';
    var func_id = {FUNC_ID};
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