<div class="row justify-content-center" data-area="usersSecurityPrivacy" data-checkss="{$CHECKSS}" data-auto-toast="{$DATA.auto_toast}" data-next-offset="{$NEXT_OFFSET}" data-page="{$DATA.page}">
    <div class="col-12 col-lg-10 col-xl-8">
        <h1 class="h3 mb-2">{$LANG->getModule('security_privacy')}</h1>
        <p class="text-muted mb-4">{$LANG->getModule('security_privacy_des')}.</p>
        <div class="rounded-4 border shadow-lg p-4">
            <h2 class="h5 mb-3">{$LANG->getModule('login_session')}</h2>
            {if empty($LOGINS)}
            <div class="alert alert-danger mb-0">{$LANG->getModule('login_session_none')}.</div>
            {else}
            <p class="mb-3">{$LANG->getModule('login_session_explain')}.</p>
            <div data-area="loginsCtn">
                {include file='security_privacy_logins.tpl'}
            </div>
            {if $HAS_MORE}
            <div class="text-center mt-3" data-area="loginMoreCtn">
                <button type="button" class="btn btn-primary" data-toggle="usersLoginMore">
                    <i class="fa-solid fa-angles-down" data-icon="fa-angles-down"></i> {$LANG->getGlobal('view_more')}
                </button>
            </div>
            {/if}
            {if $HAS_LOGOUT_ALL}
            <hr class="my-4">
            <h2 class="h5 mb-3">{$LANG->getModule('security_actions')}</h2>
            <div class="border rounded-3 p-3 bg-body-tertiary">
                <div class="d-flex flex-wrap flex-sm-nowrap justify-content-between align-items-center gap-2">
                    <div>
                        <div><strong>{$LANG->getModule('security_actions_logout_all')}</strong></div>
                        <div class="small">{$LANG->getModule('security_actions_logout_all1')}.</div>
                    </div>
                    <div class="flex-shrink-0">
                        <button type="button" class="btn btn-danger text-nowrap" data-toggle="usersLoginRemoveAll">
                            <i class="fa-solid fa-power-off" data-icon="fa-power-off"></i> {$LANG->getModule('security_actions_logout_all2')}
                        </button>
                    </div>
                </div>
            </div>
            {/if}
            {/if}
            <hr class="my-4">
            <div class="text-center">
                <a class="link-danger small" href="{$DATA.link_delete}">{$LANG->getModule('secacts_delaccount')}</a>
            </div>
        </div>
    </div>
</div>
