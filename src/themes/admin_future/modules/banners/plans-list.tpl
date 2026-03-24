<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">{$LANG->getModule('plans_list2')}</h5>
        <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=plan-content"
           class="btn btn-sm btn-primary">
            <i class="fa-solid fa-plus-circle"></i> {$LANG->getModule('add_plan')}
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive-lg table-card pb-1">
            <table class="table table-striped align-middle table-sticky mb-0">
                <thead>
                    <tr>
                        <th class="text-nowrap" style="width:35%;">{$LANG->getModule('title')}</th>
                        <th class="text-nowrap" style="width:20%;">{$LANG->getModule('blang')}</th>
                        <th class="text-nowrap" style="width:15%;">{$LANG->getModule('size')}</th>
                        <th class="text-nowrap text-center" style="width:10%;">{$LANG->getModule('is_act')}</th>
                        <th class="text-nowrap text-center" style="width:20%;">{$LANG->getGlobal('actions')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$ARRAY item=row}
                    <tr>
                        <td>{$row.title}</td>
                        <td>{$row.blang}</td>
                        <td>{$row.size}</td>
                        <td class="text-center">
                            <div class="form-check form-switch d-flex justify-content-center">
                                <input class="form-check-input" type="checkbox"
                                       id="planact_{$row.id}"
                                       {if $row.act} checked{/if}
                                       data-toggle="toggle-plan-act"
                                       data-id="{$row.id}"
                                       data-tokend="{$CHECKSS}"
                                       aria-label="{$row.title}">
                            </div>
                        </td>
                        <td class="text-center text-nowrap">
                            <div class="hstack gap-1 justify-content-center">
                                <a href="{$row.view_url}" class="btn btn-sm btn-info"
                                   aria-label="{$LANG->getGlobal('detail')}"
                                   data-bs-toggle="tooltip" title="{$LANG->getGlobal('detail')}">
                                    <i class="fa-solid fa-circle-info"></i>
                                </a>
                                <a href="{$row.edit_url}" class="btn btn-sm btn-secondary"
                                   aria-label="{$LANG->getGlobal('edit')}"
                                   data-bs-toggle="tooltip" title="{$LANG->getGlobal('edit')}">
                                    <i class="fa-solid fa-pencil"></i>
                                </a>
                                <a href="{$row.add_url}" class="btn btn-sm btn-success"
                                   aria-label="{$LANG->getModule('admin_add_banner')}"
                                   data-bs-toggle="tooltip" title="{$LANG->getModule('admin_add_banner')}">
                                    <i class="fa-solid fa-plus"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger"
                                        aria-label="{$LANG->getGlobal('delete')}"
                                        data-bs-toggle="tooltip" title="{$LANG->getGlobal('delete')}"
                                        data-toggle="del-plan"
                                        data-id="{$row.id}"
                                        data-tokend="{$CHECKSS}"
                                        data-msgconfirm="{$LANG->getModule('file_del_confirm')}">
                                    <i class="fa-solid fa-trash" data-icon="fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    {foreachelse}
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">{$LANG->getModule('plans_list_empty')}</td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
