{* Template: user_waitting.tpl
 * Giao diện admin_future cho khu vực Tài khoản đợi kích hoạt
 * Module: users - NukeViet 5.0
 *}

{if $VIEW == 'list'}
{* Xem danh sách *}
<div class="mb-3 d-flex flex-wrap gap-2 align-items-center justify-content-between">
    <form class="d-flex flex-wrap gap-2 align-items-center" action="{$FORM_ACTION}" method="post" role="search">
        <select class="form-select" style="width: auto;" name="method" id="f_method" aria-label="{$LANG->getModule('search_type')}">
            <option value="">---</option>
            {foreach from=$METHODS item=m}
            <option value="{$m.key}"{if $m.selected} selected{/if}>{$m.value}</option>
            {/foreach}
        </select>
        <input class="form-control" style="width: auto;" type="text" name="value" id="f_value" value="{$SEARCH_VALUE}" maxlength="64" autocomplete="off" />
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-search"></i> {$LANG->getGlobal('submit')}</button>
    </form>
    {if $NUM_ITEMS > 0}
    <a href="{$RESEND_URL}" class="btn btn-secondary"><i class="fa-solid fa-paper-plane"></i> {$LANG->getModule('userwait_resend_email')}</a>
    {/if}
</div>

{if $NUM_ITEMS > 0}
{if $REGISTER_ACTIVE_TIME > 0}
<div class="alert alert-info">{$LANG->getModule('warning')}</div>
{/if}

<div class="card">
    <div class="card-body">
        <div class="table-responsive-lg table-card pb-1">
            <table class="table table-striped align-middle table-sticky mb-0">
                <thead>
                    <tr>
                        <th class="text-nowrap" style="width: 5%;">
                            <a href="{$HEAD_TDS.userid.href}">{$HEAD_TDS.userid.title}</a>
                        </th>
                        <th class="text-nowrap" style="width: 15%;">
                            <a href="{$HEAD_TDS.username.href}">{$HEAD_TDS.username.title}</a>
                        </th>
                        <th class="text-nowrap" style="width: 20%;">
                            <a href="{$HEAD_TDS.full_name.href}">{$HEAD_TDS.full_name.title}</a>
                        </th>
                        <th class="text-nowrap" style="width: 25%;">
                            <a href="{$HEAD_TDS.email.href}">{$HEAD_TDS.email.title}</a>
                        </th>
                        <th class="text-nowrap" style="width: 15%;">
                            <a href="{$HEAD_TDS.regdate.href}">{$HEAD_TDS.regdate.title}</a>
                        </th>
                        <th class="text-nowrap text-center" style="width: 20%;">{$LANG->getModule('funcs')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$USERS_LIST item=u}
                    <tr>
                        <td>{$u.userid}</td>
                        <td>{$u.username}</td>
                        <td>{$u.full_name}</td>
                        <td><a href="mailto:{$u.email}">{$u.email}</a></td>
                        <td class="text-nowrap">{$u.regdate}</td>
                        <td class="text-center text-nowrap">
                            <a href="{$u.activate_url}" class="btn btn-secondary btn-sm">
                                <i class="fa-solid fa-check"></i> {$LANG->getModule('censorship')}
                            </a>
                            <button type="button" class="btn btn-danger btn-sm btn-del-waiting"
                                data-userid="{$u.userid}"
                                data-checkss="{$u.checkss}"
                                data-icon="fa-trash"
                                aria-label="{$LANG->getGlobal('delete')}">
                                <i class="fa-solid fa-trash" data-icon="fa-trash"></i> {$LANG->getGlobal('delete')}
                            </button>
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
    {if $GENERATE_PAGE}
    <div class="card-footer border-top">
        <div class="d-flex flex-wrap justify-content-between align-items-center">
            <div class="d-flex flex-wrap flex-sm-nowrap align-items-center"></div>
            <div class="pagination-wrap">{$GENERATE_PAGE}</div>
        </div>
    </div>
    {/if}
</div>
{else}
<div class="alert alert-info">{$LANG->getModule('member_wating')}: 0</div>
{/if}

