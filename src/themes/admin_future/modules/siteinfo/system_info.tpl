<div class="row">
    <div class="col-xl-6">
        <div class="card text-bg-primary mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">{$LANG->getModule('site_configs_info')}</h5>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-5 fw-medium">{$LANG->getModule('site_domain')}</div>
                        <div class="col-7">{$smarty.const.NV_MY_DOMAIN}</div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-5 fw-medium">{$LANG->getModule('site_url')}</div>
                        <div class="col-7">{$GCONFIG.site_url}</div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-5 fw-medium">{$LANG->getModule('site_root')}</div>
                        <div class="col-7">{$smarty.const.NV_ROOTDIR}</div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-5 fw-medium">{$LANG->getModule('site_script_path')}</div>
                        <div class="col-7">{$NVRQ->base_siteurl}</div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-5 fw-medium">{$LANG->getModule('site_cookie_domain')}</div>
                        <div class="col-7">{$GCONFIG.cookie_domain}</div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-5 fw-medium">{$LANG->getModule('site_cookie_path')}</div>
                        <div class="col-7">{$GCONFIG.cookie_path}</div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-5 fw-medium">{$LANG->getModule('site_session_path')}</div>
                        <div class="col-7">{$SYS.sessionpath}</div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-5 fw-medium">{$LANG->getModule('site_timezone')}</div>
                        <div class="col-7">{$smarty.const.NV_SITE_TIMEZONE_NAME}{if $smarty.const.NV_SITE_TIMEZONE_GMT_NAME neq $smarty.const.NV_SITE_TIMEZONE_NAME} ({$smarty.const.NV_SITE_TIMEZONE_GMT_NAME}){/if}</div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card text-bg-primary mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">{$LANG->getModule('server_configs_info')}</h5>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-5 fw-medium">{$LANG->getModule('version')}</div>
                        <div class="col-7">
                            {$GCONFIG.version}{if not empty($smarty.const.NV_IS_GODADMIN)}
                            <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}=webtools&amp;{$smarty.const.NV_OP_VARIABLE}=checkupdate">{$LANG->getModule('checkversion')}</a>
                            {/if}
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-5 fw-medium">{$LANG->getModule('server_phpversion')}</div>
                        <div class="col-7">{$PHPVERSION}</div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-5 fw-medium">{$LANG->getModule('server_api')}</div>
                        <div class="col-7">{$SERVER_API}</div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-5 fw-medium">{$LANG->getModule('server_phpos')}</div>
                        <div class="col-7">{$SYS.os}</div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-5 fw-medium">{$LANG->getModule('server_databaseversion')}</div>
                        <div class="col-7">{$DBVERSION}</div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    {if not empty($smarty.const.NV_IS_GODADMIN) and not $IS_WIN}
    <div class="col-12">
        <div class="card text-bg-primary">
            <div class="card-header">
                <h5 class="card-title mb-0">{$LANG->getModule('chmod')}</h5>
            </div>
            <ul class="list-group list-group-flush">
                {foreach from=$CHMODS item=chmod}
                <li class="list-group-item">
                    {$chmod.key} {if $chmod.value}<i class="fa-solid fa-circle-check text-success" aria-label="{$LANG->getModule('chmod_noneed')}"></i>{else}<span class="badge text-bg-warning">{$LANG->getModule('chmod_need')}</span>{/if}
                </li>
                {/foreach}
            </ul>
        </div>
    </div>
    {/if}
</div>
