<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <div class="hstack gap-2 flex-wrap">
        <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=cat" class="btn btn-outline-primary btn-sm">
            <i class="fa-solid fa-list"></i> {$LANG->getModule('cat_list')}
        </a>
        <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=main" class="btn btn-outline-secondary btn-sm">
            <i class="fa-solid fa-newspaper"></i> {$LANG->getModule('list')}
        </a>
        {foreach from=$CATS key=catid item=cat_title}
        <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=main&amp;catid={$catid}" class="btn btn-sm {if $FILTER_CATID eq $catid}btn-primary{else}btn-outline-secondary{/if}">
            {$cat_title}
        </a>
        {/foreach}
    </div>
    <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=content" class="btn btn-primary btn-sm">
        <i class="fa-solid fa-plus"></i> {$LANG->getModule('add')}
    </a>
</div>
<div class="card">
    <div class="card-body">
        <div class="table-responsive-lg table-card pb-1">
            <table class="table table-striped align-middle table-sticky mb-0">
                <thead>
                    <tr>
                        <th class="text-nowrap" style="width: 7%;">{$LANG->getModule('order')}</th>
                        <th class="text-nowrap" style="width: 20%;">{$LANG->getModule('cat')}</th>
                        <th class="text-nowrap" style="width: 30%;">{$LANG->getModule('title')}</th>
                        <th class="text-nowrap" style="width: 10%;">{$LANG->getModule('add_time')}</th>
                        <th class="text-nowrap" style="width: 10%;">{$LANG->getModule('edit_time')}</th>
                        <th class="text-nowrap" style="width: 6%;">{$LANG->getModule('active')}</th>
                        <th class="text-nowrap text-center" style="width: 7%;">{$LANG->getModule('hitstotal')}</th>
                        <th class="text-nowrap" style="width: 10%;">{$LANG->getModule('feature')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$DATA key=key item=row}
                    <tr>
                        <td>
                            <select aria-label="{$LANG->getModule('order')}" data-toggle="changeWeiPage" data-checkss="{$row.checkss}" data-id="{$row.id}" name="change_weight_{$row.id}" id="change_weight_{$row.id}" class="form-select form-select-sm fw-75">
                                {for $weight=1 to count($DATA)}
                                <option value="{$weight}"{if $weight eq $row.weight} selected{/if}>{$weight}</option>
                                {/for}
                            </select>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">{$row.cat_title}</span>
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
                                <a href="{$row.url_edit}" class="btn btn-sm btn-outline-primary" title="{$LANG->getGlobal('edit')}"><i class="fa-solid fa-pen-to-square"></i></a>
                                <a href="{$row.url_copy}" class="btn btn-sm btn-outline-info" title="{$LANG->getModule('title_copy_page')}"><i class="fa-solid fa-copy"></i></a>
                                <button type="button" class="btn btn-sm btn-outline-danger" title="{$LANG->getGlobal('delete')}" data-toggle="nv_del_page" data-id="{$row.id}" data-checkss="{$row.checkss}"><i class="fa-solid fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
