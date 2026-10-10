<div class="alert alert-info">{$LANG->getModule('nv_admin_add_title')}</div>
<div class="card pb-1 pt-1">
    <div class="card-body">
        <div class="table-responsive-sm table-card">
            <table class="table table-sticky mb-0">
                <col style="width: 40%;">
                <col style="width: 60%;">
                <tbody>
                    <tr>
                        <th>{$LANG->getModule('lev')}</th>
                        <td>{$DATA.lev}</td>
                    </tr>
                    <tr>
                        <th>{$LANG->getModule('nv_admin_modules')}</th>
                        <td>{$DATA.modules}</td>
                    </tr>
                    <tr>
                        <th>{$LANG->getModule('lev_expired')}</th>
                        <td>{$DATA.lev_expired}</td>
                    </tr>
                    {if isset($DATA.after_exp_action)}
                    <tr>
                        <th>{$LANG->getModule('after_exp_action')}</th>
                        <td>{$DATA.after_exp_action}</td>
                    </tr>
                    {/if}
                    <tr>
                        <th>{$LANG->getModule('position')}</th>
                        <td>{$DATA.position}</td>
                    </tr>
                    <tr>
                        <th>{$LANG->getModule('editor')}</th>
                        <td>{$DATA.editor}</td>
                    </tr>
                    <tr>
                        <th>{$LANG->getModule('allow_files_type')}</th>
                        <td>{$DATA.allow_files_type}</td>
                    </tr>
                    <tr>
                        <th>{$LANG->getModule('allow_modify_files')}</th>
                        <td>{$DATA.allow_modify_files}</td>
                    </tr>
                    <tr>
                        <th>{$LANG->getModule('allow_create_subdirectories')}</th>
                        <td>{$DATA.allow_create_subdirectories}</td>
                    </tr>
                    <tr>
                        <th>{$LANG->getModule('allow_modify_subdirectories')}</th>
                        <td>{$DATA.allow_modify_subdirectories}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        <a class="btn btn-primary" href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=edit&amp;admin_id={$DATA.admin_id}"><i class="fa-solid fa-pen"></i> {$LANG->getGlobal('edit')}</a>
        <a class="btn btn-primary" href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}"><i class="fa-solid fa-list"></i> {$LANG->getModule('main')}</a>
    </div>
</div>
