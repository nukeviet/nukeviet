<div class="card card-table card-footer-nav">
    <div class="card-body">
        <div class="row card-body-search-form">
            <div class="col-12 col-md-6">
                <div class="form-inline">
                    <label class="mr-1">{$LANG->get('block_select_module')}: </label>
                    <select name="BlockFilterModule" class="form-control form-control-sm">
                        <option value="">{$LANG->get('block_select_module')}</option>
                        {foreach from=$ARRAY_MODULES item=module_i}
                        <option value="{$module_i.key}">{$module_i.title}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="text-right">
                    <button class="btn btn-space btn-secondary block_content mt-1" data-bid="0">
                        <i class="fa fa-plus-circle"></i> {$LANG->get('block_add')}
                    </button>
                    <a class="btn btn-space btn-secondary mt-1" href="{$URL_DBLOCK}">
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
                            <select class="form-control form-control-xs blockChangeOrder" data-bid="{$block_i.bid}">
                                {for $weight=1 to $block_i.numposition}
                                <option value="{$weight}"{if $weight eq $block_i.weight} selected="selected"{/if}>{$weight}</option>
                                {/for}
                            </select>
                        </td>
                        <td>
                            <select name="blockListPos" data-bid="{$block_i.bid}" class="form-control form-control-xs">
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
                            {assign var="bnumfuncs" value=sizeof($ARRAY_BLOCK_FUNCS[$block_i.bid])}
                            {if $bnumfuncs gt 1}
                            <button class="btn btn-secondary btn-sm" data-toggle="collapse" data-target="#collapseFuncs{$block_i.bid}" aria-expanded="false" aria-controls="collapseFuncs{$block_i.bid}">{$bnumfuncs} functions <i class="fas fa-chevron-down"></i></button>
                            {/if}
                            <div class="collapse{if $block_i.all_func eq 1 or $bnumfuncs lt 2} show{/if} bCollapseFuncs" id="collapseFuncs{$block_i.bid}">
                                <div{if $block_i.all_func neq 1 and $bnumfuncs gt 1} class="mt-1"{/if}>
                                    {foreach from=$ARRAY_BLOCK_FUNCS[$block_i.bid] item=funcs}
                                    <a href="{$NV_BASE_ADMINURL}index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&amp;{$NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$NV_OP_VARIABLE}=blocks_func&amp;func={$funcs.func_id}&amp;module={$funcs.in_module}"><span style="font-weight:bold">{$funcs.in_module}</span>: {$funcs.func_custom_name}</a>
                                    <br />
                                    {/foreach}
                                </div>
                            </div>
                            {/if}
                        </td>
                        <td class="text-center">
                            <a href="#" class="btn btn-sm btn-hspace btn-secondary block_content" data-bid="{$block_i.bid}"><i class="icon icon-left fas fa-pencil-alt"></i> {$LANG->get('edit')}</a>
                            <a href="#" class="btn btn-sm btn-danger delete_block" data-bid="{$block_i.bid}"><i class="icon icon-left fas fa-trash-alt"></i> {$LANG->get('delete')}</a>
                        </td>
                        <td>
                            <label class="custom-control custom-control-sm custom-checkbox">
                                <input class="custom-control-input" type="checkbox" name="idlist" value="{$block_i.bid}" data-activedevice="{$block_i.activedevice}"><span class="custom-control-label"></span>
                            </label>
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        <button class="btn btn-space btn-secondary block_weight"><i class="fas fa-sync"></i> {$LANG->get('block_weight')}</button>
        <button class="btn btn-space btn-secondary blocks_show_device"><i class="fas fa-toggle-on"></i> {$LANG->get('show_device')}</button>
        <button class="btn btn-space btn-secondary delete_group"><i class="fa fa-trash-alt"></i> {$LANG->get('delete')}</button>
        <button class="btn btn-space btn-secondary" id="checkall"><i class="fas fa-check-square"></i> {$LANG->get('block_checkall')}</button>
        <button class="btn btn-space btn-secondary" id="uncheckall"><i class="far fa-square"></i> {$LANG->get('block_uncheckall')}</button>
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

$(document).ready(function() {
    $('.bCollapseFuncs').on('shown.bs.collapse', function(e) {
        var btn = $('[aria-controls="' + $(this).attr('id') + '"]');
        if (btn) {
            btn.find('i').removeClass('fa-chevron-down').addClass('fa-chevron-up');
        }
    });
    $('.bCollapseFuncs').on('hidden.bs.collapse', function(e) {
        var btn = $('[aria-controls="' + $(this).attr('id') + '"]');
        if (btn) {
            btn.find('i').removeClass('fa-chevron-up').addClass('fa-chevron-down');
        }
    });
});
</script>

<div id="modal_show_device" tabindex="-1" role="dialog" class="modal fade colored-header colored-header-primary">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header-colored">
                <h3 class="modal-title">{$LANG->get('show_device')}</h3>
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close md-close"><span class="fas fa-times"></span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    {for $active_device=1 to 4}
                    <div class="col-12 col-md-6">
                        <label id="active_{$active_device}" class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" name="active_device" id="active_device_{$active_device}" value="{$active_device}"{if $active_device eq 1} checked="checked"{/if}><span class="custom-control-label"> {$LANG->get("show_device_`$active_device`")}</span>
                        </label>
                    </div>
                    {/for}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary submit">{$LANG->get('submit')}</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{$LANG->get('cancel')}</button>
            </div>
        </div>
    </div>
</div>
