<div class="card card-table card-footer-nav">
    <div class="card-body">
        <div class="row card-body-search-form">
            <div class="col-12">
                <div class="form-inline">
                    <label class="mr-1">{$LANG->get('block_select_module')}: </label>
                    <select name="BlockFilterModule" class="form-control form-control-sm mr-1">
                        <option value="">{$LANG->get('block_select_module')}</option>
                        {foreach from=$ARRAY_MODULES item=module_i}
                        <option value="{$module_i.key}"{if $module_i.key eq $SELECTEDMODULE} selected="selected"{/if}>{$module_i.title}</option>
                        {/foreach}
                    </select>
                    <label class="mr-1">{$LANG->get('block_func')}: </label>
                    <select name="BlockFuncFilterFunction" class="form-control form-control-sm mr-1">
                        <option value="">{$LANG->get('block_select_function')}</option>
                        {foreach from=$ARRAY_FUNCTIONS item=function_i}
                        <option value="{$function_i.key}"{if $function_i.key eq $FUNC_ID} selected="selected"{/if}>{$function_i.title}</option>
                        {/foreach}
                    </select>
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
                        <th style="width: 17%;" class="text-center">{$LANG->get('block_active')}</th>
                        <th style="width: 17%;" class="text-center">{$LANG->get('functions')}</th>
                        <th style="width: 5%;">&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$ARRAY_BLOCKS item=block_i}
                    <tr>
                        <td>
                            <select class="form-control form-control-xs blockFuncChangeOrder" data-bid="{$block_i.bid}">
                                {for $weight=1 to $block_i.numposition}
                                <option value="{$weight}"{if $weight eq $block_i.bweight} selected="selected"{/if}>{$weight}</option>
                                {/for}
                            </select>
                        </td>
                        <td>
                            <select name="listpos_funcs" data-bid="{$block_i.bid}" class="form-control form-control-xs">
                                {for $position=0 to $block_i.positionnum}
                                <option value="{$block_i.positions[$position]->tag}"{if $block_i.positions[$position]->tag eq $block_i.position} selected="selected"{/if}>{$block_i.positions[$position]->name}</option>
                                {/for}
                            </select>
                        </td>
                        <td>{$block_i.title}</td>
                        <td>{$block_i.module} {$block_i.file_name}</td>
                        <td class="text-center">
                            {if $block_i.act}
                            {$LANG->get('yes')}
                            {else}
                            {$LANG->get('no')}
                            {/if}
                        </td>
                        <td class="text-center">
                            <a href="#" class="btn btn-sm btn-hspace btn-secondary block_content_fucs" data-bid="{$block_i.bid}"><i class="icon icon-left fas fa-pencil-alt"></i> {$LANG->get('edit')}</a>
                            <a href="#" class="btn btn-sm btn-danger delete_block_fucs" data-bid="{$block_i.bid}"><i class="icon icon-left fas fa-trash-alt"></i> {$LANG->get('delete')}</a>
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
        <button class="btn btn-space btn-secondary block_content_fucs" data-bid="0"><i class="fas fa-plus-circle"></i> {$LANG->get('block_add')}</button>
        <button class="btn btn-space btn-secondary delete_group_fucs"><i class="fa fa-trash-alt"></i> {$LANG->get('delete')}</button>
        <button class="btn btn-space btn-secondary" id="checkall"><i class="fas fa-check-square"></i> {$LANG->get('block_checkall')}</button>
        <button class="btn btn-space btn-secondary" id="uncheckall"><i class="far fa-square"></i> {$LANG->get('block_uncheckall')}</button>
    </div>
</div>
<script type="text/javascript">
var blockredirect = '{$BLOCKREDIRECT}';
var func_id = '{$FUNC_ID}';
var selectedmodule = '{$SELECTEDMODULE}';

LANG.block_delete_per_confirm = '{$LANG->get('block_delete_per_confirm')}';
LANG.block_weight_confirm = '{$LANG->get('block_weight_confirm')}';
LANG.block_error_noblock = '{$LANG->get('block_error_noblock')}';
LANG.block_delete_confirm = '{$LANG->get('block_delete_confirm')}';
LANG.block_change_pos_warning = '{$LANG->get('block_change_pos_warning')}';
LANG.block_change_pos_warning2 = '{$LANG->get('block_change_pos_warning2')}';
</script>
