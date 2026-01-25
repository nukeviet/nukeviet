<div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0 mb-4">
    <div class="card-header fs-5 fw-medium">
        {$LANG->getModule('nv_lang_show')}
    </div>
    <div class="card-body">
        <div class="table-responsive table-card">
            <table class="table table-striped align-middle mb-0">
                <thead class="text-muted">
                    <tr>
                        <th class="text-center text-nowrap" style="width: 20%;">{$LANG->getModule('nv_lang_key')}</th>
                        <th class="text-center text-nowrap" style="width: 20%;">{$LANG->getModule('nv_lang_name')}</th>
                        <th class="text-center text-nowrap" style="width: 20%;">{$LANG->getModule('nv_lang_native_name')}</th>
                        <th class="text-center text-nowrap" style="width: 40%;">{$LANG->getGlobal('function')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$ROWS item=row}
                    <tr>
                        <td class="text-center">{$row.key}</td>
                        <td class="text-center">{$row.language}</td>
                        <td class="text-center">{$row.name}</td>
                        <td class="text-nowrap">
                            <div class="d-flex flex-nowrap">
                                <button type="button" class="btn btn-secondary btn-sm me-1" data-toggle="ajLangInterface" data-url="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=read&amp;dirlang={$row.key}&amp;checksess={$row.checkss_read}"><i class="fa-solid fa-turn-up" data-icon="fa-turn-up"></i> {$LANG->getModule('nv_admin_read_all')}</button>
                                {if $row.allowed_write}
                                <button type="button" class="btn btn-secondary btn-sm me-1" data-toggle="ajLangInterface" data-url="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=write&amp;dirlang={$row.key}&amp;checksess={$row.checkss_write}"><i class="fa-solid fa-turn-down" data-icon="fa-turn-down"></i> {$LANG->getModule('nv_admin_write')}</button>
                                {/if}
                                {if $row.allowed_delete}
                                <button type="button" class="btn btn-secondary btn-sm me-1" data-toggle="ajLangInterface" data-url="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=delete&amp;type=db&amp;dirlang={$row.key}&amp;checksess={$row.checkss_delete}"><i class="fa-solid fa-eraser text-danger" data-icon="fa-eraser"></i> {$LANG->getModule('nv_admin_delete')}</button>
                                {/if}
                                {if $row.allowed_delete_files}
                                <button type="button" class="btn btn-secondary btn-sm me-1" data-toggle="ajLangInterface" data-url="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=delete&amp;type=files&amp;dirlang={$row.key}&amp;checksess={$row.checkss_delete}" data-confirm="1"><i class="fa-solid fa-folder-minus text-danger" data-icon="fa-folder-minus"></i> {$LANG->getModule('nv_admin_delete_files')}</button>
                                {/if}
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"></button>
                                    <ul class="dropdown-menu">
                                        {if $row.allowed_edit}
                                        <li><a class="dropdown-item" href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=interface&amp;dirlang={$row.key}"><i class="fa-solid fa-pen fa-fw text-center"></i> {$LANG->getModule('nv_admin_edit')}</a></li>
                                        {/if}
                                        <li><a class="dropdown-item" href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=download&amp;dirlang={$row.key}&amp;checksess={$row.checkss_download}"><i class="fa-solid fa-download fa-fw text-center"></i> {$LANG->getModule('nv_admin_download')}</a></li>
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0">
    <div class="card-header fs-5 fw-medium">
        {$LANG->getModule('nv_setting_read')}
    </div>
    <div class="card-body pt-4">
        <form method="post" class="ajax-submit" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" novalidate>
            <div class="row mb-3">
                <div class="col-sm-8 offset-sm-3">
                    {for $read_type=0 to 2}
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="read_type" value="{$read_type}" id="element_read_type_{$read_type}"{if $read_type eq $GCONFIG.read_type} checked{/if}>
                        <label class="form-check-label" for="element_read_type_{$read_type}">{$LANG->getModule("nv_setting_type_`$read_type`")}</label>
                    </div>
                    {/for}
                </div>
            </div>
            <div class="row">
                <div class="col-sm-8 offset-sm-3">
                    <input type="hidden" name="checkss" value="{$smarty.const.NV_CHECK_SESSION}">
                    <button type="submit" class="btn btn-primary">{$LANG->getGlobal('submit')}</button>
                </div>
            </div>
        </form>
    </div>
</div>
