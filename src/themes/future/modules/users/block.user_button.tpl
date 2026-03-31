{assign var='iconUserLevel' value=[
    '1' => '<i class="fa-solid fa-star text-warning"></i><i class="fa-solid fa-star text-warning"></i><i class="fa-solid fa-star text-warning"></i>',
    '2' => '<i class="fa-regular fa-star text-warning"></i><i class="fa-solid fa-star text-warning"></i><i class="fa-solid fa-star text-warning"></i>',
    '3' => '<i class="fa-regular fa-star text-warning"></i><i class="fa-regular fa-star text-warning"></i><i class="fa-solid fa-star text-warning"></i>',
    'user' => '<i class="fa-solid fa-user"></i>'
]}
<div class="site-user-button dropdown">
    {if $smarty.const.NV_IS_USER}
    {* Đã đăng nhập *}
    <a class="user-button" title="{$USER.full_name}" href="#" data-bs-toggle="dropdown" data-bs-auto-close="outside" data-bs-offset="8,0" aria-expanded="false">
        {if empty($USER.avata)}
        <span class="no-avatar d-flex align-items-center justify-content-center text-bg-primary rounded-circle w-100 h-100"><span>{$USER.chars}</span></span>
        {else}
        <img src="{$USER.avata}" alt="{$USER.full_name}">
        {/if}
    </a>
    <div class="dropdown-menu dropdown-menu-end p-0" data-toggle="form">
        <div class="user-bldropdown">
            <div class="alert alert-info mb-0 d-none" role="alert" data-toggle="message"></div>
            <div data-toggle="ct">
                <div class="mb-1 fw-medium text-break">
                    <span class="text-nowrap">{$iconUserLevel[$smarty.const.NV_IS_ADMIN ? $ADMIN.level : 'user']}</span>
                    {$LANG->getGlobal($smarty.const.NV_IS_ADMIN ? 'admin_account' : 'your_account')}: <strong>{$USER.full_name}</strong>
                </div>
                <div class="d-flex gap-2 mb-2">
                    <div class="u-avatar mt-2">
                        <a title="{$LANG->getModule('avatar')}" href="#" data-toggle="changeAvatar"
                            data-url="{$smarty.const.NV_BASE_SITEURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE}&amp;{$smarty.const.NV_OP_VARIABLE}=avatar/upd"
                        >
                            {if empty($USER.avata)}
                            <span class="no-avatar d-flex align-items-center justify-content-center text-bg-primary"><span class="fs-1">{$USER.chars}</span></span>
                            {else}
                            <img src="{$USER.avata}" alt="{$USER.full_name}" width="80" height="80">
                            {/if}
                        </a>
                    </div>
                    <div class="u-info">
                        <ul class="list-unstyled mb-0">
                            <li><a href="{$smarty.const.NV_BASE_SITEURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE}" class="link-body-emphasis">{$LANG->getModule('user_info')}</a></li>
                            <li><a href="{$smarty.const.NV_BASE_SITEURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE}&amp;{$smarty.const.NV_OP_VARIABLE}=editinfo" class="link-body-emphasis">{$LANG->getModule('editinfo')}</a></li>
                            {if $smarty.const.NV_OPENID_ALLOWED}
                            <li><a href="{$smarty.const.NV_BASE_SITEURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE}&amp;{$smarty.const.NV_OP_VARIABLE}=editinfo/openid" class="link-body-emphasis">{$LANG->getModule('openid_administrator')}</a></li>
                            {/if}
                            {if not empty($SITE_MODS.myapi)}
                            <li><a href="{$smarty.const.NV_BASE_SITEURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}=myapi" class="link-body-emphasis">{$LANG->getGlobal('myapis')}</a></li>
                            {/if}
                        </ul>
                    </div>
                </div>
                {if $smarty.const.NV_IS_ADMIN}
                <hr class="my-2">
                <div class="fw-medium">{$LANG->getGlobal('for_admin')}:</div>
                <ul class="list-unstyled mb-2">
                    <li><a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}" class="link-body-emphasis"><i class="fa-solid fa-gear text-muted text-center fa-fw"></i> {$LANG->getGlobal('admin_page')}</a></li>
                    {if $smarty.const.NV_IS_MODADMIN and not empty($MODULE_INFO.admin_file)}
                    <li><a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}" class="link-body-emphasis"><i class="fa-solid fa-key text-muted text-center fa-fw"></i> {$LANG->getGlobal('admin_module_sector')} {$MODULE_INFO.admin_title ?: $MODULE_INFO.custom_title}</a></li>
                    {/if}
                    {if $smarty.const.NV_IS_SPADMIN}
                    <li><a href="{$smarty.const.NV_BASE_SITEURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;drag_block={$smarty.const.NV_IS_DRAG_BLOCK ? 0 : 1}" class="link-body-emphasis"><i class="fa-solid fa-arrows-up-down-left-right text-muted text-center fa-fw"></i> {$LANG->getGlobal($smarty.const.NV_IS_DRAG_BLOCK ? 'no_drag_block' : 'drag_block')}</a></li>
                    {/if}
                    <li><a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}=authors&amp;id={$ADMIN.admin_id}" class="link-body-emphasis"><i class="fa-solid fa-user text-muted text-center fa-fw"></i> {$LANG->getGlobal('admin_view')}</a></li>
                </ul>
                {/if}
            </div>
        </div>
        <div class="bg-body-tertiary px-3 py-2 rounded-bottom-2 d-flex justify-content-between align-items-center gap-2">
            <div class="u-current-login">
                {$USER.current_login|ddatetime}
            </div>
            <div class="u-btn-logout">
                <button type="button" class="btn btn-secondary btn-sm" data-toggle="{$smarty.const.NV_IS_ADMIN ? 'nv_admin_logout' : 'bt_logout'}" data-module="{$MODULE}"><i class="fa-solid fa-arrow-right-from-bracket" data-icon="fa-arrow-right-from-bracket"></i> {$LANG->getModule('logout_title')}</button>
            </div>
        </div>
    </div>
    {elseif $smarty.const.SSO_SERVER and ($smarty.const.NV_IS_USER_FORUM or $smarty.const.NV_MY_DOMAIN neq $smarty.const.SSO_REGISTER_DOMAIN)}
    {* Nút đăng nhập SSO *}
    <a href="{$LINK_LOGIN}" class="btn btn-primary btn-sm text-nowrap">{$LANG->getGlobal('signin')}</a>
    {else}
    {* Nút đăng nhập thành viên *}
    <button type="button" class="btn btn-primary btn-sm text-nowrap" title="{$LANG->getGlobal('signin')} - {$LANG->getGlobal('register')}"
        data-toggle="userLoginBlButton" data-bs-toggle="dropdown"
        data-bs-auto-close="outside" data-bs-offset="8,0" aria-expanded="false"
        data-loaded="0" data-url="{$smarty.const.NV_BASE_SITEURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE}&amp;{$smarty.const.NV_OP_VARIABLE}=login"
        data-redirect="{$NV_REDIRECT}"
    >{$LANG->getGlobal('signin')}</button>
    <div class="dropdown-menu dropdown-menu-end p-3">
        <div class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">{$LANG->getGlobal('wait_page_load')}</span>
            </div>
        </div>
    </div>
    {/if}
</div>
