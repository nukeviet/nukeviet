{if $SHOW_ADD_NEW}
<div class="mb-3">
    <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}&amp;add" class="btn btn-success"><i class="fa-solid fa-plus-circle"></i> {$LANG->getModule('nv_admin_add')}</a>
</div>
{/if}

<div class="card">
    <div class="card-body">
        <div class="table-responsive-lg table-card pb-1">
            <table class="table table-striped align-middle table-sticky mb-0">
                <thead>
                    <tr>
                        <th class="text-nowrap" style="width: 10%;">{$LANG->getModule('weight')}</th>
                        <th class="text-nowrap" style="width: 20%;">{$LANG->getModule('title')}</th>
                        <th class="text-nowrap" style="width: 15%;">{$LANG->getModule('add_time')}</th>
                        <th class="text-nowrap" style="width: 15%;">{$LANG->getModule('exp_time')}</th>
                        <th class="text-nowrap text-center" style="width: 10%;">{$LANG->getModule('users')}</th>
                        <th class="text-nowrap text-center" style="width: 10%;">{$LANG->getGlobal('active')}</th>
                        <th class="text-nowrap text-center" style="width: 20%;">{$LANG->getGlobal('actions')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$GROUPS_LIST item=row}
                    <tr>
                        <td>
                            {if $row.show_weight}
                            <button id="group_weight_{$row.group_id}" data-toggle="changegroupweight" data-mod="weight" data-min="{$START_WEIGHT}" data-num="{$MAX_WEIGHT}" data-id="{$row.group_id}" data-current="{$row.weight}" data-tokend="{$smarty.const.NV_CHECK_SESSION}" data-msgerror="{$LANG->getModule('errorChangeWeight')}" type="button" class="btn btn-secondary btn-sm d-flex align-items-center gap-1 justify-content-between btn-dropdown-tool fw-75">
                                <span class="text">{$row.weight}</span><i class="fa-solid fa-caret-down"></i>
                            </button>
                            {else}
                            {$row.weight_text}
                            {/if}
                        </td>
                        <td class="text-start"><a title="{$LANG->getModule('users')}" href="{$row.link_userlist}">{$row.title}</a></td>
                        <td>{$row.add_time}</td>
                        <td>{$row.exp_time}</td>
                        <td class="text-danger text-center"><strong>{$row.number}</strong></td>
                        <td class="text-center"><input data-id="{$row.group_id}" data-tokend="{$smarty.const.NV_CHECK_SESSION}" type="checkbox" class="actGroup form-check-input" name="act_{$row.group_id}" value="1"{if $row.act} checked{/if}{if $row.disabled} disabled{/if}></td>
                        <td class="text-center">
                            {if $row.show_action}
                            <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}&amp;edit&amp;id={$row.group_id}" class="btn btn-secondary btn-sm" aria-label="{$LANG->getGlobal('edit')}"><i class="fa-solid fa-edit"></i> {$LANG->getGlobal('edit')}</a>
                            {if $row.can_delete}<a class="delGroup btn btn-danger btn-sm" href="#" data-id="{$row.group_id}" data-tokend="{$smarty.const.NV_CHECK_SESSION}" aria-label="{$LANG->getGlobal('delete')}"><i class="fa-solid fa-trash" data-icon="fa-trash"></i> {$LANG->getGlobal('delete')}</a>{/if}
                            {/if}
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>

{if $SHOW_ACTION_JS}
<div class="mt-3">
    <a class="btn btn-danger" href="#" data-toggle="delInactiveGroup" data-tokend="{$smarty.const.NV_CHECK_SESSION}" data-msgconfirm="{$LANG->getModule('delConfirm')} ?" aria-label="{$LANG->getModule('group_del_inactive')}"><i class="fa-solid fa-trash"></i> {$LANG->getModule('group_del_inactive')}</a>
</div>
{/if}
