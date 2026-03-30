<form method="post" class="ajax-submit" novalidate
      action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}"
      data-msgnocheck="{$LANG->getModule('topic_nocheck')}"
      data-msgnocat="{$LANG->getModule('nocatpage')}">
    <input type="hidden" name="checkss" value="{$CHECKSS}">
    <div class="row g-3">
        <div class="col-md-5">
            <div class="card h-100">
                <div class="card-header fw-semibold">{$LANG->getModule('content_cat')}</div>
                <div class="card-body">
                    <div class="table-responsive-lg table-card pb-1">
                        <table class="table table-striped align-middle mb-0">
                            <tbody>
                                {foreach from=$CATS item=cat}
                                <tr>
                                    <td>
                                        <div style="padding-left:{$cat.space}px" class="d-flex align-items-center gap-2">
                                            <input type="checkbox" class="form-check-input flex-shrink-0"
                                                   name="catids[]" value="{$cat.catid}"
                                                   id="catcheck_{$cat.catid}"
                                                   data-toggle="catCheckbox"{if $cat.checked} checked{/if}>
                                            <label for="catcheck_{$cat.catid}" class="form-check-label mb-0">{$cat.title nofilter}</label>
                                        </div>
                                    </td>
                                    <td class="text-center" style="width:40px">
                                        <input type="radio" class="form-check-input"
                                               id="catright_{$cat.catid}"
                                               name="catid" value="{$cat.catid}"
                                               title="{$LANG->getModule('content_checkcat')}"
                                               {if not $cat.show_radio}style="display:none"{/if}{if $cat.catidchecked} checked{/if}>
                                    </td>
                                </tr>
                                {/foreach}
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer border-top">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-right-left"></i> {$LANG->getModule('move')}
                    </button>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive-lg table-card pb-1">
                        <table class="table table-striped align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center text-nowrap" style="width:5%">
                                        <input type="checkbox" class="form-check-input" name="checkall" id="checkall" data-toggle="checkAll" checked>
                                    </th>
                                    <th class="text-nowrap" style="width:95%">{$LANG->getModule('name')}</th>
                                </tr>
                            </thead>
                            <tbody>
                                {foreach from=$ROWS item=row}
                                <tr>
                                    <td class="text-center">
                                        <input type="checkbox" class="form-check-input" data-toggle="checkSingle"
                                               name="idcheck[]" value="{$row.id}"{if $row.checked} checked{/if}>
                                    </td>
                                    <td>{$row.title nofilter}</td>
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
            </div>
        </div>
    </div>
</form>
