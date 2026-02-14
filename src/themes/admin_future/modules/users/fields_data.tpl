<div class="card mb-3">
    <div class="card-body">
        <div class="table-responsive-lg table-card pb-1">
            <table class="table table-striped align-middle table-sticky mb-0">
                <thead class="text-muted">
                    <tr>
                        <th class="text-center text-nowrap" style="width: 100px;">{$LANG->getModule('weight')}</th>
                        <th class="text-nowrap">{$LANG->getModule('field_id')}</th>
                        <th class="text-nowrap">{$LANG->getModule('field_title')}</th>
                        <th class="text-nowrap">{$LANG->getModule('field_type')}</th>
                        <th class="text-center text-nowrap">{$LANG->getModule('for_admin')}</th>
                        <th class="text-center text-nowrap">{$LANG->getModule('field_required')}</th>
                        <th class="text-center text-nowrap">{$LANG->getModule('field_show_register')}</th>
                        <th class="text-center text-nowrap">{$LANG->getModule('field_show_profile')}</th>
                        <th class="text-center text-nowrap" style="width: 1%;"></th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$DATA_ROWS item=row}
                    <tr>
                        <td class="text-center">
                            <select class="form-select form-select-sm" id="id_weight_{$row.fid}" data-fid="{$row.fid}"{if $row.disabled_weight} disabled{/if}>
                                {foreach from=$row.weights item=weight}
                                <option value="{$weight.key}"{if $weight.selected} selected{/if}>{$weight.title}</option>
                                {/foreach}
                            </select>
                        </td>
                        <td>{$row.field}</td>
                        <td>{$row.field_lang}</td>
                        <td>{$row.field_type}</td>
                        <td class="text-center">{if $row.for_admin}<i class="fa fa-check text-success"></i>{/if}</td>
                        <td class="text-center">{if $row.required}<i class="fa fa-check text-success"></i>{/if}</td>
                        <td class="text-center">{if $row.show_register}<i class="fa fa-check text-success"></i>{/if}</td>
                        <td class="text-center">{if $row.show_profile}<i class="fa fa-check text-success"></i>{/if}</td>
                        <td class="text-nowrap text-center">
                            <button type="button" class="btn btn-secondary btn-sm" data-action="edit" data-fid="{$row.fid}" title="{$LANG->getModule('field_edit')}" aria-label="{$LANG->getModule('field_edit')}"><i class="fa fa-edit"></i></button>
                            {if not $row.is_system}
                            <button type="button" class="btn btn-danger btn-sm" data-action="delete" data-fid="{$row.fid}" title="{$LANG->getGlobal('delete')}" aria-label="{$LANG->getGlobal('delete')}"><i class="fa fa-trash-o"></i></button>
                            {/if}
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
