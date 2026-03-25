<div class="mb-3">
    <button type="button" class="btn btn-sm btn-primary"
            data-toggle="add-block"
            data-url="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}&amp;action=block">
        <i class="fa-solid fa-plus-circle"></i> {$LANG->getModule('add_menu')}
    </button>
</div>
<div class="card">
    <div class="card-body">
        <div class="table-responsive-lg table-card pb-1">
            <table class="table table-striped align-middle table-sticky mb-0">
                <thead>
                    <tr>
                        <th class="text-nowrap" style="width:25%;">{$LANG->getModule('name_block')}</th>
                        <th class="text-nowrap" style="width:60%;">{$LANG->getModule('menu')}</th>
                        <th class="text-center text-nowrap" style="width:15%;">{$LANG->getModule('action')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$ARRAY item=row}
                    <tr>
                        <td><a href="{$row.link_view}"><strong>{$row.title}</strong></a></td>
                        <td>{$row.menu_item}</td>
                        <td class="text-center text-nowrap">
                            <button type="button" class="btn btn-sm btn-secondary"
                                    data-toggle="edit-block"
                                    data-url="{$row.edit_url}"
                                    aria-label="{$LANG->getModule('edit')}"
                                    data-bs-toggle="tooltip" title="{$LANG->getModule('edit')}">
                                <i class="fa-solid fa-pencil" data-icon="fa-pencil"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger"
                                    data-toggle="delete-block"
                                    data-id="{$row.id}"
                                    data-tokend="{$CHECKSS}"
                                    aria-label="{$LANG->getModule('delete')}"
                                    data-bs-toggle="tooltip" title="{$LANG->getModule('delete')}">
                                <i class="fa-solid fa-trash" data-icon="fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    {foreachelse}
                    <tr>
                        <td colspan="3" class="text-center text-muted py-4">{$LANG->getModule('data_no')}</td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="menu-block-modal"></div>
