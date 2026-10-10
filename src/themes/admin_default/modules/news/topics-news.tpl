{if $NUM_ITEMS gt 0}
<div class="card">
    <div class="card-body">
        <div class="table-responsive-lg table-card pb-1">
            <table class="table table-striped align-middle table-sticky mb-0">
                <thead>
                    <tr>
                        <th class="text-center text-nowrap" style="width:4%">
                            <input type="checkbox" data-toggle="checkAll" name="checkAll[]" class="form-check-input m-0 align-middle" aria-label="{$LANG->getGlobal('toggle_checkall')}">
                        </th>
                        <th class="text-nowrap" style="width:34%">{$LANG->getModule('name')}</th>
                        <th class="text-center text-nowrap" style="width:20%">{$LANG->getModule('content_publ_date')}</th>
                        <th class="text-nowrap" style="width:12%">{$LANG->getModule('status')}</th>
                        <th class="text-center text-nowrap" style="width:8%">
                            <i class="fa-solid fa-eye" title="{$LANG->getModule('hitstotal')}"></i>
                        </th>
                        <th class="text-center text-nowrap" style="width:8%">
                            <i class="fa-solid fa-comment" title="{$LANG->getModule('numcomments')}"></i>
                        </th>
                        <th class="text-center text-nowrap" style="width:14%">{$LANG->getGlobal('actions')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$ARRAY item=row}
                    <tr>
                        <td class="text-center">
                            <input type="checkbox" data-toggle="checkSingle" name="checkSingle[]" value="{$row.id}" class="form-check-input m-0 align-middle" aria-label="{$LANG->getGlobal('toggle_checksingle')}">
                        </td>
                        <td>
                            <a href="{$row.link}" target="_blank">{$row.title}</a>
                        </td>
                        <td class="text-center">{$row.publtime}</td>
                        <td>{$row.status}</td>
                        <td class="text-center">{$row.hitstotal}</td>
                        <td class="text-center">{$row.hitscm}</td>
                        <td class="text-center text-nowrap">
                            {if $row.can_edit}
                            <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=content&amp;id={$row.id}"
                               class="btn btn-sm btn-secondary">
                                <i class="fa-solid fa-pencil"></i> {$LANG->getGlobal('edit')}
                            </a>
                            {/if}
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer border-top">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div class="d-flex align-items-center gap-2">
                <input type="checkbox" data-toggle="checkAll" name="checkAll[]" class="form-check-input m-0 align-middle" aria-label="{$LANG->getGlobal('toggle_checkall')}">
                <button type="button" class="btn btn-sm btn-danger" id="topicsnews-delbtn"
                        data-topicid="{$TOPICID}"
                        data-tokend="{$CHECKSS}"
                        data-msgnocheck="{$LANG->getModule('topic_nocheck')}"
                        data-msgconfirm="{$LANG->getModule('topic_delete_confirm')}">
                    <i class="fa-solid fa-trash" data-icon="fa-trash"></i> {$LANG->getModule('topic_del')}
                </button>
            </div>
            {if $PAGINATION}
            <div class="pagination-wrap">
                {$PAGINATION}
            </div>
            {/if}
        </div>
    </div>
</div>
{else}
<div class="alert alert-warning">{$LANG->getModule('topic_nonews')}</div>
{/if}
