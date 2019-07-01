<div class="card card-table card-footer-nav">
    <div class="card-body">
        <div class="row card-body-search-form">
            <div class="col-12 col-md-6">
                <div class="form-inline">
                    <label class="mr-1">{$LANG->get('block_select_module')}: </label>
                    <select name="module" class="form-control form-control-sm">
                        <option value="">{$LANG->get('block_select_module')}</option>
                        {foreach from=$ARRAY_MODULES item=module_i}
                        <option value="{$module_i.key}">{$module_i.title}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="mt-1">
                    <button class="btn btn-space btn-secondary block_content">
                        <i class="fa fa-plus-circle"></i> {$LANG->get('block_add')}
                    </button>
                    <a class="btn btn-space btn-secondary" href="{$URL_DBLOCK}">
                        <i class="fa fa-object-group"></i> {$LANG_DBLOCK}</a>
                    </a>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th style="width: 10%;">{$LANG->get('block_sort')}</th>
                        <th style="width: 17%;">{$LANG->get('block_pos')}</th>
                        <th style="width: 17%;">{$LANG->get('block_title')}</th>
                        <th style="width: 17%;">{$LANG->get('block_file')}</th>
                        <th style="width: 17%;">{$LANG->get('block_func_list')}</th>
                        <th style="width: 17%;" class="text-center">{$LANG->get('functions')}</th>
                        <th style="width: 5%;">&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$ARRAY_BLOCKS item=block_i}
                    <tr>
                        <td>
                            <select class="form-control form-control-xs order" title="{$block_i.bid}">
                                {for $weight=1 to $block_i.numposition}
                                <option value="{$weight}"{if $weight eq $block_i.weight} selected="selected"{/if}>{$weight}</option>
                                {/for}
                            </select>
                        </td>
                        <td>
                            <select name="listpos" title="{$block_i.bid}" class="form-control form-control-xs">
                                <option value="">&nbsp;</option>
                                {for $position=0 to $block_i.positionnum}
                                <option value="{$block_i.positions[$position]->tag}"{if $block_i.positions[$position]->tag eq $block_i.position} selected="selected"{/if}>{$block_i.positions[$position]->name}</option>
                                {/for}
                            </select>
                        </td>
                        <td>{$block_i.title}</td>
                        <td>{$block_i.module} {$block_i.file_name}</td>
                        <td>
                            {if $block_i.all_func eq 1}
                            {$LANG->get('add_block_all_module')}
                            {elseif isset($ARRAY_BLOCK_FUNCS[$block_i.bid])}
                            {foreach from=$ARRAY_BLOCK_FUNCS[$block_i.bid] item=funcs}
                            <a href="{$NV_BASE_ADMINURL}index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&amp;{$NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$NV_OP_VARIABLE}=blocks_func&amp;func={$funcs.func_id}&amp;module={$funcs.in_module}"><span style="font-weight:bold">{$funcs.in_module}</span>: {$funcs.func_custom_name}</a>
                            <br />
                            {/foreach}
                            {/if}
                        </td>
                        <td class="text-center">
                            <a href="#" class="btn btn-sm btn-hspace btn-secondary block_content" data-bid="{$block_i.bid}"><i class="icon icon-left fas fa-pencil-alt"></i> {$LANG->get('edit')}</a>
                            <a href="#" class="btn btn-sm btn-danger delete_block" data-bid="{$block_i.bid}"><i class="icon icon-left fas fa-trash-alt"></i> {$LANG->get('delete')}</a>
                        </td>
                        <td>
                            <label class="custom-control custom-control-sm custom-checkbox">
                                <input class="custom-control-input" type="checkbox" name="idlist" value="{$block_i.bid}"><span class="custom-control-label"></span>
                            </label>
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        <button class="btn btn-space btn-secondary block_content"><i class="fa fa-edit"></i> <a class="block_weight" href="javascript:void(0);">{$LANG->get('block_weight')}</a></button>
        <button class="btn btn-space btn-secondary block_content"><button class="btn btn-space btn-secondary block_content"><i class="fa fa-toggle-on"></i> <a class="blocks_show_device" href="javascript:void(0);">{$LANG->get('show_device')}</a></button>
        <button class="btn btn-space btn-secondary block_content"><i class="fa fa-trash-o"></i> <a class="delete_group" href="javascript:void(0);">{$LANG->get('delete')}</a></button>
        <button class="btn btn-space btn-secondary block_content"><i class="fa fa-check-square-o"></i><a id="checkall" href="javascript:void(0);">{$LANG->get('block_checkall')}</a></button>
        <button class="btn btn-space btn-secondary block_content"><i class="fa fa-square-o"></i><a id="uncheckall" href="javascript:void(0);">{$LANG->get('block_uncheckall')}</a></button>
    </div>
</div>
<script type="text/javascript">
var selectthemes = '{$SELECTTHEMES}';
var blockredirect = '{$BLOCKREDIRECT}';
var blockcheckss = '{$CHECKSS}';
LANG.block_delete_per_confirm = '{$LANG->get('block_delete_per_confirm')}';
LANG.block_weight_confirm = '{$LANG->get('block_weight_confirm')}';
LANG.block_error_noblock = '{$LANG->get('block_error_noblock')}';
LANG.block_delete_confirm = '{$LANG->get('block_delete_confirm')}';
</script>

{*
<!-- BEGIN: main -->
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <tfoot>
            <tr class="text-right">
                <td colspan="7">
                </td>
            </tr>
        </tfoot>
        <tbody>
            <!-- BEGIN: loop -->
            <tr>
                <td>
                </td>
                <td>
                </td>
                <td></td>
                <td></td>
                <td>
                </td>
                <td>
                 </td>
                <td><input type="checkbox"/></td>
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
<!-- END: main -->
*}
