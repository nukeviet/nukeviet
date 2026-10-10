{if $SHOW_ADD_MEMBER}
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/select2/select2.min.js"></script>
<script src="{$smarty.const.ASSETS_LANG_STATIC_URL}/js/select2/i18n/{$smarty.const.NV_LANG_INTERFACE}.js"></script>
{/if}
<div data-area="page" data-checkss="{$DATA.checkss}" data-gid="{$DATA.group_id}">
    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex flex-wrap align-items-center gap-3">
                <img src="{$DATA.group_avatar}" alt="{$DATA.title}" title="{$DATA.title}" width="80" height="80" class="fw-80 fh-80 flex-shrink-0 rounded-circle object-fit-cover">
                <div class="flex-fill">
                    <h1 class="h4 mb-1 text-break">{$DATA.title}</h1>
                    {if $DATA.description}
                    <div class="text-muted text-break">{$DATA.description}</div>
                    {/if}
                </div>
                {if $SHOW_TOOLS}
                <div class="d-flex flex-wrap gap-2">
                    <a href="{$EDIT_GROUP_URL}" class="btn btn-secondary" title="{$LANG->getGlobal('edit')}"
                        aria-label="{$LANG->getGlobal('edit')}"
                        data-bs-toggle="tooltip" data-bs-title="{$LANG->getGlobal('edit')}"
                    >
                        <i class="fa-solid fa-pencil"></i>
                    </a>
                    {if $INFORM_NOTIFICATIONS_URL}
                    <a href="{$INFORM_NOTIFICATIONS_URL}" class="btn btn-secondary" title="{$LANG->getGlobal('inform_notifications')}"
                        aria-label="{$LANG->getGlobal('inform_notifications')}"
                        data-bs-toggle="tooltip" data-bs-title="{$LANG->getGlobal('inform_notifications')}"
                    >
                        <i class="fa-regular fa-bell"></i>
                    </a>
                    {/if}
                    {if $DATA.config.access_addus}
                    <a href="{$MODULE_URL}=register/{$DATA.group_id}" class="btn btn-secondary" title="{$LANG->getModule('addusers')}"
                        aria-label="{$LANG->getModule('addusers')}"
                        data-bs-toggle="tooltip" data-bs-title="{$LANG->getModule('addusers')}"
                    >
                        <i class="fa-solid fa-user-plus"></i>
                    </a>
                    {/if}
                    {if $DATA.config.access_waiting}
                    <button type="button" class="btn btn-secondary" data-toggle="groupUserWaiting" data-title="{$LANG->getModule('user_waiting')}"
                        title="{$LANG->getModule('user_waiting')}" aria-label="{$LANG->getModule('user_waiting')}"
                        data-bs-toggle="tooltip" data-bs-title="{$LANG->getModule('user_waiting')}"
                        data-bs-trigger="hover"
                    >
                        <i class="fa-solid fa-magnifying-glass-plus" data-icon="fa-magnifying-glass-plus"></i>
                    </button>
                    {/if}
                </div>
                {/if}
            </div>
        </div>
        <div class="card-footer bg-body-tertiary border-top">
            <div class="row g-2 g-lg-3">
                <div class="col-md-4">
                    <div class="text-muted small text-uppercase">{$LANG->getModule('group_type')}</div>
                    <div class="fw-medium text-break">
                        {$DATA.group_type_mess}{if $DATA.group_type_note} <span class="text-muted fw-normal">({$DATA.group_type_note})</span>{/if}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small text-uppercase">{$LANG->getModule('group_exp_time')}</div>
                    <div class="fw-medium">{$DATA.exp}</div>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small text-uppercase">{$LANG->getModule('group_userr')}</div>
                    <div class="fw-medium">{$DATA.numbers|dnumber}</div>
                </div>
            </div>
        </div>
    </div>
    {if $DATA.content}
    <div class="card mb-3">
        <div class="card-body">
            {$DATA.content}
        </div>
    </div>
    {/if}
    {if $SHOW_ADD_MEMBER}
    <div class="card mb-3">
        <div class="card-body">
            <label class="form-label" for="uid">{$LANG->getModule('addMemberToGroup')}</label>
            <div class="input-group">
                <select name="uid" id="uid" class="form-select" data-toggle="groupAddUserSelect"
                    data-placeholder="{$LANG->getModule('addMemberToGroup')}"
                    data-gid="{$DATA.group_id}" data-checkss="{$DATA.checkss}"
                    data-min-search="{$MIN_SEARCH}"></select>
                <button type="button" class="btn btn-primary" data-toggle="groupAddUser"
                    data-msg-nochoice="{$LANG->getModule('choiceUserID')}"
                    title="{$LANG->getModule('addMemberToGroup')}" aria-label="{$LANG->getModule('addMemberToGroup')}">
                    <i class="fa-solid fa-plus" data-icon="fa-plus"></i>
                </button>
            </div>
        </div>
    </div>
    {/if}
    {if empty($GROUP_USERS)}
    <div class="alert alert-warning">
        <i class="fa-solid fa-circle-info me-1"></i>{$LANG->getModule('error_users_not_found')}
    </div>
    {else}
    {foreach from=$GROUP_USERS key=type item=users}
    {assign var=has_tools value=($type != 'leaders')}
    {assign var=caption_key value=$type|cat:'_in_group_caption'}
    <div class="card mb-3" id="id_{$type}">
        <div class="card-header d-flex align-items-center gap-2">
            <h2 class="h6 mb-0">{$LANG->getModule($caption_key)}</h2>
            <span class="badge text-bg-secondary rounded-pill">{$DATA.data_number[$type]|dnumber}</span>
        </div>
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead>
                    <tr>
                        <th class="text-nowrap text-center" style="width:10%">{$LANG->getModule('STT')}</th>
                        <th class="text-nowrap" style="width:{if $has_tools}70%{else}90%{/if}">{$LANG->getModule('account')} ({$LANG->getModule('nametitle')})</th>
                        {if $has_tools}<th style="width:20%"></th>{/if}
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$users item=user name=userloop}
                    <tr>
                        <td class="text-center">{$smarty.foreach.userloop.iteration}</td>
                        <td class="text-break">
                            {if $DATA.viewuser_allowed and $type != 'pending'}
                            <a href="{$user.link_view}">{$user.username} ({$user.full_name})</a>
                            {else}
                            {$user.username} ({$user.full_name})
                            {/if}
                        </td>
                        {if $has_tools}
                        <td class="text-end text-nowrap">
                            {if $user.tools_allowed}
                                {if $type == 'pending'}
                                <button type="button" class="btn btn-success btn-sm" data-toggle="groupApproved" data-id="{$user.userid}"
                                    title="{$LANG->getModule('approved')}" aria-label="{$LANG->getModule('approved')}"
                                    data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-title="{$LANG->getModule('approved')}"
                                >
                                    <i class="fa-solid fa-check" data-icon="fa-check"></i>
                                </button>
                                <button type="button" class="btn btn-warning btn-sm" data-toggle="groupDenied" data-id="{$user.userid}"
                                    title="{$LANG->getModule('denied')}" aria-label="{$LANG->getModule('denied')}"
                                    data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-title="{$LANG->getModule('denied')}"
                                >
                                    <i class="fa-solid fa-circle-minus" data-icon="fa-circle-minus"></i>
                                </button>
                                {else}
                                {if not $user.is_admin and $DATA.config.access_editus}
                                <a href="{$user.link_edit}" class="btn btn-secondary btn-sm"
                                    title="{$LANG->getGlobal('edit')}" aria-label="{$LANG->getGlobal('edit')}"
                                    data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-title="{$LANG->getGlobal('edit')}"
                                >
                                    <i class="fa-solid fa-pencil"></i>
                                </a>
                                {/if}
                                {if $DATA.config.access_groups_del}
                                <button type="button" class="btn btn-warning btn-sm" data-toggle="groupExclude" data-id="{$user.userid}"
                                    title="{$LANG->getModule('exclude_user2')}" aria-label="{$LANG->getModule('exclude_user2')}"
                                    data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-title="{$LANG->getModule('exclude_user2')}"
                                >
                                    <i class="fa-solid fa-circle-minus" data-icon="fa-circle-minus"></i>
                                </button>
                                {/if}
                                {if not $user.is_admin and $DATA.config.access_delus and $user.group_count == 1}
                                <button type="button" class="btn btn-danger btn-sm" data-toggle="groupDelUser" data-id="{$user.userid}"
                                    title="{$LANG->getModule('access_delus')}" aria-label="{$LANG->getModule('access_delus')}"
                                    data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-title="{$LANG->getModule('access_delus')}"
                                >
                                    <i class="fa-solid fa-trash" data-icon="fa-trash"></i>
                                </button>
                                {/if}
                                {/if}
                            {/if}
                        </td>
                        {/if}
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
        {if $DATA.generate_page}
        <div class="card-footer border-top pagination-wrap text-center">
            {$DATA.generate_page}
        </div>
        {elseif $DATA.data_number[$type] > $PER_PAGE}
        <div class="card-footer border-top text-center">
            <a class="btn btn-sm btn-primary" href="{$DATA.link_types[$type]}#id_{$type}">{$LANG->getGlobal('view_more')}</a>
        </div>
        {/if}
    </div>
    {/foreach}
    {/if}
</div>
