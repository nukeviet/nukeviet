<div class="card card-table card-footer-nav">
    <div class="card-header">
        {$LANG->get('nv_admin_add_title')}
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <col style="width: 50%;">
                <col style="width: 50%;">
                <tbody>
                    <tr>
                        <td>{$LANG->get('lev')}</td>
                        <td>{if $RESULT.lev eq 2}{$LANG->get('level2')}{else}{$LANG->get('level3')}{/if}</td>
                    </tr>
                    <tr>
                        <td>{$LANG->get('nv_admin_modules')}</td>
                        <td>{$RESULT.modules}</td>
                    </tr>
                    <tr>
                        <td>{$LANG->get('position')}</td>
                        <td>{$RESULT.position}</td>
                    </tr>
                    <tr>
                        <td>{$LANG->get('editor')}</td>
                        <td>{if empty($RESULT.editor)}{$LANG->get('not_use')}{else}{$RESULT.editor}{/if}</td>
                    </tr>
                    <tr>
                        <td>{$LANG->get('allow_files_type')}</td>
                        <td>{if empty($RESULT.allow_files_type)}{$LANG->get('no')}{else}{", "|implode:$RESULT.allow_files_type}{/if}</td>
                    </tr>
                    <tr>
                        <td>{$LANG->get('allow_modify_files')}</td>
                        <td>{if $RESULT.allow_files_type}{$LANG->get('yes')}{else}{$LANG->get('no')}{/if}</td>
                    </tr>
                    <tr>
                        <td>{$LANG->get('allow_create_subdirectories')}</td>
                        <td>{if $RESULT.allow_create_subdirectories}{$LANG->get('yes')}{else}{$LANG->get('no')}{/if}</td>
                    </tr>
                    <tr>
                        <td>{$LANG->get('allow_modify_subdirectories')}</td>
                        <td>{if $RESULT.allow_modify_subdirectories}{$LANG->get('yes')}{else}{$LANG->get('no')}{/if}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        <div class="page-tools">
            <a href="{$NV_BASE_ADMINURL}index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&amp;{$NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{NV_OP_VARIABLE}=edit&amp;admin_id={$RESULT.admin_id}" class="btn btn-space btn-secondary">{$LANG->get('edit')}</a>
            <a href="{$NV_BASE_ADMINURL}index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&amp;{$NV_NAME_VARIABLE}={$MODULE_NAME}" class="btn btn-space btn-secondary">{$LANG->get('main')}</a>
        </div>
    </div>
</div>
