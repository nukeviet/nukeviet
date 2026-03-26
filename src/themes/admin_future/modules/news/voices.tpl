<div class="card mb-4">
    <div class="card-body">
        <div class="table-responsive-lg table-card pb-1">
            <table class="table table-striped align-middle table-sticky mb-0">
                <thead>
                    <tr>
                        <th class="text-center text-nowrap" style="width:10%">{$LANG->getModule('order')}</th>
                        <th class="text-nowrap" style="width:65%">{$LANG->getModule('voice_title')}</th>
                        <th class="text-center text-nowrap" style="width:15%">{$LANG->getModule('status')}</th>
                        <th class="text-center text-nowrap" style="width:10%">{$LANG->getModule('function')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$VOICES item=row}
                    <tr>
                        <td class="text-center">
                            <select name="weight_{$row.id}" class="form-select form-select-sm fw-75"
                                    data-toggle="change-voice-weight"
                                    data-id="{$row.id}"
                                    data-tokend="{$CHECKSS}">
                                {for $w=1 to $NUM_VOICES}
                                <option value="{$w}"{if $w == $row.weight} selected{/if}>{$w}</option>
                                {/for}
                            </select>
                        </td>
                        <td><strong>{$row.title}</strong></td>
                        <td class="text-center">
                            <div class="form-check d-inline-block">
                                <input type="checkbox" class="form-check-input"
                                       data-toggle="change-voice-status"
                                       data-id="{$row.id}"
                                       data-tokend="{$CHECKSS}"
                                       {if $row.status}checked{/if}>
                            </div>
                        </td>
                        <td class="text-center text-nowrap">
                            <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}&amp;id={$row.id}"
                               class="btn btn-sm btn-secondary">
                                <i class="fa-solid fa-pencil"></i> {$LANG->getGlobal('edit')}
                            </a>
                            <button type="button" class="btn btn-sm btn-danger"
                                    data-toggle="delete-voice"
                                    data-id="{$row.id}"
                                    data-tokend="{$CHECKSS}">
                                <i class="fa-solid fa-trash" data-icon="fa-trash"></i> {$LANG->getGlobal('delete')}
                            </button>
                        </td>
                    </tr>
                    {foreachelse}
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">{$LANG->getModule('no_data')}</td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>

<form id="voice-form" method="post" class="ajax-submit" novalidate{if $IS_EDIT} data-is-edit="1"{/if}
      action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}{if $ITEM.id}&amp;id={$ITEM.id}{/if}">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                {if $ITEM.id}{$LANG->getModule('voice_edit')}{else}{$LANG->getModule('voice_add')}{/if}
            </h5>
        </div>
        <div class="card-body pt-4">
            <div class="row mb-3">
                <label for="voice_title" class="col-sm-3 col-form-label text-sm-end">
                    {$LANG->getModule('voice_title')} <span class="text-danger">(*)</span>
                </label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input type="text" class="form-control required" id="voice_title" name="title"
                           value="{$ITEM.title}" maxlength="250" autocomplete="off">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="voice_description" class="col-sm-3 col-form-label text-sm-end">
                    {$LANG->getModule('description')}
                </label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <textarea class="form-control" id="voice_description" name="description"
                              rows="3">{$ITEM.description}</textarea>
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-8 offset-sm-3">
                    <input type="hidden" name="save" value="1">
                    <input type="hidden" name="checkss" value="{$CHECKSS}">
                    <input type="hidden" name="id" value="{$ITEM.id}">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-floppy-disk"></i> {$LANG->getGlobal('save')}
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
