{assign var="COUNT" value=$DEPARTMENTS|count}
{assign var="ARR_STATUS" value=[$LANG->getGlobal('disable'), $LANG->getGlobal('active'), $LANG->getModule('department_no_home')]}

<div class="card list" data-checkss="{$CHECKSS}" data-url="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}">
    <div class="card-body">
        {if empty($DEPARTMENTS)}
        <div class="alert alert-info mb-0">{$LANG->getModule('department_add')}</div>
        {else}
        <div class="table-responsive-lg table-card pb-1">
            <table class="table table-striped align-middle table-sticky mb-0">
                <thead>
                    <tr>
                        <th class="text-center text-nowrap" style="width:8%;">{$LANG->getModule('number')}</th>
                        <th class="text-nowrap" style="width:36%;">{$LANG->getModule('part_row_title')}</th>
                        <th class="text-nowrap" style="width:20%;">{$LANG->getGlobal('email')}</th>
                        <th class="text-nowrap" style="width:16%;">{$LANG->getGlobal('phonenumber')}</th>
                        <th class="text-center text-nowrap" style="width:10%;">{$LANG->getGlobal('status')}</th>
                        <th class="text-center text-nowrap" style="width:10%;">{$LANG->getModule('is_default')}</th>
                        {if !empty($smarty.const.NV_IS_SPADMIN)}<th class="text-center text-nowrap" style="width:1%;">{$LANG->getGlobal('actions')}</th>{/if}
                    </tr>
                </thead>
                <tbody>
                    {foreach $DEPARTMENTS as $ROW}
                    <tr class="item" data-id="{$ROW.id}">
                        <td class="text-center">
                            {if !empty($smarty.const.NV_IS_SPADMIN)}
                            <select class="form-select form-select-sm department_cweight fw-75" name="weight" data-default="{$ROW.weight}" aria-label="{$LANG->getModule('number')}">
                                {for $WEIGHT = 1 to $COUNT}
                                <option value="{$WEIGHT}" {if $WEIGHT == $ROW.weight}selected{/if}>{$WEIGHT}</option>
                                {/for}
                            </select>
                            {else}
                            {$ROW.weight}
                            {/if}
                        </td>
                        <td class="full_name{if !empty($ROW.is_default)} is-default{/if}">
                            <a href="#" class="department_view">{$ROW.full_name}</a>
                        </td>
                        <td class="text-nowrap">{$ROW.email}</td>
                        <td class="text-nowrap">{$ROW.phone}</td>
                        <td class="text-center">
                            {if !empty($smarty.const.NV_IS_SPADMIN)}
                            <select class="form-select form-select-sm department_cstatus fw-200" name="status" data-default="{$ROW.act}" aria-label="{$LANG->getGlobal('status')}">
                                {foreach $ARR_STATUS as $KEY => $STATUS}
                                <option value="{$KEY}" {if $KEY == $ROW.act}selected{/if}>{$STATUS}</option>
                                {/foreach}
                            </select>
                            {else}
                            {$ARR_STATUS[$ROW.act]}
                            {/if}
                        </td>
                        <td class="text-center">
                            {if !empty($smarty.const.NV_IS_SPADMIN)}
                            <input type="radio" name="is_default" class="form-check-input" value="{$ROW.id}" {if !empty($ROW.is_default)}checked{/if} aria-label="{$LANG->getModule('is_default_select')}">
                            {elseif !empty($ROW.is_default)}
                            <i class="fa-solid fa-check"></i>
                            {/if}
                        </td>
                        {if !empty($smarty.const.NV_IS_SPADMIN)}
                        <td class="text-center text-nowrap">
                            <button type="button" title="{$LANG->getGlobal('edit')}" aria-label="{$LANG->getGlobal('edit')}" class="btn btn-secondary btn-sm department_edit">
                                <i class="fa-solid fa-pencil"></i>
                            </button>
                            <button type="button" title="{$LANG->getGlobal('delete')}" aria-label="{$LANG->getGlobal('delete')}" class="btn btn-danger btn-sm department_del">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                        {/if}
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
        {/if}
    </div>
</div>

{if !empty($smarty.const.NV_IS_SPADMIN)}
<div class="text-center mt-3">
    <button type="button" title="{$LANG->getModule('department_add')}" data-url="{$OP_URL}" class="btn btn-primary department_add{if empty($DEPARTMENTS)} auto{/if}">
        <i class="fa-solid fa-plus-circle"></i> {$LANG->getModule('department_add')}
    </button>
</div>

<div class="modal fade" id="content" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="department_add_title" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header text-bg-primary">
                <h4 class="modal-title" id="department_add_title"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>
{/if}
