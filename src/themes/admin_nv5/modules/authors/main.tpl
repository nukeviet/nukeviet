{foreach from=$ADMINS key=adminid item=adminrow}
<div class="card card-border-color card-border-color-primary" id="aid{$adminid}">
    <div class="card-header card-header-divider">
        <div class="tools">
            {if $adminrow.t_is_2step}
            <a href="{$NV_BASE_ADMINURL}index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&amp;{$NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$NV_OP_VARIABLE}=2step&amp;admin_id={$adminid}"><i class="fas fa-key" data-toggle="tooltip" title="{$LANG->get('2step_manager')}"></i></a>
            {/if}
            {if $adminrow.t_is_edit}
            <a href="{$NV_BASE_ADMINURL}index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&amp;{$NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$NV_OP_VARIABLE}=edit&amp;admin_id={$adminid}"><i class="fas fa-pencil-alt" data-toggle="tooltip" title="{$LANG->get('edit')}"></i></a>
            {/if}
            {if $adminrow.t_is_suspend}
            <a href="{$NV_BASE_ADMINURL}index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&amp;{$NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$NV_OP_VARIABLE}=suspend&amp;admin_id={$adminid}">{if $adminrow.is_suspend}<i class="fas fa-user-check" data-toggle="tooltip" title="{$LANG->get('suspend0')}"></i>{else}<i class="fas fa-user-slash" data-toggle="tooltip" title="{$LANG->get('suspend1')}"></i>{/if}</a>
            {/if}
            {if $adminrow.t_is_del}
            <a href="{$NV_BASE_ADMINURL}index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&amp;{$NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$NV_OP_VARIABLE}=del&amp;admin_id={$adminid}"><i class="fas fa-trash-alt" data-toggle="tooltip" title="{$LANG->get('delete')}"></i></a>
            {/if}
        </div>
        {if $adminid eq $ADMIN_INFO.admin_id}
        {$LANG->get('admin_info_title2', $adminrow.full_name)}
        {else}
        {$LANG->get('admin_info_title1', $adminrow.full_name)}
        {/if}
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12 col-sm-7 col-md-8 col-lg-9 col-xl-10">
                <dl class="row">
                    <dt class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 text-truncate">{$LANG->get('login')}</dt>
                    <dd class="col-12 col-sm-6 col-md-8 col-lg-9 col-xl-10 text-truncate">{$adminrow.login}</dd>
                    <dt class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 text-truncate">{$LANG->get('email')}</dt>
                    <dd class="col-12 col-sm-6 col-md-8 col-lg-9 col-xl-10 text-truncate">{$adminrow.email}</dd>
                    <dt class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 text-truncate">{$LANG->get('name')}</dt>
                    <dd class="col-12 col-sm-6 col-md-8 col-lg-9 col-xl-10 text-truncate">{$adminrow.full_name}</dd>
                    <dt class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 text-truncate">{$LANG->get('lev')}</dt>
                    <dd class="col-12 col-sm-6 col-md-8 col-lg-9 col-xl-10 text-truncate"><strong class="text-danger">{$adminrow.level_txt}</strong></dd>
                    <dt class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 text-truncate">{$LANG->get('position')}</dt>
                    <dd class="col-12 col-sm-6 col-md-8 col-lg-9 col-xl-10 text-truncate">{$adminrow.position}</dd>
                    <dt class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 text-truncate">{$LANG->get('themeadmin')}</dt>
                    <dd class="col-12 col-sm-6 col-md-8 col-lg-9 col-xl-10 text-truncate">{if empty($adminrow.admin_theme)}{$LANG->get('theme_default')}{else}{$adminrow.admin_theme}{/if}</dd>
                    {if $adminrow.is_suspend}
                    <dt class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 text-truncate">{$LANG->get('is_suspend')}</dt>
                    <dd class="col-12 col-sm-6 col-md-8 col-lg-9 col-xl-10">
                        {$LANG->get('is_suspend1', $adminrow.suspen_starttime, $adminrow.suspen_name, $adminrow.suspen_info, $adminrow.suspen_adminlink)}
                    </dd>
                    {/if}
                </dl>
            </div>
            <div class="col-12 col-sm-5 col-md-4 col-lg-3 col-xl-2">
                <div class="author-ribon{if $adminrow.is_suspend or empty($adminrow.active)} danger{/if}">
                    <div class="ribon-status">
                        {$LANG->get('is_suspend')}
                    </div>
                    <div class="ribon-stars">
                        {for $lev=1 to $adminrow.levelloop}
                        <i class="fas fa-star"></i>
                        {/for}
                    </div>
                    <div class="ribon-active">
                        {if $adminrow.is_suspend or empty($adminrow.active)}
                        {$LANG->get('is_suspend2')}
                        {else}
                        {$LANG->get('is_suspend0')}
                        {/if}
                    </div>
                </div>
            </div>
        </div>
        {if $IS_SPADMIN}
        <div class="author-detail">
            <div class="detail-head my-4">
                {$LANG->get('other_info')}
            </div>
            <div class="detail-body px-3">
                <div class="body-item row">
                    <div class="col-6 p-3">{$LANG->get('editor')}</div>
                    <div class="col-6 p-3">{if empty($adminrow.editor)}{$LANG->get('not_use')}{else}{$adminrow.editor}{/if}</div>
                </div>
                <div class="body-item row">
                    <div class="col-6 p-3">{$LANG->get('allow_files_type')}</div>
                    <div class="col-6 p-3">{if empty($adminrow.allow_files_type)}{$LANG->get('no')}{else}{$adminrow.allow_files_type}{/if}</div>
                </div>
                <div class="body-item row">
                    <div class="col-6 p-3">{$LANG->get('allow_modify_files')}</div>
                    <div class="col-6 p-3">{if empty($adminrow.allow_modify_files)}{$LANG->get('no')}{else}{$LANG->get('yes')}{/if}</div>
                </div>
                <div class="body-item row">
                    <div class="col-6 p-3">{$LANG->get('allow_create_subdirectories')}</div>
                    <div class="col-6 p-3">{if empty($adminrow.allow_create_subdirectories)}{$LANG->get('no')}{else}{$LANG->get('yes')}{/if}</div>
                </div>
                <div class="body-item row">
                    <div class="col-6 p-3">{$LANG->get('allow_modify_subdirectories')}</div>
                    <div class="col-6 p-3">{if empty($adminrow.allow_modify_subdirectories)}{$LANG->get('no')}{else}{$LANG->get('yes')}{/if}</div>
                </div>
                <div class="body-item row">
                    <div class="col-6 p-3">{$LANG->get('regtime')}</div>
                    <div class="col-6 p-3">{$adminrow.regtime}</div>
                </div>
                <div class="body-item row">
                    <div class="col-6 p-3">{$LANG->get('last_login')}</div>
                    <div class="col-6 p-3">{$adminrow.last_login}</div>
                </div>
                <div class="body-item row">
                    <div class="col-6 p-3">{$LANG->get('last_ip')}</div>
                    <div class="col-6 p-3">{$adminrow.last_ip}</div>
                </div>
                <div class="body-item row">
                    <div class="col-6 p-3">{$LANG->get('browser')}</div>
                    <div class="col-6 p-3">{$adminrow.browser}</div>
                </div>
                <div class="body-item row">
                    <div class="col-6 p-3">{$LANG->get('os')}</div>
                    <div class="col-6 p-3">{$adminrow.os}</div>
                </div>
            </div>
        </div>
        {/if}
    </div>
</div>
{/foreach}
