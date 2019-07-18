<div class="card card-table card-footer-nav">
    <div class="card-header">
        {$LANG->get('nv_admin_edit_result_title', $RESULT.login)}
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th style="width: 33.3333%;">{$LANG->get('field')}</th>
                        <th style="width: 33.3333%;">{$LANG->get('old_value')}</th>
                        <th style="width: 33.3333%;">{$LANG->get('new_value')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$RESULT.change item=value}
                    <tr>
                        <td>{$value.0}</td>
                        <td>{$value.1}</td>
                        <td>{$value.2}</td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        <div class="page-tools">
            <a href="{$NV_BASE_ADMINURL}index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&amp;{$NV_NAME_VARIABLE}={$MODULE_NAME}" class="btn btn-space btn-secondary">{$LANG->get('main')}</a>
            <a href="{$NV_BASE_ADMINURL}index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&amp;{$NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{NV_OP_VARIABLE}=edit&amp;admin_id={$RESULT.admin_id}" class="btn btn-space btn-secondary">{$LANG->get('edit')}</a>
        </div>
    </div>
</div>
