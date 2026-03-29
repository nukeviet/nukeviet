<form method="post" class="ajax-submit" novalidate
      action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive-lg table-card pb-1">
                <table class="table table-striped align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="text-center text-nowrap" style="width:8%">
                                <input type="checkbox" id="check-all-block" class="form-check-input">
                            </th>
                            <th class="text-nowrap">{$LANG->getModule('name')}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach from=$NEWS_ROWS item=row}
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" class="form-check-input" name="idcheck[]" value="{$row.id}"{if $row.checked} checked{/if}>
                            </td>
                            <td>{$row.title}</td>
                        </tr>
                        {foreachelse}
                        <tr>
                            <td colspan="2" class="text-center text-muted py-4">{$LANG->getModule('no_data')}</td>
                        </tr>
                        {/foreach}
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer border-top">
            <div class="d-flex flex-wrap align-items-center gap-2">
                <select class="form-select" name="bid" style="width:auto; min-width:200px">
                    {foreach from=$BLOCK_OPTIONS item=opt}
                    <option value="{$opt.bid}"{if $opt.selected} selected{/if}>{$opt.title}</option>
                    {/foreach}
                </select>
                <input type="hidden" name="addtoblock" value="1">
                <input type="hidden" name="checkss" value="{$CHECKSS}">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i> {$LANG->getGlobal('save')}
                </button>
            </div>
        </div>
    </div>
</form>
