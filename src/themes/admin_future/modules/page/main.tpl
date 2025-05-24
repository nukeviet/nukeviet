<div class="card">
    <div class="table-responsive-lg table-card pb-1">
        <table class="table table-striped align-middle table-sticky mb-0">
            <thead>
                <tr>
                    <th class="text-nowrap" style="width: 7%;">{$LANG->getModule('order')}</th>
                    <th class="text-nowrap" style="width: 45%;">{$LANG->getModule('title')}</th>
                    <th class="text-nowrap" style="width: 10%;">{$LANG->getModule('add_time')}</th>
                    <th class="text-nowrap" style="width: 10%;">{$LANG->getModule('edit_time')}</th>
                    <th class="text-nowrap" style="width: 6%;">{$LANG->getModule('active')}</th>
                    <th class="text-nowrap text-center" style="width: 7%;">{$LANG->getModule('hitstotal')}</th>
                    <th class="text-nowrap" style="width: 15%;">{$LANG->getModule('feature')}</th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$DATA key=key item=row}
                <tr>
                    <td>
                        <select aria-label="{$LANG->getModule('order')}" data-toggle="changeWeiPage" data-id="{$row.id}" name="change_weight_{$row.id}" id="change_weight_{$row.id}" class="form-select fw-75">
                            {for $weight=1 to count($DATA)}
                            <option value="{$weight}"{if $weight eq $row.weight} selected{/if}>{$weight}</option>
                            {/for}
                        </select>
                    </td>
                    <td>
                        <a title="{$row.title}" href="{$row.url_view}">{$row.title}</a>
                    </td>
                    <td>{$row.add_time|ddatetime:1}</td>
                    <td>{$row.edit_time|ddatetime:1}</td>
                    <td class="text-center form-switch">
                        <div class="d-inline-flex">
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" role="switch" aria-label="{$LANG->getModule('active')}" data-toggle="changeActive" data-checkss="{$row.checkss}" data-id="{$row.id}" name="change_status_{$row.id}" id="change_status_{$row.id}" {if $row.status == 1} checked{/if}/>
                            </div>
                        </div>
                    </td>
                    <td class="text-center">{$row.hitstotal|dnumber}</td>
                    <td>
                        <div class="hstack gap-1">
                            {if not empty($PCONFIG.copy_page)}
                            <div class="text-nowrap">
                                <a title="{$LANG->getModule('title_copy_page')}" href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=content&amp;copy=1&amp;id={$row.id}" class="btn btn-success btn-sm"><i class="fa-solid fa-copy fa-fw text-center"></i></a>
                            </div>
                            {/if}
                            <div class="text-nowrap">
                                <a href="{$row.url_edit}" class="btn btn-secondary btn-sm"><i class="fa-solid fa-pen"></i> {$LANG->getGlobal('edit')}</a>
                            </div>
                            <div class="text-nowrap">
                                <button type="button" class="btn btn-danger btn-sm" data-toggle="nv_del_page" data-checkss="{$row.checkss}" data-id="{$row.id}"><i class="fa-solid fa-trash" data-icon="fa-trash"></i> {$LANG->getGlobal('delete')}</button>
                            </div>
                        </div>
                    </td>
                </tr>
                {/foreach}
            </tbody>
        </table>
    </div>
</div>
