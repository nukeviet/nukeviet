<div class="card"
     data-bid="{$BID}"
     data-checkss="{$CHECKSS}">
    <div class="card-body">
        <div class="table-responsive-lg table-card pb-1">
            <table class="table table-striped align-middle table-sticky mb-0">
                <thead>
                    <tr>
                        <th class="text-center text-nowrap" style="width:3%">
                            <input type="checkbox" id="check-all-block" class="form-check-input">
                        </th>
                        <th class="text-center text-nowrap" style="width:7%">{$LANG->getModule('weight')}</th>
                        <th class="text-nowrap" style="width:25%">{$LANG->getModule('name')}</th>
                        <th class="text-nowrap" style="width:13%">{$LANG->getModule('content_publ_date')}</th>
                        <th class="text-nowrap" style="width:10%">{$LANG->getModule('status')}</th>
                        <th class="text-center text-nowrap" style="width:5%">
                            <i class="fa-solid fa-eye" title="{$LANG->getModule('hitstotal')}"></i>
                        </th>
                        <th class="text-center text-nowrap" style="width:5%">
                            <i class="fa-solid fa-comment" title="{$LANG->getModule('numcomments')}"></i>
                        </th>
                        <th class="text-center text-nowrap" style="width:32%">{$LANG->getGlobal('actions')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$BLOCK_ROWS item=row}
                    <tr>
                        <td class="text-center">
                            <input type="checkbox" class="form-check-input block-item-check" value="{$row.id}">
                        </td>
                        <td class="text-center">
                            {if $NUM_ROWS > 1}
                            <button type="button" class="btn btn-sm btn-secondary"
                                    data-toggle="change-block-weight"
                                    data-id="{$row.id}"
                                    data-current-weight="{$row.weight}"
                                    data-tokend="{$CHECKSS}"
                                    data-bs-title="{$LANG->getModule('change_weight')}">
                                {$row.weight}
                            </button>
                            {else}
                            {$row.weight}
                            {/if}
                        </td>
                        <td>
                            <a target="_blank" href="{$row.link}">{$row.title}</a>
                        </td>
                        <td>{$row.publtime}</td>
                        <td>{$row.status}</td>
                        <td class="text-center">{$row.hitstotal}</td>
                        <td class="text-center">{$row.hitscm}</td>
                        <td class="text-center text-nowrap">
                            <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=content&amp;id={$row.id}"
                               class="btn btn-sm btn-secondary">
                                <i class="fa-solid fa-pencil"></i> {$LANG->getGlobal('edit')}
                            </a>
                            <button type="button" class="btn btn-sm btn-danger"
                                    data-toggle="delete-block-item"
                                    data-id="{$row.id}"
                                    data-tokend="{$CHECKSS}">
                                <i class="fa-solid fa-trash" data-icon="fa-trash"></i> {$LANG->getGlobal('delete')}
                            </button>
                        </td>
                    </tr>
                    {foreachelse}
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">{$LANG->getModule('no_data')}</td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer border-top">
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <button type="button" class="btn btn-danger"
                    data-toggle="delete-block-selected"
                    data-tokend="{$CHECKSS}">
                <i class="fa-solid fa-trash"></i> {$LANG->getModule('delete_from_block')}
            </button>
            {if $IS_SPADMIN}
            <a href="#"
               class="btn btn-info"
               data-toggle="confirm-order-publtime"
               data-href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}&amp;bid={$BID}&amp;order_publtime={$ORDER_PUBLTIME_KEY}">
                <i class="fa-solid fa-sort"></i> {$LANG->getModule('order_publtime')}
            </a>
            {/if}
        </div>
    </div>
</div>

{if $NUM_ROWS > 1}
<div id="block-weight-tpl" class="d-none">
    <div style="width:220px">
        <div class="input-group input-group-sm block-weight-item">
            <input type="number" class="form-control block-new-weight" min="1" max="{$NUM_ROWS}" value="" name="newweight">
            <button type="button" class="btn btn-secondary block-weight-down" tabindex="-1"><i class="fa-solid fa-angle-down"></i></button>
            <button type="button" class="btn btn-secondary block-weight-up" tabindex="-1"><i class="fa-solid fa-angle-up"></i></button>
            <button type="button" class="btn btn-primary block-weight-ok" data-id="" data-current-weight="">OK</button>
        </div>
        <div class="form-text mt-1">{$LANG->getModule('type_new_weight')} {$NUM_ROWS}</div>
    </div>
</div>
{/if}
