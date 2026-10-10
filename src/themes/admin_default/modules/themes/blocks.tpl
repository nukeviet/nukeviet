<div id="blocklist" data-selectthemes="{$SELECTTHEMES}" data-blockredirect="" data-checkss="{$CHECKSS}" data-warning1="{$LANG->getModule('block_change_pos_warning')}" data-warning2="{$LANG->getModule('block_change_pos_warning2')}" data-error-noblock="{$LANG->getModule('block_error_noblock')}" data-del-confirm="{$LANG->getModule('block_delete_confirm')}" data-funcid="{$FUNC_ID}">
    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-wrap flex-md-nowrap justify-content-between gap-2">
                <div class="hstack gap-2">
                    <div>
                        <select name="module" class="form-select">
                            <option value="">{$LANG->getModule('block_select_module')}</option>
                            {foreach from=$MODLIST key=key item=title}
                            <option value="{$key}"{if $key eq $SELECTEDMODULE} selected{/if}>{$title}</option>
                            {/foreach}
                        </select>
                    </div>
                    {if $SET_BY_FUNC}
                    <div>
                        <select name="function" class="form-select">
                            <option value="">{$LANG->getModule('block_select_function')}</option>
                            {foreach from=$FUNCLIST key=key item=title}
                            <option value="{$key}"{if $key eq $FUNC_ID} selected{/if}>{$title}</option>
                            {/foreach}
                        </select>
                    </div>
                    {/if}
                </div>
                <div class="hstack gap-2">
                    <button type="button" class="btn btn-primary block_content add"><i class="fa-solid fa-circle-plus" data-icon="fa-circle-plus"></i> {$LANG->getModule('block_add')}</button>
                    <a href="{$URL_DBLOCK}" title="{$LANG_DBLOCK}" class="btn btn-primary"><i class="fa-solid fa-object-group" data-icon="fa-object-group"></i> {$LANG_DBLOCK}</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive-lg table-card">
                <table class="table align-middle table-sticky mb-0">
                    <thead class="text-muted">
                        <tr>
                            <th class="text-nowrap" style="width: 5%;">
                                <input type="checkbox" data-toggle="checkAll" name="checkAll" class="form-check-input m-0 align-middle" aria-label="{$LANG->getGlobal('toggle_checkall')}">
                            </th>
                            <th class="text-nowrap" style="width: 5%;">{$LANG->getModule('block_sort')}</th>
                            <th class="text-nowrap" style="width: 22%;">{$LANG->getModule('block_title')}</th>
                            <th class="text-nowrap" style="width: 22%;">{$LANG->getModule('of_module')}</th>
                            <th class="text-nowrap" style="width: 22%;">{$LANG->getModule('block_func_list')}</th>
                            <th class="text-nowrap" style="width: 14%;">{$LANG->getModule('dtime_type')}</th>
                            <th class="text-nowrap" style="width: 5%;">{$LANG->getModule('status')}</th>
                            <th class="text-nowrap" style="width: 5%;">{$LANG->getModule('functions')}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {assign var="md" value="" nocache}
                        {foreach from=$BLOCKLIST item=row}
                        {if $md eq '' or $md neq $row.position}
                        <tr>
                            <td colspan="8" class="bg-body-secondary">
                                {assign var="md" value=$row.position nocache}
                                {$LANG->getModule('block_pos')}: <strong>{isset($THEME_POS[$row.position]) ? $THEME_POS[$row.position].title : $row.position}</strong>
                            </td>
                        </tr>
                        {/if}
                        <tr class="item" data-id="{$row.bid}" data-checkss="{$row.checkss}">
                            <td>
                                <input type="checkbox" data-toggle="checkSingle" name="idlist" value="{$row.bid}" class="form-check-input m-0 align-middle" aria-label="{$LANG->getGlobal('toggle_checksingle')}">
                            </td>
                            <td>
                                <select class="form-select fw-75 {$row.order_func}" name="changew" data-current="{$SET_BY_FUNC ? $row.bweight : $row.weight}">
                                    {for $w=1 to $POSITIONLIST[$row.position]}
                                    <option value="{$w}"{if $w eq ($SET_BY_FUNC ? $row.bweight : $row.weight)} selected{/if}>{$w}</option>
                                    {/for}
                                </select>
                            </td>
                            <td><strong>{$row.title}</strong></td>
                            <td><div class="text-break">{$row.module} ({$row.file_name})</div></td>
                            <td>
                                {if $row.all_func}
                                {$LANG->getModule('add_block_all_module')}
                                {else}
                                <ul class="list-unstyled mb-0 funclist{if count($row.in_funcs) gt 2} d-none{/if}">
                                    {foreach from=$row.in_funcs item=func}
                                    <li>
                                        <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}&amp;func={$func.func_id}&amp;module={$func.in_module}">
                                            <span class="fw-medium">{$func.in_module}</span>: {$func.func_custom_name}
                                        </a>
                                    </li>
                                    {/foreach}
                                </ul>
                                {if count($row.in_funcs) gt 2}
                                <button type="button" class="btn btn-secondary viewlist"><i class="fa-solid fa-eye"></i> {$LANG->getModule('click_to_view')}</button>
                                {/if}
                                {/if}
                            </td>
                            <td>{$row.dtime_type_format}</td>
                            <td>
                                <select name="act" class="form-select fw-75 act" data-current="{$row.act}" data-success="{$LANG->getModule('block_update_success')}">
                                    {for $i=0 to 1}
                                    <option value="{$i}"{if $i eq $row.act} selected{/if}>{$LANG->getModule("act_`$i`")}</option>
                                    {/for}
                                </select>
                            </td>
                            <td>
                                <div class="btn-group flex-nowrap">
                                    <button type="button" class="btn btn-secondary text-nowrap block_content edit"><i class="fa-solid fa-pencil"></i> {$LANG->getGlobal('edit')}</button>
                                    <button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                        <span class="visually-hidden">{$LANG->getModule('functions')}</span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item change_pos_block" href="#"><i class="fa-solid fa-table-cells-large fa-fw"></i> {$LANG->getModule('block_change_pos')}</a></li>
                                        <li><a class="dropdown-item delete_block" href="#" data-confirm="{$LANG->getModule('block_delete_confirm')}"><i class="fa-solid fa-trash fa-fw text-danger"></i> {$LANG->getGlobal('delete')}</a></li>
                                    </ul>
                                </div>
                                <div class="modal fade change_pos" tabindex="-1" aria-labelledby="block_change_pos_title_{$row.bid}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <div class="modal-title fs-5 fw-medium" id="block_change_pos_title_{$row.bid}">{$LANG->getModule('block_change_pos')}</div>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
                                            </div>
                                            <div class="modal-body">
                                                <select name="listpos" class="form-select" data-default="{$row.position}">
                                                    {foreach from=$THEME_POS key=key item=value}
                                                    <option value="{$key}"{if $key eq $row.position} selected{/if}>{$value.title}</option>
                                                    {/foreach}
                                                </select>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{$LANG->getGlobal('close')}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        {/foreach}
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer border-top border-3 py-2">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                <div class="d-flex flex-wrap flex-sm-nowrap align-items-center">
                    <div class="me-2">
                        <input type="checkbox" data-toggle="checkAll" name="checkAll" class="form-check-input m-0 align-middle" aria-label="{$LANG->getGlobal('toggle_checkall')}">
                    </div>
                    <div class="input-group me-1 my-1">
                        <select id="element_action" name="action" class="form-select fw-150" aria-label="{$LANG->getGlobal('select_actions')}" aria-describedby="element_action_btn">
                            <option value="bls_act">{$LANG->getModule('act_1')}</option>
                            <option value="bls_deact">{$LANG->getModule('act_0')}</option>
                            <option value="blocks_show_device">{$LANG->getModule('show_device')}</option>
                            <option value="delete_group">{$LANG->getGlobal('delete')}</option>
                        </select>
                        <button class="btn btn-primary bl_action" type="button" id="element_action_btn">{$LANG->getGlobal('submit')}</button>
                    </div>
                </div>
                <div>
                    <button type="button" class="btn btn-primary block_weight" data-confirm="{$LANG->getModule('block_weight_confirm')}"><i class="fa-solid fa-arrows-rotate" data-icon="fa-arrows-rotate"></i> {$LANG->getModule('block_weight')}</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal_show_device" tabindex="-1" aria-labelledby="modal_show_device_lbl" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title fs-5 fw-medium" id="modal_show_device_lbl">{$LANG->getModule('show_device')}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-2">
                        {for $i=1 to 4}
                        <div class="col-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="active_device" value="{$i}" id="active_device_{$i}"{if $i eq 1} checked{/if}>
                                <label class="form-check-label" for="active_device_{$i}">
                                    {$LANG->getModule("show_device_`$i`")}
                                </label>
                            </div>
                        </div>
                        {/for}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary submit">{$LANG->getGlobal('submit')}</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{$LANG->getGlobal('cancel')}</button>
                </div>
            </div>
        </div>
    </div>
</div>
