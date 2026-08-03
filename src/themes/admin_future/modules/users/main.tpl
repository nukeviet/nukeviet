{* Template: main.tpl
 * Giao diện admin_future cho khu vực Danh sách tài khoản
 * Module: users - NukeViet 5.0
 *}

{if $IS_FORUM}
<div class="alert alert-warning">{$LANG->getModule('modforum')}</div>
{/if}

{* Form tìm kiếm và lọc *}
<link type="text/css" href="{$smarty.const.ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet">
<script type="text/javascript" src="{$smarty.const.ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{$smarty.const.ASSETS_LANG_STATIC_URL}/js/language/jquery.ui.datepicker-{$smarty.const.NV_LANG_INTERFACE}.js"></script>

<div class="card mb-3">
    <div class="card-body">
        <form action="{$smarty.const.NV_BASE_ADMINURL}index.php" method="get">
            <input type="hidden" name="{$smarty.const.NV_LANG_VARIABLE}" value="{$smarty.const.NV_LANG_DATA}">
            <input type="hidden" name="{$smarty.const.NV_NAME_VARIABLE}" value="{$MODULE_NAME}">
            {* Dòng 1: từ khóa + loại tìm + submit *}
            <div class="row g-3 flex-lg-nowrap">
                <div class="col-md-6 flex-lg-fill">
                    <label for="f_value" class="form-label">
                        {$LANG->getModule('search_key')}
                        <i class="fa-solid fa-circle-info text-secondary ms-1" data-bs-toggle="tooltip" data-bs-title="{$LANG->getModule('search_note')}" data-bs-trigger="hover"></i>
                    </label>
                    <input class="form-control" type="text" name="value" id="f_value" value="{$SEARCH_VALUE}" maxlength="64" placeholder="{$LANG->getModule('search_key')}" autocomplete="off">
                </div>
                <div class="col-md-6 flex-lg-fill">
                    <label for="f_method" class="form-label">{$LANG->getModule('search_type')}</label>
                    <select class="form-select" name="method" id="f_method">
                        <option value="">---{$LANG->getModule('search_type')}---</option>
                        {foreach from=$METHODS item=m}
                        <option value="{$m.key}"{if $m.selected} selected{/if}>{$m.value}</option>
                        {/foreach}
                    </select>
                </div>
                <div class="flex-grow-0 flex-shrink-1 w-auto">
                    <div class="form-label d-none d-md-block">&nbsp;</div>
                    <div class="d-flex align-items-center gap-2">
                        <button type="submit" class="btn btn-primary text-nowrap">
                            <i class="fa-solid fa-magnifying-glass"></i> {$LANG->getModule('submit')}
                        </button>
                        <span data-bs-toggle="tooltip"
                              data-bs-title="{if $ADV_EXPANDED}{$LANG->getGlobal('collapse')}{else}{$LANG->getGlobal('expand')}{/if}"
                              data-bs-trigger="hover">
                            <button type="button" class="btn btn-secondary text-nowrap"
                                    data-bs-toggle="collapse" data-bs-target="#search-adv"
                                    aria-expanded="{if $ADV_EXPANDED}true{else}false{/if}"
                                    aria-controls="search-adv"
                                    aria-label="{if $ADV_EXPANDED}{$LANG->getGlobal('collapse')}{else}{$LANG->getGlobal('expand')}{/if}">
                                <i class="fa-solid {if $ADV_EXPANDED}fa-compress{else}fa-expand{/if}"></i>
                            </button>
                        </span>
                    </div>
                </div>
            </div>
            {* Dòng 2: bộ lọc nâng cao (collapsible) *}
            <div class="collapse{if $ADV_EXPANDED} show{/if}" id="search-adv"
                 data-label-expand="{$LANG->getGlobal('expand')}"
                 data-label-collapse="{$LANG->getGlobal('collapse')}">
                <div class="row g-3 flex-xl-nowrap pt-3">
                    <div class="col-6 col-lg-4 col-xl-auto flex-xl-fill">
                        <label for="f_usactive" class="form-label text-truncate">{$LANG->getModule('usactive')}</label>
                        <select class="form-select" name="usactive" id="f_usactive">
                            {foreach from=$USACTIVE_OPTIONS item=ua}
                            <option value="{$ua.key}"{if $ua.selected} selected{/if}>{$ua.value}</option>
                            {/foreach}
                        </select>
                    </div>
                    <div class="col-6 col-lg-4 col-xl-auto flex-xl-fill">
                        <label for="f_group" class="form-label text-truncate">{$LANG->getGlobal('in_groups')}</label>
                        <select class="form-select" name="group" id="f_group">
                            {foreach from=$GROUPS_LIST item=grp}
                            <option value="{$grp.group_id}"{if $grp.selected} selected{/if}>{$grp.title}</option>
                            {/foreach}
                        </select>
                    </div>
                    <div class="col-6 col-lg-4 col-xl-auto flex-xl-fill">
                        <label for="f_active2step" class="form-label text-truncate">{$LANG->getModule('active2step_status')}</label>
                        <select class="form-select" name="active2step" id="f_active2step">
                            {foreach from=$ACTIVE2STEPS_OPTIONS item=a2s}
                            <option value="{$a2s.val}"{if $a2s.selected} selected{/if}>{$a2s.name}</option>
                            {/foreach}
                        </select>
                    </div>
                    <div class="col-6 col-lg-6 col-xl-auto flex-xl-fill">
                        <label for="reg_time_from" class="form-label text-truncate">{$LANG->getModule('reg_time_from')}</label>
                        <div class="input-group flex-nowrap">
                            <input type="text" class="form-control datepicker-search" name="reg_from" id="reg_time_from" value="{$REG_FROM}" autocomplete="off">
                            <button class="btn btn-secondary" type="button" data-toggle="focusDate" aria-label="{$LANG->getModule('reg_time_from')}">
                                <i class="fa-regular fa-calendar"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-6 col-lg-6 col-xl-auto flex-xl-fill">
                        <label for="reg_time_to" class="form-label text-truncate">{$LANG->getModule('reg_time_to')}</label>
                        <div class="input-group flex-nowrap">
                            <input type="text" class="form-control datepicker-search" name="reg_to" id="reg_time_to" value="{$REG_TO}" autocomplete="off">
                            <button class="btn btn-secondary" type="button" data-toggle="focusDate" aria-label="{$LANG->getModule('reg_time_to')}">
                                <i class="fa-regular fa-calendar"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

