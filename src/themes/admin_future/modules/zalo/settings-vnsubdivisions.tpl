{assign var="formAction" value="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}"}
<div class="card">
    <div class="card-header d-flex flex-wrap gap-2 align-items-center justify-content-between">
        <span class="fs-5 fw-medium">{$LANG->getModule('vnsubdivisions_settings')}</span>
        <div>
            <select class="form-select" name="subdiv_parent" data-toggle="zaloSubdivParent" data-url="{$formAction}&amp;action=vnsubdivisions" aria-label="{$LANG->getModule('vnsubdivisions_settings')}">
                <option value="">{$LANG->getModule('provincial_vnsubdivisions')}</option>
                {foreach from=$PROVINCES item=province}
                <option value="{$province.code}"{if $province.code eq $PARENT} selected{/if}>{$LANG->getModule('vnsubdivisions_parent', $province.name)}</option>
                {/foreach}
            </select>
        </div>
    </div>
    <form method="post" action="{$formAction}" class="ajax-submit" novalidate>
        <div class="card-body">
            <div class="table-responsive-lg table-card pb-1">
                <table class="table table-striped align-middle table-sticky mb-0">
                    <thead>
                        <tr>
                            <th class="text-nowrap" style="width:5%">#</th>
                            <th class="text-nowrap" style="width:10%">{$LANG->getModule('vnsubdivisions_code')}</th>
                            <th class="text-nowrap" style="width:40%">{$LANG->getModule('vnsubdivisions_main_name')}</th>
                            <th class="text-nowrap" style="width:45%">
                                {$LANG->getModule('vnsubdivisions_other_name')}
                                <i class="fa-solid fa-circle-info text-body-secondary" data-bs-toggle="tooltip" title="{$LANG->getModule('vnsubdivisions_other_name_note')}" aria-label="{$LANG->getModule('vnsubdivisions_other_name_note')}"></i>
                            </th>
                        </tr>
                    </thead>
                    <tbody data-msgconfirm="{$LANG->getModule('change_name_note')}">
                        {foreach from=$SUBDIVS item=row name=loop}
                        <tr>
                            <td>{$smarty.foreach.loop.iteration}</td>
                            <td class="text-nowrap"><code>{$row.code_format}</code></td>
                            <td>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="subdiv_mainname[{$row.code}]" value="{$row.mainname}" maxlength="100" readonly aria-label="{$LANG->getModule('vnsubdivisions_main_name')}">
                                    <button type="button" class="btn btn-secondary" data-toggle="zaloSubdivUnlock" aria-label="{$LANG->getModule('unlock_edit')}" data-bs-toggle="tooltip" title="{$LANG->getModule('unlock_edit')}"><i class="fa-solid fa-lock fa-fw"></i></button>
                                </div>
                                <div class="invalid-feedback"></div>
                            </td>
                            <td>
                                <div class="d-grid gap-2 zalo-multi-inputs" data-max="10">
                                    {foreach from=$row.othernames item=othername}
                                    <div class="input-group zalo-multi-input">
                                        <input type="text" class="form-control" name="subdiv_othername[{$row.code}][]" value="{$othername}" maxlength="100" aria-label="{$LANG->getModule('vnsubdivisions_other_name')}">
                                        <button type="button" class="btn btn-secondary" data-toggle="zaloInputAdd" aria-label="{$LANG->getGlobal('add')}" data-bs-toggle="tooltip" title="{$LANG->getGlobal('add')}"><i class="fa-solid fa-plus fa-fw text-primary"></i></button>
                                        <button type="button" class="btn btn-secondary" data-toggle="zaloInputRemove" aria-label="{$LANG->getGlobal('delete')}" data-bs-toggle="tooltip" title="{$LANG->getGlobal('delete')}"><i class="fa-solid fa-xmark fa-fw text-danger"></i></button>
                                    </div>
                                    {/foreach}
                                </div>
                            </td>
                        </tr>
                        {/foreach}
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer border-top text-center sticky-bottom bg-body">
            <input type="hidden" name="parent" value="{$PARENT}">
            <input type="hidden" name="vnsubdivisionsSave" value="1">
            <input type="hidden" name="checkss" value="{$CHECKSS}">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> {$LANG->getGlobal('save')}</button>
        </div>
    </form>
</div>
