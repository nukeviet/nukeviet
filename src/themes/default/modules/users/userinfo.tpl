{if not empty($CHANGEPASS_INFO)}
<div class="alert alert-danger">
    {$CHANGEPASS_INFO}
</div>
{/if}
{if not empty($CHANGEEMAIL_INFO)}
<div class="alert alert-danger">
    {$CHANGEEMAIL_INFO}
</div>
{/if}
<div class="mb-3 shadow-lg bg-body rounded-4">
    <div class="fh-60 text-bg-primary rounded-top-4 px-2 px-lg-3 pt-2 pt-lg-3">
        <div class="position-relative h-100 text-white">
            <div class="position-absolute top-100 start-0 translate-middle-y">
                {if $IMG.src}
                <img src="{$IMG.src}" alt="{$USER.username}" title="{$USER.username}" width="80" height="80" class="fw-80 fh-80 rounded-circle object-fit-cover">
                {else}
                <span class="avatar-letters avatar-letters-lg fw-80 fh-80" title="{$USER.username}" style="background-color:{$USER.avatar_color}" aria-hidden="true">{$USER.avatar_letters}</span>
                {/if}
                <a href="#" data-toggle="changeAvatar" data-url="{$URL_AVATAR}" data-action="upd" data-title="{$LANG->getModule('change_avatar')}" class="position-absolute end-0 bottom-0 d-flex align-items-center justify-content-center fw-25 fh-25 bg-body rounded-circle text-center text-decoration-none text-muted shadow-sm" title="{$LANG->getModule('change_avatar')}" aria-label="{$LANG->getModule('change_avatar')}">
                    <i class="fa-solid fa-pencil small"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="d-flex">
        <div class="ps-2 ps-lg-3 flex-shrink-0"></div>
        <div class="fw-80 flex-shrink-0"></div>
        <div class="flex-fill px-2 px-lg-3 py-2">
            <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                <h1 class="h3 mb-0">{$USER.full_name}</h1>
                <span class="text-break badge text-bg-secondary rounded-pill mt-1">
                    {$USER.username}
                </span>
            </div>
            <div class="d-flex align-items-center gap-2 text-muted text-break mb-1">
                <i class="fa-regular fa-envelope"></i> {$USER.email}
            </div>
        </div>
    </div>
    <div class="bg-body-tertiary rounded-bottom-4 border-top">
        <div class="row g-0 align-items-center">
            <div class="col-lg-4">
                <div class="p-2 p-lg-3">
                    <div class="d-flex align-items-center gap-2 gap-lg-3">
                        <i class="fa-solid fa-shield-halved fa-lg text-muted flex-shrink-0"></i>
                        <div class="flex-fill">
                            <div class="text-muted small text-uppercase">{$LANG->getModule('current_mode')}:</div>
                            <div class="text-break">
                                {$USER.current_mode}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 border-lg-start">
                <div class="p-2 p-lg-3">
                    <div class="d-flex align-items-center gap-2 gap-lg-3">
                        <i class="fa-regular fa-clock fa-lg text-muted flex-shrink-0"></i>
                        <div class="flex-fill">
                            <div class="text-muted small text-uppercase">{$LANG->getModule('current_login')}:</div>
                            <div class="text-break">
                                {$USER.current_login}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 border-lg-start">
                <div class="p-2 p-lg-3">
                    <div class="d-flex align-items-center gap-2 gap-lg-3">
                        <i class="fa-solid fa-map-location-dot fa-lg text-muted flex-shrink-0"></i>
                        <div class="flex-fill">
                            <div class="text-muted small text-uppercase">{$LANG->getModule('ip')}:</div>
                            <div class="text-break">
                                {$USER.current_ip}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{if $SHOW_CHANGE_LOGIN_NOTE}
<div class="alert alert-danger">
    <i class="fa-solid fa-triangle-exclamation"></i> {$USER.change_name_info}
</div>
{/if}
{if $SHOW_PASS_EMPTY}
<div class="alert alert-danger">
    <i class="fa-solid fa-triangle-exclamation"></i> {$USER.pass_empty_note}
</div>
{/if}
{if $SHOW_QUESTION_EMPTY}
<div class="alert alert-danger">
    <i class="fa-solid fa-triangle-exclamation"></i> {$USER.question_empty_note}
</div>
{/if}
<div class="card mb-3 ">
    <div class="card-body">
        <div class="h5 border-bottom mb-3 pb-3">
            {$LANG->getModule('editcensor_info_basic')}
        </div>
        <div class="row g-2 g-lg-3">
            <div class="col-lg-6">
                <div class="text-muted">{$LANG->getModule('regdate')}:</div>
                <div class="fw-medium">{$USER.regdate}</div>
            </div>
            <div class="col-lg-6">
                <div class="text-muted">{$LANG->getModule('gender')}:</div>
                <div class="fw-medium">{$USER.gender}</div>
            </div>
            <div class="col-lg-6">
                <div class="text-muted">{$LANG->getModule('showmail')}:</div>
                <div class="fw-medium">{$USER.view_mail}</div>
            </div>
            {if $SHOW_LANGINTERFACE}
            <div class="col-lg-6">
                <div class="text-muted">{$LANG->getGlobal('langinterface')}:</div>
                <div class="fw-medium">{$USER.langinterface}</div>
            </div>
            {/if}
            <div class="col-lg-6">
                <div class="text-muted">{$LANG->getModule('birthday')}:</div>
                <div class="fw-medium">{$USER.birthday}</div>
            </div>
            {if $SHOW_GROUP_MANAGE}
            <div class="col-lg-6">
                <div class="text-muted">{$LANG->getModule('group_manage_count')}:</div>
                <div class="fw-medium">
                    <a href="{$URL_GROUPS}" title="{$LANG->getModule('group_manage_list')}">{$USER.group_manage}</a>
                </div>
            </div>
            {/if}
            <div class="col-lg-6">
                <div class="text-muted">{$LANG->getModule('st_login2')}:</div>
                <div class="fw-medium">{$USER.st_login}</div>
            </div>
            <div class="col-lg-6">
                <div class="text-muted">{$LANG->getModule('login_name')}:</div>
                <div class="fw-medium">{$USER.login_name}</div>
            </div>
            <div class="col-lg-6">
                <div class="text-muted">{$LANG->getModule('2step_status')}:</div>
                <div class="fw-medium">{$USER.active2step} (<a href="{$URL_2STEP}">{$LANG->getModule('2step_link')}</a>)</div>
            </div>
            <div class="col-lg-6">
                <div class="text-muted">{$LANG->getModule('last_login')}:</div>
                <div class="fw-medium">{$USER.last_login}</div>
            </div>
        </div>
    </div>
</div>
{if not empty($CUSTOM_FIELDS)}
<div class="card mb-3">
    <div class="card-body">
        <div class="h5 border-bottom mb-3 pb-3">
            {$LANG->getModule('editcensor_info_custom')}
        </div>
        <div class="row g-2 g-lg-3">
            {foreach from=$CUSTOM_FIELDS item=field}
            <div class="col-lg-6">
                <div class="text-muted">{$field.title}:</div>
                <div class="fw-medium">{$field.value}</div>
            </div>
            {/foreach}
        </div>
    </div>
</div>
{/if}
{if not empty($NAVBARS)}
<ul class="list-inline">
    {foreach from=$NAVBARS item=nav}
    <li class="list-inline-item text-nowrap me-3"><a href="{$nav.href}"><i class="fa-solid fa-caret-right"></i> {$nav.title}</a></li>
    {/foreach}
</ul>
{/if}