{* Bảng danh sách tài khoản *}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            <i class="fa-solid fa-users"></i> {$TABLE_CAPTION}
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive-lg table-card pb-1"
             data-checkss="{$CHECKSS}">
            <table class="table table-striped align-middle table-sticky mb-0">
                <thead>
                    <tr>
                        {if $HAS_CHOOSE}
                        <th class="text-nowrap" style="width: 1%;">
                            <input type="checkbox" class="form-check-input" id="check_all" aria-label="Select all">
                        </th>
                        {/if}
                        <th class="text-nowrap text-center" style="width: 4%;">
                            <a href="{$HEAD.userid.href}">{$HEAD.userid.title}</a>
                        </th>
                        <th class="text-nowrap" style="width: 28%;">
                            <a href="{$HEAD.username.href}">{$HEAD.username.title}</a> /
                            <a href="{$HEAD.full_name.href}">{$HEAD.full_name.title}</a>
                        </th>
                        <th class="text-nowrap" style="width: 22%;">
                            <a href="{$HEAD.email.href}">{$HEAD.email.title}</a>
                        </th>
                        <th class="text-nowrap" style="width: 15%;">
                            <a href="{$HEAD.regdate.href}">{$HEAD.regdate.title}</a>
                        </th>
                        <th class="text-nowrap text-center" style="width: 10%;">{$LANG->getModule('memberlist_active')}</th>
                        <th class="text-nowrap text-center" style="width:{if $HAS_CHOOSE} 20%{else} 21%{/if};">{$LANG->getModule('funcs')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$USERS_LIST item=u}
                    <tr>
                        {if $HAS_CHOOSE}
                        <td>
                            {if $u.setactive or $u.is_delete}
                            <input type="checkbox" class="form-check-input idcheck" value="{$u.userid}" aria-label="{$u.username}">
                            {/if}
                        </td>
                        {/if}
                        <td class="text-center text-nowrap">{$u.userid}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                {if $u.avata}
                                <img src="{$u.avata}" alt="{$u.username|escape}" width="32" height="32" class="user-avatar flex-shrink-0 rounded-circle object-fit-cover">
                                {else}
                                <span class="avatar-letters avatar-letters-sm user-avatar" style="background-color:{$u.avatar_color}" aria-hidden="true">{$u.avatar_letters}</span>
                                {/if}
                                <div class="flex-grow-1">
                                    {if $u.is_admin}
                                    <span class="badge bg-secondary me-1" title="{$u.level}">{$u.level}</span>
                                    {/if}
                                    {if $u.is_pending_deletion}
                                    <span class="d-inline-flex align-items-center justify-content-center text-danger me-1" data-bs-toggle="tooltip" title="{$u.delete_at_display}">
                                        <i class="fa-solid fa-user-slash"></i>
                                    </span>
                                    {/if}
                                    {if $VIEW_USER_ALLOWED}
                                    <a href="#" data-toggle="view-user" data-link="{$u.link}">{$u.username|escape}</a>
                                    {else}
                                    {$u.username|escape}
                                    {/if}
                                    <div class="mt-1 text-muted small">{$u.full_name|escape}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <a href="mailto:{$u.email|escape}">{$u.email|escape}</a>
                            <div class="mt-1 text-muted small">{$u.info_verify}</div>
                        </td>
                        <td>
                            <span class="text-info">{$u.regdate}</span>
                            <div class="mt-1 text-muted small">{$u.active_obj}</div>
                        </td>
                        <td class="text-center">
                            <div class="form-check form-switch d-flex justify-content-center">
                                <input class="form-check-input" type="checkbox" id="active_{$u.userid}" value="{$u.userid}"
                                    {if $u.active} checked{/if}
                                    {if $u.setactive} data-toggle="setactive" data-userid="{$u.userid}" data-icon="fa-spinner"{else} disabled{/if}
                                    aria-label="{$u.username}">
                            </div>
                        </td>
                        <td class="text-center text-nowrap">
                            {if not $IS_FORUM}
                            <div class="btn-group">
                                {if $u.is_edit}
                                <a class="btn btn-sm btn-secondary"
                                   href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=edit&amp;userid={$u.userid}"
                                   aria-label="{$LANG->getModule('memberlist_edit')}">
                                    <i class="fa-solid fa-pencil"></i>
                                </a>
                                {/if}
                                {if $u.is_set_official}
                                <button type="button" class="btn btn-sm btn-warning"
                                        data-toggle="set-official"
                                        data-userid="{$u.userid}"
                                        data-icon="fa-user-check"
                                        aria-label="{$LANG->getModule('set_official_note')}"
                                        title="{$LANG->getModule('set_official_note')}">
                                    <i class="fa-solid fa-user-check" data-icon="fa-user-check"></i>
                                </button>
                                {/if}
                                {if $u.is_delete}
                                <button type="button" class="btn btn-sm btn-danger"
                                        data-toggle="row-del"
                                        data-userid="{$u.userid}"
                                        data-icon="fa-trash"
                                        aria-label="{$LANG->getGlobal('delete')}"
                                        data-msgconfirm="{$LANG->getModule('delConfirm')}">
                                    <i class="fa-solid fa-trash" data-icon="fa-trash"></i>
                                </button>
                                {/if}
                                {if $u.is_edit}
                                <button type="button" class="btn btn-sm btn-secondary dropdown-toggle dropdown-toggle-split px-2" data-bs-toggle="dropdown" aria-expanded="false" aria-label="{$LANG->getModule('memberlist_edit')}"></button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=edit_oauth&amp;userid={$u.userid}">
                                            {$LANG->getModule('user_openid_mamager')}
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=edit_2step&amp;userid={$u.userid}">
                                            {$LANG->getModule('user_2step_mamager')}
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#"
                                            data-toggle="pass-reset-request"
                                            data-userid="{$u.userid}">
                                            {$LANG->getModule('pass_reset_request')}
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#"
                                            data-toggle="email-reset-request"
                                            data-userid="{$u.userid}">
                                            {$LANG->getModule('email_reset_request')}
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#"
                                            data-toggle="forced-relogin"
                                            data-userid="{$u.userid}">
                                            {$LANG->getModule('forcedrelogin')}
                                        </a>
                                    </li>
                                    {if $u.is_pending_deletion}
                                    <li>
                                        <a class="dropdown-item" href="#"
                                            data-toggle="cancel-deletion"
                                            data-userid="{$u.userid}">
                                            {$LANG->getModule('delacc_cancel_adm')}
                                        </a>
                                    </li>
                                    {/if}
                                </ul>
                                {/if}
                            </div>
                            {/if}
                        </td>
                    </tr>
                    {foreachelse}
                    <tr>
                        <td colspan="{if $HAS_CHOOSE}7{else}6{/if}" class="text-center text-muted py-4">
                            {$LANG->getModule('list_module_title')}: 0
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
    {if $HAS_CHOOSE or $PAGINATION or $CAN_EXPORT}
    <div class="card-footer border-top">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div class="d-flex flex-wrap flex-sm-nowrap align-items-center gap-2">
                {if $HAS_CHOOSE}
                <div class="input-group" style="width: fit-content;">
                    <select class="form-select" id="mainuseropt" style="width: fit-content;">
                        {foreach from=$ARRAY_ACTION key=action_key item=action_lang}
                        <option value="{$action_key}">{$action_lang}</option>
                        {/foreach}
                    </select>
                    <button type="button" class="btn btn-primary" id="mainusersaction"
                            data-msgnocheck="{$LANG->getModule('msgnocheck')}">
                        {$LANG->getModule('read_submit')}
                    </button>
                </div>
                {/if}
                {if $CAN_EXPORT}
                <button type="button" class="btn btn-secondary"
                        id="btn-export"
                        data-toggle="data-export"
                        data-note="{$LANG->getModule('export_note')}"
                        data-complete="{$LANG->getModule('export_complete')}">
                    <i class="fa-solid fa-file-export" data-icon="fa-file-export"></i> {$LANG->getModule('export')}
                </button>
                {/if}
            </div>
            {if $PAGINATION}
            <div class="pagination-wrap">{$PAGINATION}</div>
            {/if}
        </div>
    </div>
    {/if}