{elseif $VIEW == 'detail'}
{* Xem chi tiết và kích hoạt tài khoản *}
<link type="text/css" href="{$smarty.const.ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet">
<script type="text/javascript" src="{$smarty.const.ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{$smarty.const.ASSETS_LANG_STATIC_URL}/js/language/jquery.ui.datepicker-{$smarty.const.NV_LANG_INTERFACE}.js"></script>
<form class="ajax-submit" id="user_details_form" action="{$FORM_ACTION}" method="post" novalidate>
    <div class="card">
        <div class="card-body">
            {* Username *}
            <div class="row mb-3">
                <label for="username_field" class="col-sm-3 col-form-label text-sm-end">{$LANG->getGlobal('username')} <span class="text-danger">(*)</span></label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input type="text"
                        class="form-control"
                        id="username_field"
                        name="username"
                        value="{$DATA.username}"
                        maxlength="{$NV_UNICKMAX}"
                        autocomplete="username"
                        required>
                    <div class="invalid-feedback"></div>
                </div>
            </div>

            {* Email *}
            <div class="row mb-3">
                <label for="email_field" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('email')} <span class="text-danger">(*)</span></label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input type="email"
                        class="form-control"
                        id="email_field"
                        name="email"
                        value="{$DATA.email}"
                        autocomplete="email"
                        required>
                    <div class="invalid-feedback"></div>
                </div>
            </div>

            {* Mật khẩu *}
            <div class="row mb-3">
                <label for="password1" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('password')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <div class="input-group">
                        <input type="password"
                            class="form-control btn-eye-added"
                            id="password1"
                            name="password"
                            value=""
                            maxlength="{$NV_UPASSMAX}"
                            autocomplete="new-password">
                        <button class="btn btn-outline-secondary btn-eye" type="button" data-field="#password1" aria-label="{$LANG->getModule('show_password')}"><i class="fa-solid fa-eye"></i></button>
                        <button class="btn btn-outline-secondary" type="button" data-toggle="genpass" data-field1="#password1" data-field2="#password2" aria-label="{$LANG->getModule('random_password')}"><i class="fa-solid fa-rotate" data-icon="fa-rotate"></i></button>
                    </div>
                    <div class="form-text">{$LANG->getModule('leave_blank_note')}</div>
                    <div class="invalid-feedback"></div>
                </div>
            </div>

            {* Nhập lại mật khẩu *}
            <div class="row mb-3">
                <label for="password2" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('repassword')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <div class="input-group">
                        <input type="password"
                            class="form-control btn-eye-added"
                            id="password2"
                            name="re_password"
                            value=""
                            autocomplete="new-password">
                        <button class="btn btn-outline-secondary btn-eye" type="button" data-field="#password2" aria-label="{$LANG->getModule('show_password')}"><i class="fa-solid fa-eye"></i></button>
                    </div>
                    <div class="form-text">{$LANG->getModule('leave_blank_note')}</div>
                </div>
            </div>

            {* Yêu cầu thay đổi mật khẩu *}
            <div class="row mb-3">
                <label for="pass_reset_request" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('pass_reset_request')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <select class="form-select" id="pass_reset_request" name="pass_reset_request">
                        {foreach from=$PASS_RESET_OPTIONS item=opt}
                        <option value="{$opt.num}"{if $opt.selected} selected{/if}>{$opt.title}</option>
                        {/foreach}
                    </select>
                </div>
            </div>

            {* Yêu cầu thay đổi email *}
            <div class="row mb-3">
                <label for="email_reset_request" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('email_reset_request')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <select class="form-select" id="email_reset_request" name="email_reset_request">
                        {foreach from=$EMAIL_RESET_OPTIONS item=opt}
                        <option value="{$opt.num}"{if $opt.selected} selected{/if}>{$opt.title}</option>
                        {/foreach}
                    </select>
                </div>
            </div>

            {* Các trường hệ thống *}
            {if $HAVE_NAME_FIELD}
                {if $NAME_SHOW == 0}
                    {* Thứ tự: Họ trước, Tên sau *}
                    {if isset($SYSTEM_FIELDS.last_name)}
                    {assign var="sf" value=$SYSTEM_FIELDS.last_name}
                    <div class="row mb-3">
                        <label for="last_name_field" class="col-sm-3 col-form-label text-sm-end">
                            {$sf.title}{if $sf.required} <span class="text-danger">(*)</span>{/if}
                        </label>
                        <div class="col-sm-8 col-lg-6 col-xxl-5">
                            <input type="text" class="form-control" id="last_name_field" name="last_name" value="{$sf.value}" autocomplete="family-name">
                            {if $sf.required}<div class="invalid-feedback"></div>{/if}
                            {if $sf.description}<div class="form-text">{$sf.description}</div>{/if}
                        </div>
                    </div>
                    {/if}
                    {if isset($SYSTEM_FIELDS.first_name)}
                    {assign var="sf" value=$SYSTEM_FIELDS.first_name}
                    <div class="row mb-3">
                        <label for="first_name_field" class="col-sm-3 col-form-label text-sm-end">
                            {$sf.title}{if $sf.required} <span class="text-danger">(*)</span>{/if}
                        </label>
                        <div class="col-sm-8 col-lg-6 col-xxl-5">
                            <input type="text" class="form-control" id="first_name_field" name="first_name" value="{$sf.value}" autocomplete="given-name">
                            {if $sf.required}<div class="invalid-feedback"></div>{/if}
                            {if $sf.description}<div class="form-text">{$sf.description}</div>{/if}
                        </div>
                    </div>
                    {/if}
                {else}
                    {* Thứ tự: Tên trước, Họ sau *}
                    {if isset($SYSTEM_FIELDS.first_name)}
                    {assign var="sf" value=$SYSTEM_FIELDS.first_name}
                    <div class="row mb-3">
                        <label for="first_name_field" class="col-sm-3 col-form-label text-sm-end">
                            {$sf.title}{if $sf.required} <span class="text-danger">(*)</span>{/if}
                        </label>
                        <div class="col-sm-8 col-lg-6 col-xxl-5">
                            <input type="text" class="form-control" id="first_name_field" name="first_name" value="{$sf.value}" autocomplete="given-name">
                            {if $sf.required}<div class="invalid-feedback"></div>{/if}
                            {if $sf.description}<div class="form-text">{$sf.description}</div>{/if}
                        </div>
                    </div>
                    {/if}
                    {if isset($SYSTEM_FIELDS.last_name)}
                    {assign var="sf" value=$SYSTEM_FIELDS.last_name}
                    <div class="row mb-3">
                        <label for="last_name_field" class="col-sm-3 col-form-label text-sm-end">
                            {$sf.title}{if $sf.required} <span class="text-danger">(*)</span>{/if}
                        </label>
                        <div class="col-sm-8 col-lg-6 col-xxl-5">
                            <input type="text" class="form-control" id="last_name_field" name="last_name" value="{$sf.value}" autocomplete="family-name">
                            {if $sf.required}<div class="invalid-feedback"></div>{/if}
                            {if $sf.description}<div class="form-text">{$sf.description}</div>{/if}
                        </div>
                    </div>
                    {/if}
                {/if}
            {/if}

            {* Giới tính *}
            {if isset($SYSTEM_FIELDS.gender)}
            {assign var="sf" value=$SYSTEM_FIELDS.gender}
            <div class="row mb-3">
                <label for="gender_field" class="col-sm-3 col-form-label text-sm-end">
                    {$sf.title}{if $sf.required} <span class="text-danger">(*)</span>{/if}
                </label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <select class="form-select" id="gender_field" name="gender" autocomplete="sex">
                        {foreach from=$sf.gender_options item=g}
                        <option value="{$g.key}"{if $g.selected} selected{/if}>{$g.title}</option>
                        {/foreach}
                    </select>
                    {if $sf.required}<div class="invalid-feedback"></div>{/if}
                    {if $sf.description}<div class="form-text">{$sf.description}</div>{/if}
                </div>
            </div>
            {/if}

            {* Ngày sinh *}
            {if isset($SYSTEM_FIELDS.birthday)}
            {assign var="sf" value=$SYSTEM_FIELDS.birthday}
            <div class="row mb-3">
                <label for="birthday_field" class="col-sm-3 col-form-label text-sm-end">
                    {$sf.title}{if $sf.required} <span class="text-danger">(*)</span>{/if}
                </label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <div class="input-group" style="max-width: 180px;">
                        <input type="text" class="form-control datepicker" id="birthday_field" name="birthday" value="{$sf.value}" maxlength="10">
                        <button type="button" class="btn btn-outline-secondary" data-toggle="focusDate" aria-label="{$sf.title}"><i class="fa-solid fa-calendar-days"></i></button>
                    </div>
                    {if $sf.required}<div class="invalid-feedback"></div>{/if}
                    {if $sf.description}<div class="form-text">{$sf.description}</div>{/if}
                </div>
            </div>
            {/if}

            {* Chữ ký *}
            {if isset($SYSTEM_FIELDS.sig)}
            {assign var="sf" value=$SYSTEM_FIELDS.sig}
            <div class="row mb-3">
                <div class="col-sm-3 col-form-label text-sm-end">
                    {$sf.title}{if $sf.required} <span class="text-danger">(*)</span>{/if}
                </div>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <textarea class="form-control" name="sig" rows="4" autocomplete="off">{$sf.value}</textarea>
                    {if $sf.required}<div class="invalid-feedback"></div>{/if}
                    {if $sf.description}<div class="form-text">{$sf.description}</div>{/if}
                </div>
            </div>
            {/if}

            {* Câu hỏi bảo mật *}
            {if isset($SYSTEM_FIELDS.question)}
            {assign var="sf" value=$SYSTEM_FIELDS.question}
            <div class="row mb-3">
                <label for="question_field" class="col-sm-3 col-form-label text-sm-end">
                    {$sf.title}{if $sf.required} <span class="text-danger">(*)</span>{/if}
                </label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <div class="input-group">
                        <input type="text" class="form-control" id="question_field" name="question" value="" autocomplete="off">
                        {if $sf.questions}
                        <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" aria-label="{$sf.title}"></button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            {foreach from=$sf.questions item=q}
                            <li><a href="#" class="dropdown-item question">{$q}</a></li>
                            {/foreach}
                        </ul>
                        {/if}
                    </div>
                    {if $sf.required}<div class="invalid-feedback"></div>{/if}
                    <div class="form-text">{$LANG->getModule('leave_blank_note')}</div>
                    {if $sf.description}<div class="form-text">{$sf.description}</div>{/if}
                </div>
            </div>
            {/if}

            {* Câu trả lời bảo mật *}
            {if isset($SYSTEM_FIELDS.answer)}
            {assign var="sf" value=$SYSTEM_FIELDS.answer}
            <div class="row mb-3">
                <label for="answer_field" class="col-sm-3 col-form-label text-sm-end">
                    {$sf.title}{if $sf.required} <span class="text-danger">(*)</span>{/if}
                </label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input type="text" class="form-control" id="answer_field" name="answer" value="" autocomplete="off">
                    {if $sf.required}<div class="invalid-feedback"></div>{/if}
                    <div class="form-text">{$LANG->getModule('leave_blank_note')}</div>
                    {if $sf.description}<div class="form-text">{$sf.description}</div>{/if}
                </div>
            </div>
            {/if}

            {* Hình đại diện *}
            <div class="row mb-3">
                <label for="avatar" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('avatar')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <div class="input-group">
                        <input type="text" class="form-control" id="avatar" name="photo" value="" readonly autocomplete="off">
                        <button class="btn btn-outline-secondary" type="button"
                            data-toggle="changeAvatar"
                            data-admin="1"
                            data-url="{$smarty.const.NV_BASE_SITEURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=avatar/opener"
                            data-action="value"
                            data-target="#avatar"
                            data-title="{$LANG->getModule('avatar')}"
                            aria-label="{$LANG->getModule('avatar')}"
                            title="{$LANG->getModule('avatar')}">
                            <i class="fa-solid fa-folder-open"></i>
                        </button>
                    </div>
                </div>
            </div>

            {* Hiển thị email *}
            <div class="row mb-3">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                    <div class="form-check form-switch mt-1">
                        <input class="form-check-input" type="checkbox" id="view_mail_field" name="view_mail" value="1">
                        <label class="form-check-label" for="view_mail_field">{$LANG->getModule('show_email')}</label>
                    </div>
                </div>
            </div>

            {* Là người dùng chính thức *}
            <div class="row mb-3">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                    <div class="form-check form-switch mt-1">
                        <input class="form-check-input" type="checkbox" id="is_official_field" name="is_official" value="1" checked>
                        <label class="form-check-label small" for="is_official_field">{$LANG->getModule('is_official')}</label>
                    </div>
                    <div class="form-text">{$LANG->getModule('is_official_note')}</div>
                </div>
            </div>

            {* Danh sách nhóm - ẩn khi is_official = false *}
            {if $GROUP_EXISTS}
            <div class="row mb-3" id="ctn-list-groups">
                <div class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('in_group')}</div>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    {foreach from=$GROUPS_FOR_TPL item=grp}
                    <div class="d-flex align-items-center gap-3 mb-1">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="group_{$grp.id}" name="group[]" value="{$grp.id}">
                            <label class="form-check-label" for="group_{$grp.id}" style="min-width: 180px;">{$grp.title}</label>
                        </div>
                        <div class="form-check group_default">
                            <input class="form-check-input" type="radio" id="group_default_{$grp.id}" name="group_default" value="{$grp.id}">
                            <label class="form-check-label" for="group_default_{$grp.id}">{$LANG->getModule('in_group_default')}</label>
                        </div>
                    </div>
                    {/foreach}
                    <div class="mt-1" id="cleargroupdefault">
                        <a href="#" data-toggle="cleargdefault" class="btn btn-secondary btn-sm">
                            <i class="fa-solid fa-circle-xmark"></i> {$LANG->getModule('clear_group_default')}
                        </a>
                    </div>
                </div>
            </div>
            {/if}

            {* Email không cần xác minh *}
            <div class="row mb-3">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                    <div class="form-check form-switch mt-1">
                        <input class="form-check-input" type="checkbox" id="is_email_verified_field" name="is_email_verified" value="1" checked>
                        <label class="form-check-label small" for="is_email_verified_field">{$LANG->getModule('is_email_verified')}</label>
                    </div>
                    <div class="form-text">{$LANG->getModule('is_email_verified1')}</div>
                </div>
            </div>

            {* Các trường tùy biến *}
            {if $HAVE_CUSTOM_FIELDS}
            <hr>
            <h6 class="mb-3"><i class="fa-solid fa-list"></i> {$LANG->getModule('fields')}</h6>
            {foreach from=$CUSTOM_FIELDS item=cf}
            <div class="row mb-3">
                {if $cf.field_type != 'editor' && $cf.field_type != 'file' && $cf.field_type != 'radio' && $cf.field_type != 'checkbox'}
                <label for="cf_{$cf.field}" class="col-sm-3 col-form-label text-sm-end">
                    {$cf.title}{if $cf.required} <span class="text-danger">(*)</span>{/if}
                </label>
                {else}
                <div class="col-sm-3 col-form-label text-sm-end">
                    {$cf.title}{if $cf.required} <span class="text-danger">(*)</span>{/if}
                </div>
                {/if}
                <div class="col-sm-8 col-lg-6{if $cf.field_type == 'editor'} col-xxl-11{else} col-xxl-5{/if}">
                    {if $cf.field_type_render == 'textbox' || $cf.field_type == 'number'}
                    <input class="form-control" type="{if $cf.field_type == 'number'}number{else}text{/if}" id="cf_{$cf.field}" name="custom_fields[{$cf.field}]" value="{$cf.value}" autocomplete="off">

                    {elseif $cf.field_type_render == 'date'}
                    <div class="input-group" style="max-width: 180px;">
                        <input class="form-control datepicker" type="text" id="cf_{$cf.field}" name="custom_fields[{$cf.field}]" value="{$cf.value}" autocomplete="off">
                        <button type="button" class="btn btn-outline-secondary" data-toggle="focusDate" aria-label="{$cf.title}"><i class="fa-solid fa-calendar-days"></i></button>
                    </div>

                    {elseif $cf.field_type_render == 'textarea'}
                    <textarea class="form-control" rows="5" id="cf_{$cf.field}" name="custom_fields[{$cf.field}]" autocomplete="off">{$cf.value}</textarea>

                    {elseif $cf.field_type_render == 'editor'}
                    {$cf.editor_html}

                    {elseif $cf.field_type == 'select'}
                    <select class="form-select" id="cf_{$cf.field}" name="custom_fields[{$cf.field}]">
                        {foreach from=$cf.choices_prepared item=ch}
                        <option value="{$ch.key}"{if $ch.selected} selected{/if}>{$ch.value}</option>
                        {/foreach}
                    </select>

                    {elseif $cf.field_type == 'radio'}
                    {foreach from=$cf.choices_prepared item=ch}
                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="cf_{$ch.id}" name="custom_fields[{$cf.field}]" value="{$ch.key}"{if $ch.selected} checked{/if}>
                        <label class="form-check-label" for="cf_{$ch.id}">{$ch.value}</label>
                    </div>
                    {/foreach}

                    {elseif $cf.field_type == 'checkbox'}
                    {foreach from=$cf.choices_prepared item=ch}
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="cf_{$ch.id}" name="custom_fields[{$cf.field}][]" value="{$ch.key}"{if $ch.selected} checked{/if}>
                        <label class="form-check-label" for="cf_{$ch.id}">{$ch.value}</label>
                    </div>
                    {/foreach}

                    {elseif $cf.field_type == 'multiselect'}
                    <select class="form-select" id="cf_{$cf.field}" name="custom_fields[{$cf.field}][]" multiple>
                        {foreach from=$cf.choices_prepared item=ch}
                        <option value="{$ch.key}"{if $ch.selected} selected{/if}>{$ch.value}</option>
                        {/foreach}
                    </select>

                    {elseif $cf.field_type == 'file'}
                    <div class="filelist" data-field="{$cf.field}" data-oclass="{$cf.class}" data-maxnum="{$cf.filemaxnum}">
                        <ul class="list-unstyled items mb-1">
                            {foreach from=$cf.file_items item=fi}
                            <li class="d-flex align-items-center gap-1 mb-1">
                                <input type="checkbox" class="form-check-input {$cf.class}" name="custom_fields[{$cf.field}][]" value="{$fi.key}" checked>
                                <button type="button" class="btn btn-success btn-sm btn-file type-{$fi.type}" data-url="{$fi.url}">{$fi.value}</button>
                                <button type="button" class="btn btn-link btn-sm" data-toggle="thisfile_del">{$LANG->getGlobal('delete')}</button>
                            </li>
                            {/foreach}
                        </ul>
                        <div>
                            <button type="button" class="btn btn-info btn-sm" data-toggle="addfilebtn" data-modal="uploadfile_{$cf.field}"{if $cf.addfile_disabled} style="display:none"{/if}>
                                <i class="fa-solid fa-upload"></i> {$LANG->getModule('addfile')}
                            </button>
                        </div>
                        <div class="modal fade uploadfile" tabindex="-1" id="uploadfile_{$cf.field}"
                            data-url="{$cf.url_module}"
                            data-field="{$cf.field}"
                            data-csrf="{$cf.csrf}"
                            data-accept="{$cf.fileaccept}"
                            data-maxsize="{$cf.filemaxsize}"
                            data-ext-error="{$LANG->getModule('addfile_ext_error')}"
                            data-size-error="{$LANG->getModule('addfile_size_error')}"
                            data-size-error2="{$LANG->getModule('addfile_size_error2')}"
                            data-delete="{$LANG->getGlobal('delete')}">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">{$LANG->getModule('addfile')}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="fileinput" style="display:flex;justify-content:center;margin-top:20px;margin-bottom:20px"></p>
                                        <ul class="list-unstyled small">
                                            <li>- {$LANG->getModule('accepted_extensions')}: {$cf.fileaccept}</li>
                                            <li>- {$LANG->getModule('field_file_max_size')}: {$cf.filemaxsize_format}</li>
                                            {if $cf.widthlimit}<li>- {$cf.widthlimit}</li>{/if}
                                            {if $cf.heightlimit}<li>- {$cf.heightlimit}</li>{/if}
                                        </ul>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{$LANG->getGlobal('close')}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {/if}

                    {if $cf.required}<div class="invalid-feedback"></div>{/if}
                    {if $cf.description}<div class="form-text">{$cf.description}</div>{/if}
                </div>
            </div>
            {/foreach}
            {/if}

            {* Nút submit và xóa *}
            <div class="row mb-3">
                <div class="col-sm-8 col-lg-6 offset-sm-3">
                    <input type="hidden" name="checkss" value="{$CHECKSS}">
                    <button class="btn btn-primary" type="submit">
                        <i class="fa-solid fa-check"></i> {$LANG->getModule('awaiting_active')}
                    </button>
                    <button class="btn btn-danger btn-del-waiting ms-2" type="button"
                        data-userid="{$DATA.userid}"
                        data-checkss="{$CHECKSS}"
                        data-icon="fa-trash">
                        <i class="fa-solid fa-trash" data-icon="fa-trash"></i> {$LANG->getGlobal('delete')}
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
{/if}
