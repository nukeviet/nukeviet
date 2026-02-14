{if !empty($DATA)}
<div class="card">
    <div class="card-body">
        <div class="table-responsive-lg table-card pb-1">
            <table class="table table-striped align-middle table-sticky mb-0">
                <thead>
                    <tr>
                        <th class="text-nowrap" style="width: 120px;">{$LANG->getModule('weight')}</th>
                        <th class="text-nowrap">{$LANG->getModule('question')}</th>
                        <th class="text-nowrap text-center" style="width: 150px;">{$LANG->getGlobal('actions')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$DATA item=row}
                    <tr data-qid="{$row.qid}">
                        <td>
                            <select class="form-select form-select-sm" name="weight_{$row.qid}" id="weight_{$row.qid}" data-qid="{$row.qid}" data-action="changeweight">
                                {foreach from=$row.weights item=weight}
                                <option value="{$weight.key}"{if $weight.selected} selected{/if}>{$weight.title}</option>
                                {/foreach}
                            </select>
                        </td>
                        <td>
                            <input type="hidden" name="hidden_{$row.qid}" id="hidden_{$row.qid}" value="{$row.title}">
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" name="title_{$row.qid}" id="title_{$row.qid}" value="{$row.title}" maxlength="240" autocomplete="off">
                                <button type="button" class="btn btn-primary" data-qid="{$row.qid}" data-action="save" aria-label="{$LANG->getGlobal('save')}">
                                    <i class="fa-solid fa-floppy-disk" data-icon="fa-solid fa-floppy-disk"></i> {$LANG->getGlobal('save')}
                                </button>
                            </div>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-danger" data-qid="{$row.qid}" data-action="delete" aria-label="{$LANG->getGlobal('delete')}">
                                <i class="fa-solid fa-trash" data-icon="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
{else}
<div class="alert alert-info">
    {$LANG->getGlobal('no_data')}
</div>
{/if}
