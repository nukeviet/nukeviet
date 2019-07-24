<div class="card card-table">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th style="width: 17%;">{$LANG->get('login')}</th>
                        <th style="width: 19%;">{$LANG->get('email')}</th>
                        <th style="width: 16%;">{$LANG->get('position')}</th>
                        <th style="width: 16%;">{$LANG->get('lev')}</th>
                        <th style="width: 16%;">{$LANG->get('is_suspend')}</th>
                        <th style="width: 16%;" class="text-right">{$LANG->get('funcs')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$ADMINS key=adminid item=adminrow}
                    <tr>
                        <td class="text-nowrap">
                            {for $lev=1 to 3}
                            <i class="fas fa-star {if $lev <= $adminrow.levelloop}text-warning{else}text-muted{/if}"></i>
                            {/for}
                            <span class="ml-1">{$adminrow.login}</span>
                        </td>
                        <td>{$adminrow.email}</td>
                        <td>{$adminrow.position}</td>
                        <td>{$adminrow.level_txt}</td>
                        <td>
                            {if $adminrow.is_suspend or empty($adminrow.active)}
                            {$LANG->get('is_suspend2')}
                            {else}
                            {$LANG->get('is_suspend0')}
                            {/if}
                        </td>
                        <td class="text-right text-nowrap">
                            {if $adminrow.t_is_edit}
                            <a class="btn btn-sm btn-secondary" href="{$NV_BASE_ADMINURL}index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&amp;{$NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$NV_OP_VARIABLE}=edit&amp;admin_id={$adminid}"><i class="icon icon-left fas fa-pencil-alt"></i> {$LANG->get('edit')}</a>
                            {/if}
                            {if $adminrow.t_is_suspend}
                            <a class="btn btn-sm btn-secondary" href="{$NV_BASE_ADMINURL}index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&amp;{$NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$NV_OP_VARIABLE}=suspend&amp;admin_id={$adminid}">{if $adminrow.is_suspend}<i class="icon icon-left fas fa-user-check"></i> {$LANG->get('suspend0')}{else}<i class="icon icon-left fas fa-user-slash"></i> {$LANG->get('suspend1')}{/if}</a>
                            {/if}
                            {if $adminrow.t_is_del}
                            <a class="btn btn-sm btn-danger" href="{$NV_BASE_ADMINURL}index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&amp;{$NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$NV_OP_VARIABLE}=del&amp;admin_id={$adminid}"><i class="icon icon-left fas fa-trash-alt"></i> {$LANG->get('delete')}</a>
                            {/if}
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