</div>

{* Modal yêu cầu thay đổi mật khẩu *}
<div id="pass-reset-modal" class="modal fade" tabindex="-1" aria-labelledby="passResetModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="passResetModalLabel">{$LANG->getModule('pass_reset_request')}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" class="userid" value="0">
                <p>{$LANG->getGlobal('username')}: <span class="username fw-bold"></span></p>
                <p id="currentpass-created-time-row">{$LANG->getModule('currentpass_created_time')}: <span class="currentpass-created-time"></span></p>
                <p>{$LANG->getModule('currentpass_request_status')}: <span class="currentpass-request-status"></span></p>
                <div class="d-flex flex-column gap-2">
                    <button type="button" class="btn btn-primary btn-sm btn-pass-reset-submit" data-type="1" data-icon="fa-paper-plane">
                        <i class="fa-solid fa-paper-plane" data-icon="fa-paper-plane"></i> {$LANG->getModule('pass_reset_request1_send')}
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm btn-pass-reset-submit" data-type="2" data-icon="fa-paper-plane">
                        <i class="fa-solid fa-paper-plane" data-icon="fa-paper-plane"></i> {$LANG->getModule('pass_reset_request2_send')}
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{$LANG->getGlobal('close')}</button>
            </div>
        </div>
    </div>
</div>

{* Modal yêu cầu thay đổi email *}
<div id="email-reset-modal" class="modal fade" tabindex="-1" aria-labelledby="emailResetModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="emailResetModalLabel">{$LANG->getModule('email_reset_request')}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" class="userid" value="0">
                <p>{$LANG->getGlobal('username')}: <span class="username fw-bold"></span></p>
                <p>{$LANG->getModule('currentemail_created_time')}: <span class="currentemail-created-time"></span></p>
                <p>{$LANG->getModule('currentemail_request_status')}: <span class="currentemail-request-status"></span></p>
                <div class="d-flex flex-column gap-2">
                    <button type="button" class="btn btn-primary btn-sm btn-email-reset-submit" data-type="1" data-icon="fa-paper-plane">
                        <i class="fa-solid fa-paper-plane" data-icon="fa-paper-plane"></i> {$LANG->getModule('email_reset_request1_send')}
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm btn-email-reset-submit" data-type="2" data-icon="fa-paper-plane">
                        <i class="fa-solid fa-paper-plane" data-icon="fa-paper-plane"></i> {$LANG->getModule('email_reset_request2_send')}
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{$LANG->getGlobal('close')}</button>
            </div>
        </div>
    </div>
</div>
