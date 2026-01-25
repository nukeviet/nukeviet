<div class="card">
    <div class="card-body bg-body-tertiary">
        <div class="table-responsive-lg table-card">
            <table class="table table-striped align-middle table-sticky mb-0">
                <thead class="text-muted">
                    <tr>
                        <th class="text-nowrap" style="width: 20%;">{$LANG->getModule('login')}</th>
                        <th class="text-nowrap" style="width: 25%;">{$LANG->getModule('email')}</th>
                        <th class="text-nowrap" style="width: 15%;">{$LANG->getModule('position')}</th>
                        <th class="text-nowrap" style="width: 15%;">{$LANG->getModule('lev')}</th>
                        <th class="text-nowrap" style="width: 15%;">{$LANG->getModule('is_suspend')}</th>
                        <th class="text-nowrap" style="width: 10%;">{$LANG->getModule('funcs')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$ADMINS item=admin}
                    {if not ($GCONFIG.idsite gt 0 and $admin.lev eq 1)}
                    <tr>
                        <td>
                            <img alt="{$admin.level_txt}" src="{$smarty.const.NV_BASE_SITEURL}themes/{$TEMPLATE}/images/admin{$admin.lev}.png" width="38" height="18">
                            <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;id={$admin.admin_id}">{$admin.username}</a>
                        </td>
                        <td>
                            {$admin.show_mail}
                        </td>
                        <td>{$LANG->getModule('position')}</td>
                        <td>
                            {if $admin.lev gt 2}
                            <span class="badge text-bg-secondary" role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="{$admin.level_txt}">{$LANG->getGlobal("level`$admin.lev`")}</span>
                            {else}
                            <span class="badge text-bg-primary">{$LANG->getGlobal("level`$admin.lev`")}</span>
                            {/if}
                        </td>
                        <td>{$admin.suspend_text}</td>
                        <td class="text-nowrap">
                            <div class="btn-group btn-group-sm" role="group" aria-label="{$LANG->getModule('funcs')}">
                                {if not empty($admin.funcs.edit)}
                                <a class="btn btn-secondary" href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=edit&amp;admin_id={$admin.admin_id}"><i class="fa-solid fa-pen"></i> {$LANG->getGlobal('edit')}</a>
                                {/if}
                                {if not empty($admin.funcs.2step) or not empty($admin.funcs.chg_is_suspend) or not empty($admin.funcs.del)}
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"></button>
                                    <ul class="dropdown-menu">
                                        {if not empty($admin.funcs.2step)}
                                        <li><a class="dropdown-item" href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=2step&amp;admin_id={$admin.admin_id}"><i class="fa-solid fa-key fa-fw text-center"></i> {$LANG->getModule('2step_manager')}</a></li>
                                        {/if}
                                        {if not empty($admin.funcs.chg_is_suspend)}
                                        <li><a class="dropdown-item" href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=suspend&amp;admin_id={$admin.admin_id}"><i class="fa-solid fa-ban text-danger fa-fw text-center"></i> {$LANG->getModule('chg_is_suspend2')}</a></li>
                                        {/if}
                                        {if not empty($admin.funcs.del)}
                                        <li><a class="dropdown-item" href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=del&amp;admin_id={$admin.admin_id}"><i class="fa-solid fa-trash text-danger fa-fw text-center"></i> {$LANG->getGlobal('delete')}</a></li>
                                        {/if}
                                    </ul>
                                </div>
                                {/if}
                            </div>
                        </td>
                    </tr>
                    {/if}
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
