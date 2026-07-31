<div class="mb-3 shadow-lg bg-body rounded-4">
    <div class="fh-60 text-bg-primary rounded-top-4 px-2 px-lg-3 pt-2 pt-lg-3">
        <div class="position-relative h-100 text-white">
            <div class="position-absolute top-100 start-0 translate-middle-y">
                {if $USER.avata}
                <img src="{$USER.avata}" alt="{$USER.username}" title="{$USER.username}" width="80" height="80" class="fw-80 fh-80 rounded-circle object-fit-cover">
                {else}
                <span class="avatar-letters avatar-letters-lg fw-80 fh-80" style="background-color:{$USER.avatar_color}" title="{$USER.username}" aria-hidden="true">{$USER.avatar_letters}</span>
                {/if}
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
            {if $SHOW_EMAIL}
            <div class="d-flex align-items-center gap-2 text-muted text-break mb-1">
                <i class="fa-regular fa-envelope"></i> {$USER.email}
            </div>
            {/if}
            <div class="d-flex align-items-center gap-2 text-muted text-break">
                <i class="fa-regular fa-clock"></i> {$LANG->getModule('last_login')}: {$USER.last_login}
            </div>
        </div>
    </div>
    {if $SHOW_ADMIN and ($USER.allow_edit or $USER.allow_delete)}
    <div class="bg-body-tertiary rounded-bottom-4 border-top px-2 px-lg-3 py-2 d-flex flex-wrap gap-2">
        {if $USER.allow_edit}
        <a href="{$USER.link_edit}" class="btn btn-sm btn-secondary">
            <i class="fa-solid fa-pencil"></i> {$LANG->getGlobal('edit')}
        </a>
        {/if}
        {if $USER.allow_delete}
        <button type="button" class="btn btn-sm btn-danger" data-toggle="admindeluser" data-userid="{$USER.userid}" data-url="{$USER.link_delete}" data-back="{$USER.link_delete_callback}" data-checkss="{$CHECKSS}">
            <i class="fa-solid fa-trash" data-icon="fa-trash"></i> {$LANG->getGlobal('delete')}
        </button>
        {/if}
    </div>
    {/if}
</div>
<div class="card mb-3">
    <div class="card-body">
        <div class="h5 border-bottom mb-3 pb-3">
            {$LANG->getModule('editcensor_info_basic')}
        </div>
        <div class="row g-2 g-lg-3">
            <div class="col-lg-6">
                <div class="text-muted">{$LANG->getModule('name')}:</div>
                <div class="fw-medium">{$USER.full_name}</div>
            </div>
            <div class="col-lg-6">
                <div class="text-muted">{$LANG->getModule('birthday')}:</div>
                <div class="fw-medium">{$USER.birthday}</div>
            </div>
            <div class="col-lg-6">
                <div class="text-muted">{$LANG->getModule('gender')}:</div>
                <div class="fw-medium">{$USER.gender}</div>
            </div>
            <div class="col-lg-6">
                <div class="text-muted">{$LANG->getModule('regdate')}:</div>
                <div class="fw-medium">{$USER.regdate}</div>
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
