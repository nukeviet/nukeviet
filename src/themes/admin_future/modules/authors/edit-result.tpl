<div class="card pb-1 pt-1">
    <div class="card-body">
        {$LANG->getModule('nv_admin_edit_result_title', $DATA.login)}
    </div>
    <div class="card-body">
        <div class="table-responsive-sm table-card">
            <table class="table table-sticky mb-0">
                <col style="width: 33.3333%;">
                <col style="width: 33.3333%;">
                <col style="width: 33.3333%;">
                <thead>
                    <tr>
                        <th class="text-nowrap text-capitalize">{$LANG->getModule('field')}</th>
                        <th class="text-nowrap">{$LANG->getModule('old_value')}</th>
                        <th class="text-nowrap">{$LANG->getModule('new_value')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$DATA.change item=row}
                    <tr>
                        <th>{$row.0}</th>
                        <td>{$row.1}</td>
                        <td>{$row.2}</td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        <a class="btn btn-primary" href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=edit&amp;admin_id={$DATA.admin_id}"><i class="fa-solid fa-pen"></i> {$LANG->getGlobal('edit')}</a>
        <a class="btn btn-primary" href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}"><i class="fa-solid fa-list"></i> {$LANG->getModule('main')}</a>
    </div>
</div>
