<div class="row g-4">
    {foreach from=$ADMINS item=admin}
    <div class="col-xxl-{if count($ADMINS) eq 1}12{else}6{/if}">
        <div class="card bg-body-tertiary h-100">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="fs-5 fw-medium">
                        <img alt="{$admin.level_txt}" src="{$smarty.const.NV_BASE_SITEURL}themes/{$TEMPLATE}/images/admin{$admin.lev}.png" width="38" height="18"> {$admin.full_name}{if $ADMIN_INFO.admin_id eq $admin.admin_id} ({$LANG->getModule('admin_info_title2')}){/if}
                    </div>
                    {if $admin.funcs.num gt 0}
                    <div class="ms-2">
                        <div class="btn-group btn-group-sm" role="group" aria-label="{$LANG->getModule('funcs')}">
                            {if not empty($admin.funcs.edit)}
                            <a class="btn btn-secondary" href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=edit&amp;admin_id={$admin.admin_id}"><i class="fa-solid fa-pen"></i> {$LANG->getGlobal('edit')}</a>
                            {/if}
                            {if not empty($admin.funcs.del)}
                            <a class="btn btn-secondary" href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=del&amp;admin_id={$admin.admin_id}"><i class="fa-solid fa-trash text-danger"></i> {$LANG->getGlobal('delete')}</a>
                            {/if}
                            {if not empty($admin.funcs.2step) or not empty($admin.funcs.chg_is_suspend)}
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"></button>
                                <ul class="dropdown-menu">
                                    {if not empty($admin.funcs.2step)}
                                    <li><a class="dropdown-item" href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=2step&amp;admin_id={$admin.admin_id}"><i class="fa-solid fa-key fa-fw text-center"></i> {$LANG->getModule('2step_manager')}</a></li>
                                    {/if}
                                    {if not empty($admin.funcs.chg_is_suspend)}
                                    <li><a class="dropdown-item" href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=suspend&amp;admin_id={$admin.admin_id}"><i class="fa-solid fa-ban text-danger fa-fw text-center"></i> {$LANG->getModule('chg_is_suspend2')}</a></li>
                                    {/if}
                                </ul>
                            </div>
                            {/if}
                        </div>
                    </div>
                    {/if}
                </div>
            </div>
            <div class="card-body p-0 pb-1">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <div class="row g-2">
                            <div class="col-5 fw-medium">{$LANG->getModule('login')}</div>
                            <div class="col-7">{$admin.username}</div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row g-2">
                            <div class="col-5 fw-medium">{$LANG->getModule('email')}</div>
                            <div class="col-7 text-break">{$admin.show_mail}</div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row g-2">
                            <div class="col-5 fw-medium">{$LANG->getModule('name')}</div>
                            <div class="col-7">{$admin.full_name}</div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row g-2">
                            <div class="col-5 fw-medium">{$LANG->getModule('lev')}</div>
                            <div class="col-7">
                                {if $admin.lev gt 2}{$admin.level_txt}{else}<strong>{$admin.level_txt}</strong>{/if}
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row g-2">
                            <div class="col-5 fw-medium">{$LANG->getModule('position')}</div>
                            <div class="col-7">{$admin.position}</div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row g-2">
                            <div class="col-5 fw-medium">{$LANG->getModule('themeadmin')}</div>
                            <div class="col-7">{$admin.admin_theme ?: $LANG->getModule('theme_default')}</div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row g-2">
                            <div class="col-5 fw-medium">{$LANG->getModule('is_suspend')}</div>
                            <div class="col-7">{$admin.suspend_text}</div>
                        </div>
                    </li>
                    {if $smarty.const.NV_IS_SPADMIN}
                    <li class="list-group-item">
                        <div class="row g-2">
                            <div class="col-5 fw-medium">{$LANG->getModule('editor')}</div>
                            <div class="col-7">{$admin.editor ?: $LANG->getModule('not_use')}</div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row g-2">
                            <div class="col-5 fw-medium">{$LANG->getModule('allow_files_type')}</div>
                            <div class="col-7">{empty($admin.allow_files_type) ? $LANG->getGlobal('no') : join($admin.allow_files_type, ', ')}</div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row g-2">
                            <div class="col-5 fw-medium">{$LANG->getModule('allow_modify_files')}</div>
                            <div class="col-7">{$admin.allow_modify_files ? $LANG->getGlobal('yes') : $LANG->getGlobal('no')}</div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row g-2">
                            <div class="col-5 fw-medium">{$LANG->getModule('allow_create_subdirectories')}</div>
                            <div class="col-7">{$admin.allow_create_subdirectories ? $LANG->getGlobal('yes') : $LANG->getGlobal('no')}</div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row g-2">
                            <div class="col-5 fw-medium">{$LANG->getModule('allow_modify_subdirectories')}</div>
                            <div class="col-7">{$admin.allow_modify_subdirectories ? $LANG->getGlobal('yes') : $LANG->getGlobal('no')}</div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row g-2">
                            <div class="col-5 fw-medium">{$LANG->getModule('regtime')}</div>
                            <div class="col-7">{datetime_format($admin.regdate)}</div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row g-2">
                            <div class="col-5 fw-medium">{$LANG->getModule('last_login')}</div>
                            <div class="col-7">{$admin.last_login ? datetime_format($admin.last_login) : $LANG->getModule('last_login0')}</div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row g-2">
                            <div class="col-5 fw-medium">{$LANG->getModule('last_ip')}</div>
                            <div class="col-7">{$admin.last_ip}</div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row g-2">
                            <div class="col-5 fw-medium">{$LANG->getModule('browser')}</div>
                            <div class="col-7">{$admin.browser.name}</div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row g-2">
                            <div class="col-5 fw-medium">{$LANG->getModule('os')}</div>
                            <div class="col-7">{$admin.os.name}</div>
                        </div>
                    </li>
                    {/if}
                </ul>
            </div>
        </div>
    </div>
    {/foreach}
</div>
