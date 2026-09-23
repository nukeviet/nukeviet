{if $DATEPICKER}
<link rel="stylesheet" href="{$smarty.const.ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.css">
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.js"></script>
<script src="{$smarty.const.ASSETS_LANG_STATIC_URL}/js/language/jquery.ui.datepicker-{$smarty.const.NV_LANG_INTERFACE}.js"></script>
{/if}
{if $TABS.passkey}
<script src="{$smarty.const.NV_STATIC_URL}themes/{$TEMPLATE_JS}/js/users.passkey.js"></script>
{/if}
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
<h1 class="h3 mb-3">{$LANG->getModule('editinfo_pagetitle')}</h1>
<div class="row g-3 mb-3">
    <div class="col-md-4 col-lg-3">
        {* Màn hình nhỏ thu gọn danh sách tab thành nút bấm, màn hình lớn luôn hiển thị *}
        <button type="button" class="btn btn-outline-secondary w-100 d-flex d-md-none align-items-center justify-content-between gap-2" data-toggle="usersEditinfoNavToggle" data-bs-toggle="collapse" data-bs-target="#usersEditinfoNav" aria-expanded="false" aria-controls="usersEditinfoNav">
            <span class="text-truncate"><i class="fa-solid fa-bars"></i> <span data-area="usersEditinfoNavCurrent">{$ACTIVE_TITLE}</span></span>
            <i class="fa-solid fa-chevron-down small"></i>
        </button>
        <div class="collapse d-md-block mt-2 mt-md-0" id="usersEditinfoNav" data-area="usersEditinfoNav">
            {* Danh sách tab, mục chỉ là liên kết sang trang khác thì dùng thẻ a thường *}
            <div class="nav flex-column nav-pills gap-1" role="tablist" aria-orientation="vertical">
                <button type="button" class="nav-link text-start{if $ACTIVE == 'basic'} active{/if}" id="usersEditinfoTab-basic" data-bs-toggle="pill" data-bs-target="#usersEditinfo-basic" role="tab" aria-controls="usersEditinfo-basic" aria-selected="{if $ACTIVE == 'basic'}true{else}false{/if}" data-location="{$EDITINFO_FORM}/basic">
                    <i class="fa-solid fa-user fa-fw"></i> {$LANG->getModule('edit_basic')}
                </button>
                {if $TABS.avatar}
                <button type="button" class="nav-link text-start{if $ACTIVE == 'avatar'} active{/if}" id="usersEditinfoTab-avatar" data-bs-toggle="pill" data-bs-target="#usersEditinfo-avatar" role="tab" aria-controls="usersEditinfo-avatar" aria-selected="{if $ACTIVE == 'avatar'}true{else}false{/if}" data-location="{$EDITINFO_FORM}/avatar">
                    <i class="fa-solid fa-image fa-fw"></i> {$LANG->getModule('edit_avatar')}
                </button>
                {/if}
                {if $TABS.username}
                <button type="button" class="nav-link text-start{if $ACTIVE == 'username'} active{/if}" id="usersEditinfoTab-username" data-bs-toggle="pill" data-bs-target="#usersEditinfo-username" role="tab" aria-controls="usersEditinfo-username" aria-selected="{if $ACTIVE == 'username'}true{else}false{/if}" data-location="{$EDITINFO_FORM}/username">
                    <i class="fa-solid fa-user-pen fa-fw"></i> {$LANG->getModule('edit_login')}
                </button>
                {/if}
                {if $TABS.email}
                <button type="button" class="nav-link text-start{if $ACTIVE == 'email'} active{/if}" id="usersEditinfoTab-email" data-bs-toggle="pill" data-bs-target="#usersEditinfo-email" role="tab" aria-controls="usersEditinfo-email" aria-selected="{if $ACTIVE == 'email'}true{else}false{/if}" data-location="{$EDITINFO_FORM}/email">
                    <i class="fa-solid fa-envelope fa-fw"></i> {$LANG->getModule('edit_email')}
                </button>
                {/if}
                {if $TABS.password}
                <button type="button" class="nav-link text-start{if $ACTIVE == 'password'} active{/if}" id="usersEditinfoTab-password" data-bs-toggle="pill" data-bs-target="#usersEditinfo-password" role="tab" aria-controls="usersEditinfo-password" aria-selected="{if $ACTIVE == 'password'}true{else}false{/if}" data-location="{$EDITINFO_FORM}/password">
                    <i class="fa-solid fa-lock fa-fw"></i> {$LANG->getModule('edit_password')}
                </button>
                {/if}
                {if $TABS.passkey}
                <button type="button" class="nav-link text-start{if $ACTIVE == 'passkey'} active{/if}" id="usersEditinfoTab-passkey" data-bs-toggle="pill" data-bs-target="#usersEditinfo-passkey" role="tab" aria-controls="usersEditinfo-passkey" aria-selected="{if $ACTIVE == 'passkey'}true{else}false{/if}" data-location="{$EDITINFO_FORM}/passkey">
                    <i class="fa-solid fa-key fa-fw"></i> {$LANG->getModule('edit_passkey')}
                </button>
                {elseif $TABS.passkey_link}
                <a class="nav-link" href="{$DATA.confirm_pass_url}">
                    <i class="fa-solid fa-key fa-fw"></i> {$LANG->getModule('edit_passkey')}
                </a>
                {/if}
                {if $TABS.langinterface}
                <button type="button" class="nav-link text-start{if $ACTIVE == 'langinterface'} active{/if}" id="usersEditinfoTab-langinterface" data-bs-toggle="pill" data-bs-target="#usersEditinfo-langinterface" role="tab" aria-controls="usersEditinfo-langinterface" aria-selected="{if $ACTIVE == 'langinterface'}true{else}false{/if}" data-location="{$EDITINFO_FORM}/langinterface">
                    <i class="fa-solid fa-language fa-fw"></i> {$LANG->getGlobal('langinterface')}
                </button>
                {/if}
                {if $TABS['2step']}
                <a class="nav-link" href="{$URL_2STEP}">
                    <i class="fa-solid fa-shield-halved fa-fw"></i> {$LANG->getModule('2step_status')}
                </a>
                {/if}
                {if $TABS.question}
                <button type="button" class="nav-link text-start{if $ACTIVE == 'question'} active{/if}" id="usersEditinfoTab-question" data-bs-toggle="pill" data-bs-target="#usersEditinfo-question" role="tab" aria-controls="usersEditinfo-question" aria-selected="{if $ACTIVE == 'question'}true{else}false{/if}" data-location="{$EDITINFO_FORM}/question">
                    <i class="fa-solid fa-circle-question fa-fw"></i> {$LANG->getModule('edit_question')}
                </button>
                {/if}
                {if $TABS.openid}
                <button type="button" class="nav-link text-start{if $ACTIVE == 'openid'} active{/if}" id="usersEditinfoTab-openid" data-bs-toggle="pill" data-bs-target="#usersEditinfo-openid" role="tab" aria-controls="usersEditinfo-openid" aria-selected="{if $ACTIVE == 'openid'}true{else}false{/if}" data-location="{$EDITINFO_FORM}/openid">
                    <i class="fa-solid fa-link fa-fw"></i> {$LANG->getModule('openid_administrator')}
                </button>
                {/if}
                {if $TABS.group}
                <button type="button" class="nav-link text-start{if $ACTIVE == 'group'} active{/if}" id="usersEditinfoTab-group" data-bs-toggle="pill" data-bs-target="#usersEditinfo-group" role="tab" aria-controls="usersEditinfo-group" aria-selected="{if $ACTIVE == 'group'}true{else}false{/if}" data-location="{$EDITINFO_FORM}/group">
                    <i class="fa-solid fa-users fa-fw"></i> {$LANG->getModule('group')}
                </button>
                {/if}
                {if $TABS.others}
                <button type="button" class="nav-link text-start{if $ACTIVE == 'others'} active{/if}" id="usersEditinfoTab-others" data-bs-toggle="pill" data-bs-target="#usersEditinfo-others" role="tab" aria-controls="usersEditinfo-others" aria-selected="{if $ACTIVE == 'others'}true{else}false{/if}" data-location="{$EDITINFO_FORM}/others">
                    <i class="fa-solid fa-list fa-fw"></i> {$LANG->getModule('edit_others')}
                </button>
                {/if}
                {if $TABS.safemode}
                <button type="button" class="nav-link text-start{if $ACTIVE == 'safemode'} active{/if}" id="usersEditinfoTab-safemode" data-bs-toggle="pill" data-bs-target="#usersEditinfo-safemode" role="tab" aria-controls="usersEditinfo-safemode" aria-selected="{if $ACTIVE == 'safemode'}true{else}false{/if}" data-location="{$EDITINFO_FORM}/safemode">
                    <i class="fa-solid fa-shield fa-fw"></i> {$LANG->getModule('safe_mode')}
                </button>
                {/if}
                {if $TABS.securityprivacy}
                <a class="nav-link" href="{$URL_SECURITY_PRIVACY}">
                    <i class="fa-solid fa-user-shield fa-fw"></i> {$LANG->getModule('security_privacy')}
                </a>
                {/if}
            </div>
        </div>
    </div>
    <div class="col-md-8 col-lg-9">
        <div class="tab-content">
            {* Thông tin cơ bản *}
            <div class="tab-pane fade{if $ACTIVE == 'basic'} show active{/if}" id="usersEditinfo-basic" role="tabpanel" aria-labelledby="usersEditinfoTab-basic" tabindex="0">
                <div class="card">
                    <div class="card-body">
                        <h2 class="h5 border-bottom pb-3 mb-3">{$LANG->getModule('edit_basic')}</h2>
                        <form action="{$EDITINFO_FORM}/basic" method="post" data-toggle="ajax-form" data-form="usersEditinfo" data-precheck="nv_precheck_form" autocomplete="off" novalidate>
                            <div class="row g-3">
                                {* Họ và tên hiển thị theo cấu hình name_show *}
                                {if $GCONFIG.name_show == 1}
                                {assign var="nameKeys" value=['first_name', 'last_name']}
                                {else}
                                {assign var="nameKeys" value=['last_name', 'first_name']}
                                {/if}
                                {foreach from=$nameKeys item=nameKey}
                                {if isset($SYSTEM[$nameKey])}
                                {assign var="nameField" value=$SYSTEM[$nameKey]}
                                <div class="col-md-6">
                                    <label class="form-label" for="ei_{$nameKey}">{$nameField.title}{if $nameField.required} <span class="text-danger">*</span>{/if}</label>
                                    <input type="text" class="form-control {$nameField.class}" id="ei_{$nameKey}"
                                        placeholder="{$nameField.title}" value="{$nameField.value}" name="{$nameKey}"
                                        autocomplete="{if $nameKey == 'first_name'}given-name{else}family-name{/if}"
                                        minlength="{$nameField.min_length}" maxlength="{$nameField.max_length}"
                                        {if $nameField.required or $nameField.callfunc} data-valid data-error-type="feedback"{/if}
                                        {if not $nameField.required} data-allowed-empty="1"{/if}
                                        {if $nameField.callfunc} data-valid-callback="{$nameField.callfunc}"{/if}
                                        data-error-mess="{$nameField.errmess}"
                                    >
                                    <div class="invalid-feedback"></div>
                                    {if $nameField.description}<div class="form-text">{$nameField.description}</div>{/if}
                                </div>
                                {/if}
                                {/foreach}

                                {* Giới tính *}
                                {if isset($SYSTEM.gender)}
                                <div class="col-md-6">
                                    <label class="form-label d-block" for="ei_gender_{$SYSTEM.gender.genders[0].key}">{$SYSTEM.gender.title}{if $SYSTEM.gender.required} <span class="text-danger">*</span>{/if}</label>
                                    <div class="d-flex flex-wrap gap-2">
                                        {foreach from=$SYSTEM.gender.genders item=gender}
                                        <input class="btn-check" type="radio" name="gender" id="ei_gender_{$gender.key}" value="{$gender.key}"{if $gender.checked} checked{/if}{if $SYSTEM.gender.required} data-valid data-error-type="feedback" data-min="1" data-max="1" data-error-mess="{$SYSTEM.gender.title}"{/if}>
                                        <label class="btn btn-outline-primary" for="ei_gender_{$gender.key}">{$gender.title}</label>
                                        {/foreach}
                                        {* Thẻ báo lỗi phải nằm ngay sau label cuối để validator tìm thấy, w-100 để xuống dòng riêng *}
                                        <div class="invalid-feedback w-100"></div>
                                    </div>
                                    {if $SYSTEM.gender.description}<div class="form-text">{$SYSTEM.gender.description}</div>{/if}
                                </div>
                                {/if}

                                {* Ngày sinh *}
                                {if isset($SYSTEM.birthday)}
                                <div class="col-md-6">
                                    <label class="form-label" for="ei_birthday">{$SYSTEM.birthday.title}{if $SYSTEM.birthday.required} <span class="text-danger">*</span>{/if}</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-solid fa-calendar-days"></i></span>
                                        <input type="text" class="form-control {$SYSTEM.birthday.class}" id="ei_birthday"
                                            name="birthday" value="{$SYSTEM.birthday.value}" autocomplete="off"
                                            data-provide="datepicker" data-min-old="{$SYSTEM.birthday.min_old_user}"
                                            placeholder="{$SYSTEM.birthday.title}"
                                            {if $SYSTEM.birthday.required} data-valid data-error-type="feedback"{/if}
                                            data-error-mess="{$SYSTEM.birthday.errmess}"
                                        >
                                    </div>
                                    <div class="invalid-feedback"></div>
                                    {if $SYSTEM.birthday.description}<div class="form-text">{$SYSTEM.birthday.description}</div>{/if}
                                </div>
                                {/if}

                                {* Chữ ký *}
                                {if isset($SYSTEM.sig)}
                                <div class="col-12">
                                    <label class="form-label" for="ei_sig">{$SYSTEM.sig.title}{if $SYSTEM.sig.required} <span class="text-danger">*</span>{/if}</label>
                                    <textarea class="form-control {$SYSTEM.sig.class}" id="ei_sig" placeholder="{$SYSTEM.sig.title}" name="sig" rows="3" autocomplete="off"{if $SYSTEM.sig.required} data-valid data-error-type="feedback" data-error-mess="{$SYSTEM.sig.errmess}"{/if}>{$SYSTEM.sig.value}</textarea>
                                    <div class="invalid-feedback"></div>
                                    {if $SYSTEM.sig.description}<div class="form-text">{$SYSTEM.sig.description}</div>{/if}
                                </div>
                                {/if}

                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="view_mail" value="1" id="ei_view_mail"{if $VIEW_MAIL} checked{/if}>
                                        <label class="form-check-label" for="ei_view_mail">{$LANG->getModule('showmail')}</label>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="checkss" value="{$DATA.checkss}">
                            <div class="d-flex flex-wrap gap-2 mt-4">
                                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> {$LANG->getModule('editinfo_confirm')}</button>
                                <button type="button" class="btn btn-outline-secondary" data-toggle="nv-reset-form"><i class="fa-solid fa-rotate-left"></i> {$LANG->getGlobal('reset')}</button>
                            </div>
                            {if not empty($GROUP_MANAGE)}
                            <div class="mt-3">
                                <a href="{$GROUP_MANAGE.link}"><i class="fa-solid fa-caret-right"></i> {$GROUP_MANAGE.title}</a>
                            </div>
                            {/if}
                        </form>
                    </div>
                </div>
            </div>

            {* Ảnh đại diện *}
            {if $TABS.avatar}
            <div class="tab-pane fade{if $ACTIVE == 'avatar'} show active{/if}" id="usersEditinfo-avatar" role="tabpanel" aria-labelledby="usersEditinfoTab-avatar" tabindex="0">
                <div class="card">
                    <div class="card-body">
                        <h2 class="h5 border-bottom pb-3 mb-3">{$LANG->getModule('edit_avatar')}</h2>
                        <div class="d-flex flex-wrap align-items-center gap-3">
                            {if $AVATAR.src}
                            <img src="{$AVATAR.src}" alt="{$DATA.username}" width="80" height="80" class="fw-80 fh-80 rounded-circle object-fit-cover">
                            {else}
                            <span class="avatar-letters avatar-letters-lg fw-80 fh-80" style="background-color:{$AVATAR.color}" aria-hidden="true">{$AVATAR.letters}</span>
                            {/if}
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="changeAvatar" data-url="{$DATA.url_avatar}" data-action="upd" data-title="{$LANG->getModule('change_avatar')}"{if $AVATAR.direct_change} data-direct-change="1"{/if}>
                                    <i class="fa-solid fa-camera"></i> {$LANG->getModule('change_avatar')}
                                </button>
                                {if $AVATAR.src}
                                <button type="button" class="btn btn-danger btn-sm" data-toggle="usersAvatarDelete" data-checkss="{$DATA.checkss_avatar}" data-url="{$smarty.const.NV_BASE_SITEURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=avatar/del">
                                    <i class="fa-solid fa-trash" data-icon="fa-trash"></i> {$LANG->getGlobal('delete')}
                                </button>
                                {/if}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {/if}

            {* Đổi tên đăng nhập *}
            {if $TABS.username}
            <div class="tab-pane fade{if $ACTIVE == 'username'} show active{/if}" id="usersEditinfo-username" role="tabpanel" aria-labelledby="usersEditinfoTab-username" tabindex="0">
                <div class="card">
                    <div class="card-body">
                        <h2 class="h5 border-bottom pb-3 mb-3">{$LANG->getModule('edit_login')}</h2>
                        {if $PASS_EMPTY}
                        <div class="alert alert-danger">
                            <i class="fa-solid fa-triangle-exclamation"></i> {$LANG->getModule('changelogin_notvalid')}
                            <button type="button" class="btn btn-primary btn-sm ms-1" data-toggle="usersEditinfoAddPass">{$LANG->getModule('add_pass')}</button>
                        </div>
                        {/if}
                        <form action="{$EDITINFO_FORM}/username" method="post" class="{if $PASS_EMPTY}d-none{/if}" data-toggle="ajax-form" data-form="usersEditinfo" data-precheck="nv_precheck_form" autocomplete="off" novalidate>
                            <div class="alert alert-info" data-area="info" data-default="{$LANG->getModule('edit_login_warning')|escape}">{$LANG->getModule('edit_login_warning')}</div>
                            <div data-area="form">
                                <div class="mb-3">{$LANG->getModule('currentlogin')}: <strong>{$DATA.username}</strong></div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label" for="ei_username">{$LANG->getModule('newlogin')} <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                                            <input type="text" class="form-control" id="ei_username" name="username" value="" autocomplete="username"
                                                placeholder="{$LANG->getModule('newlogin')}"
                                                minlength="{$GCONFIG.nv_unickmin}" maxlength="{$GCONFIG.nv_unickmax}"
                                                data-valid data-error-type="feedback" data-valid-callback="userRegLoginCheck"
                                                data-login-type="{$GCONFIG.nv_unick_type}" data-error-mess="{$USERNAME_RULE}"
                                            >
                                        </div>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="ei_username_password">{$LANG->getModule('password')} <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                                            <input type="password" class="form-control" id="ei_username_password" name="password" value="" autocomplete="current-password"
                                                placeholder="{$LANG->getGlobal('password')}" maxlength="{$GCONFIG.nv_upassmax}"
                                                data-valid data-error-type="feedback" data-error-mess="{$LANG->getGlobal('password_empty')}"
                                            >
                                        </div>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    {if not empty($GCONFIG.allowuserloginmulti)}
                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="forcedrelogin" value="1" id="ei_username_forcedrelogin">
                                            <label class="form-check-label" for="ei_username_forcedrelogin">{$LANG->getModule('forcedrelogin')}</label>
                                        </div>
                                    </div>
                                    {/if}
                                </div>
                                <input type="hidden" name="checkss" value="{$DATA.checkss}">
                                <div class="d-flex flex-wrap gap-2 mt-4">
                                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> {$LANG->getModule('editinfo_confirm')}</button>
                                    <button type="button" class="btn btn-outline-secondary" data-toggle="nv-reset-form"><i class="fa-solid fa-rotate-left"></i> {$LANG->getGlobal('reset')}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            {/if}

            {* Đổi email *}
            {if $TABS.email}
            <div class="tab-pane fade{if $ACTIVE == 'email'} show active{/if}" id="usersEditinfo-email" role="tabpanel" aria-labelledby="usersEditinfoTab-email" tabindex="0">
                <div class="card">
                    <div class="card-body">
                        <h2 class="h5 border-bottom pb-3 mb-3">{$LANG->getModule('edit_email')}</h2>
                        {if $PASS_EMPTY}
                        <div class="alert alert-danger">
                            <i class="fa-solid fa-triangle-exclamation"></i> {$LANG->getModule('changeemail_notvalid')}
                            <button type="button" class="btn btn-primary btn-sm ms-1" data-toggle="usersEditinfoAddPass">{$LANG->getModule('add_pass')}</button>
                        </div>
                        {/if}
                        {if $EMAIL_CHANGE_REQUIRED}
                        <div class="alert alert-danger">
                            <i class="fa-solid fa-triangle-exclamation"></i> {$LANG->getModule('email_reset1_info')}
                        </div>
                        {/if}
                        <form action="{$EDITINFO_FORM}/email" method="post" class="{if $PASS_EMPTY}d-none{/if}" data-toggle="ajax-form" data-form="usersEditinfo" data-precheck="nv_precheck_form" autocomplete="off" novalidate>
                            <div class="alert alert-info" data-area="info" data-default="{$LANG->getModule('edit_email_warning')|escape}">{$LANG->getModule('edit_email_warning')}</div>
                            <div data-area="form">
                                <div class="mb-3">{$LANG->getModule('currentemail')}: <strong>{$DATA.email}</strong></div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label" for="ei_email_password">{$LANG->getModule('password')} <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                                            <input type="password" class="form-control" id="ei_email_password" name="password" value="" autocomplete="current-password"
                                                placeholder="{$LANG->getGlobal('password')}" maxlength="{$GCONFIG.nv_upassmax}"
                                                data-valid data-error-type="feedback" data-error-mess="{$LANG->getGlobal('password_empty')}"
                                            >
                                        </div>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="ei_email">{$LANG->getModule('newemail')} <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                                            <input type="email" class="form-control" id="ei_email" name="email" value="" maxlength="100" autocomplete="email"
                                                placeholder="{$LANG->getModule('newemail')}"
                                                data-valid data-error-type="feedback"
                                            >
                                        </div>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label" for="ei_verifykey">{$LANG->getModule('verifykey')}</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="ei_verifykey" name="verifykey" value="" autocomplete="off"
                                                placeholder="{$LANG->getModule('verifykey')}" minlength="32" maxlength="32"
                                                data-valid data-error-type="feedback" data-allowed-empty="1"
                                                data-error-mess="{$LANG->getModule('safe_key_invalid')}"
                                            >
                                            <button type="button" class="btn btn-secondary" data-toggle="usersEmailKeySend">
                                                <i class="fa-solid fa-paper-plane" data-icon="fa-paper-plane"></i> {$LANG->getModule('verifykey_send')}
                                            </button>
                                        </div>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    {if not empty($GCONFIG.allowuserloginmulti)}
                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="forcedrelogin" value="1" id="ei_email_forcedrelogin">
                                            <label class="form-check-label" for="ei_email_forcedrelogin">{$LANG->getModule('forcedrelogin')}</label>
                                        </div>
                                    </div>
                                    {/if}
                                </div>
                                <input type="hidden" name="checkss" value="{$DATA.checkss}">
                                <div class="d-flex flex-wrap gap-2 mt-4">
                                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> {$LANG->getModule('editinfo_confirm')}</button>
                                    <button type="button" class="btn btn-outline-secondary" data-toggle="nv-reset-form"><i class="fa-solid fa-rotate-left"></i> {$LANG->getGlobal('reset')}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            {/if}

            {* Đổi mật khẩu *}
            {if $TABS.password}
            <div class="tab-pane fade{if $ACTIVE == 'password'} show active{/if}" id="usersEditinfo-password" role="tabpanel" aria-labelledby="usersEditinfoTab-password" tabindex="0">
                <div class="card">
                    <div class="card-body">
                        <h2 class="h5 border-bottom pb-3 mb-3">{$LANG->getModule('edit_password')}</h2>
                        <form action="{$EDITINFO_FORM}/password" method="post" data-toggle="ajax-form" data-form="usersEditinfo" data-precheck="nv_precheck_form" autocomplete="off" novalidate>
                            <div class="row g-3">
                                {if $SHOW_OLD_PASS}
                                <div class="col-12">
                                    <label class="form-label" for="ei_nv_password">{$LANG->getModule('pass_old')} <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                                        <input type="password" class="form-control" id="ei_nv_password" name="nv_password" value="" autocomplete="current-password"
                                            placeholder="{$LANG->getModule('pass_old')}" maxlength="{$GCONFIG.nv_upassmax}"
                                            data-valid data-error-type="feedback" data-error-mess="{$LANG->getGlobal('password_empty')}"
                                        >
                                    </div>
                                    <div class="invalid-feedback"></div>
                                </div>
                                {/if}
                                <div class="col-md-6">
                                    <label class="form-label" for="ei_new_password">{$LANG->getModule('pass_new')} <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-solid fa-key"></i></span>
                                        <input type="password" class="form-control" id="ei_new_password" name="new_password" value="" autocomplete="new-password"
                                            placeholder="{$LANG->getModule('pass_new')}" maxlength="{$GCONFIG.nv_upassmax}"
                                            data-valid data-error-type="feedback" data-pattern="{$PASSWORD_PATTERN}" data-error-mess="{$PASSWORD_RULE}"
                                        >
                                    </div>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="ei_re_password">{$LANG->getModule('pass_new_re')} <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-solid fa-key"></i></span>
                                        <input type="password" class="form-control" id="ei_re_password" name="re_password" value="" autocomplete="new-password"
                                            placeholder="{$LANG->getModule('pass_new_re')}" maxlength="{$GCONFIG.nv_upassmax}"
                                            data-valid data-error-type="feedback" data-valid-callback="userLostpassRepassCheck"
                                            data-error-mess="{$LANG->getGlobal('passwordsincorrect')}"
                                        >
                                    </div>
                                    <div class="invalid-feedback"></div>
                                </div>
                                {if not empty($GCONFIG.allowuserloginmulti)}
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="forcedrelogin" value="1" id="ei_password_forcedrelogin">
                                        <label class="form-check-label" for="ei_password_forcedrelogin">{$LANG->getModule('forcedrelogin')}</label>
                                    </div>
                                </div>
                                {/if}
                            </div>
                            <input type="hidden" name="checkss" value="{$DATA.checkss}">
                            <div class="d-flex flex-wrap gap-2 mt-4">
                                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> {$LANG->getModule('editinfo_confirm')}</button>
                                <button type="button" class="btn btn-outline-secondary" data-toggle="nv-reset-form"><i class="fa-solid fa-rotate-left"></i> {$LANG->getGlobal('reset')}</button>
                            </div>
                            {if not empty($GROUP_MANAGE)}
                            <div class="mt-3">
                                <a href="{$GROUP_MANAGE.link}"><i class="fa-solid fa-caret-right"></i> {$GROUP_MANAGE.title}</a>
                            </div>
                            {/if}
                        </form>
                    </div>
                </div>
            </div>
            {/if}

            {* Khóa đăng nhập, form được users.passkey.js xử lý *}
            {if $TABS.passkey}
            <div class="tab-pane fade{if $ACTIVE == 'passkey'} show active{/if}" id="usersEditinfo-passkey" role="tabpanel" aria-labelledby="usersEditinfoTab-passkey" tabindex="0">
                <div class="card">
                    <div class="card-body">
                        <h2 class="h5 border-bottom pb-3 mb-3">{$LANG->getModule('edit_passkey')}</h2>
                        <form action="{$EDITINFO_FORM}/passkey" id="passkey-form" method="post" autocomplete="off" novalidate>
                            <input type="hidden" name="checkss" value="{$DATA.checkss}">
                            {if empty($LOGIN_KEYS)}
                            <div class="text-center py-3">
                                <div class="mb-3">
                                    <i class="fa-solid fa-key fa-4x text-muted" aria-hidden="true"></i>
                                </div>
                                <h3 class="h5 mb-2">{$LANG->getModule('passkey_login_create')}</h3>
                                <p>{$LANG->getModule('passkey_login_create_body')}.</p>
                                <button class="btn btn-primary d-none" type="button" data-toggle="passkey-add" data-enable-login="1">
                                    <i class="fa-solid fa-plus" data-icon="fa-plus" aria-hidden="true"></i> {$LANG->getModule('passkey_add')}
                                </button>
                                <div class="text-danger d-none" data-toggle="passkey-not-supported">{$LANG->getModule('passkey_not_supported')}</div>
                                <div class="text-danger mt-2 d-none" data-toggle="error"></div>
                            </div>
                            {else}
                            <div class="text-danger mb-3 d-none" data-toggle="passkey-not-supported">{$LANG->getModule('passkey_not_supported')}</div>
                            <p>{$LANG->getModule('passkey_login_create_body')}.</p>
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
                                <h3 class="h6 mb-0 fw-bold">{$LANG->getModule('passkey_list')}</h3>
                                <button class="btn btn-primary btn-sm d-none" type="button" data-toggle="passkey-add" data-enable-login="1">
                                    <i class="fa-solid fa-plus" data-icon="fa-plus" aria-hidden="true"></i> {$LANG->getModule('passkey_add')}
                                </button>
                            </div>
                            <div class="text-danger mb-2 d-none" data-toggle="error"></div>
                            <ul class="list-group">
                                {foreach from=$LOGIN_KEYS item=publicKey}
                                <li class="list-group-item">
                                    <div class="d-flex flex-wrap justify-content-between gap-2">
                                        <div>
                                            <strong><i class="fa-solid fa-key" aria-hidden="true"></i> {$publicKey.nickname}</strong>
                                            {if $publicKey.is_this_client}
                                            <span class="badge text-bg-secondary">{$LANG->getModule('passkey_seenthis')}</span>
                                            {/if}
                                            <div class="mt-1 text-muted small">
                                                {$LANG->getModule('passkey_created_at')}: {$publicKey.created_at} |
                                                {$LANG->getModule('passkey_last_used_at')}: {$publicKey.last_used_at}.
                                            </div>
                                        </div>
                                        <div class="d-flex gap-1 align-items-start">
                                            <button type="button" class="btn btn-sm btn-secondary" data-toggle="edit" data-id="{$publicKey.id}" data-nickname="{$publicKey.nickname}">
                                                <i class="fa-solid fa-pencil" data-icon="fa-pencil" aria-hidden="true"></i> {$LANG->getGlobal('edit')}
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" data-toggle="del" data-id="{$publicKey.id}">
                                                <i class="fa-solid fa-trash" data-icon="fa-trash" aria-hidden="true"></i> {$LANG->getGlobal('delete')}
                                            </button>
                                        </div>
                                    </div>
                                </li>
                                {/foreach}
                            </ul>
                            {/if}
                        </form>
                    </div>
                </div>
            </div>
            {/if}

            {* Ngôn ngữ giao diện *}
            {if $TABS.langinterface}
            <div class="tab-pane fade{if $ACTIVE == 'langinterface'} show active{/if}" id="usersEditinfo-langinterface" role="tabpanel" aria-labelledby="usersEditinfoTab-langinterface" tabindex="0">
                <div class="card">
                    <div class="card-body">
                        <h2 class="h5 border-bottom pb-3 mb-3">{$LANG->getGlobal('langinterface')}</h2>
                        <form action="{$EDITINFO_FORM}/langinterface" method="post" data-toggle="ajax-form" data-form="usersEditinfo" data-precheck="nv_precheck_form" novalidate>
                            <label class="form-label" for="ei_langinterface">{$LANG->getGlobal('langinterface')}</label>
                            <select class="form-select w-auto" id="ei_langinterface" name="langinterface" autocomplete="off">
                                <option value="">{$LANG->getModule('bydatalang')}</option>
                                {foreach from=$LANGS item=lang}
                                <option value="{$lang.val}"{if $lang.val == $DATA.langinterface} selected{/if}>{$lang.name}</option>
                                {/foreach}
                            </select>
                            <input type="hidden" name="checkss" value="{$DATA.checkss}">
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> {$LANG->getGlobal('submit')}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            {/if}

            {* Câu hỏi bảo mật *}
            {if $TABS.question}
            <div class="tab-pane fade{if $ACTIVE == 'question'} show active{/if}" id="usersEditinfo-question" role="tabpanel" aria-labelledby="usersEditinfoTab-question" tabindex="0">
                <div class="card">
                    <div class="card-body">
                        <h2 class="h5 border-bottom pb-3 mb-3">{$LANG->getModule('edit_question')}</h2>
                        {if $PASS_EMPTY}
                        <div class="alert alert-danger">
                            <i class="fa-solid fa-triangle-exclamation"></i> {$LANG->getModule('changequestion_notvalid')}
                            <button type="button" class="btn btn-primary btn-sm ms-1" data-toggle="usersEditinfoAddPass">{$LANG->getModule('add_pass')}</button>
                        </div>
                        {/if}
                        <form action="{$EDITINFO_FORM}/question" method="post" class="{if $PASS_EMPTY}d-none{/if}" data-toggle="ajax-form" data-form="usersEditinfo" data-precheck="nv_precheck_form" autocomplete="off" novalidate>
                            <div class="alert alert-info">{$LANG->getModule('edit_question_warning')}</div>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label" for="ei_question_password">{$LANG->getModule('password')} <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                                        <input type="password" class="form-control" id="ei_question_password" name="nv_password" value="" autocomplete="current-password"
                                            placeholder="{$LANG->getGlobal('password')}" maxlength="{$GCONFIG.nv_upassmax}"
                                            data-valid data-error-type="feedback" data-error-mess="{$LANG->getGlobal('password_empty')}"
                                        >
                                    </div>
                                    <div class="invalid-feedback"></div>
                                </div>
                                {if isset($QUESTION_FIELDS.question)}
                                <div class="col-md-6">
                                    <label class="form-label" for="ei_question">{$QUESTION_FIELDS.question.title}{if $QUESTION_FIELDS.question.required} <span class="text-danger">*</span>{/if}</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control {$QUESTION_FIELDS.question.class}" id="ei_question"
                                            placeholder="{$QUESTION_FIELDS.question.title}" value="" name="question" autocomplete="off"
                                            minlength="{$QUESTION_FIELDS.question.min_length}" maxlength="{$QUESTION_FIELDS.question.max_length}"
                                            {if $QUESTION_FIELDS.question.required} data-valid data-error-type="feedback"{/if}
                                            data-error-mess="{$QUESTION_FIELDS.question.errmess}"
                                        >
                                        {if not empty($QUESTIONS)}
                                        <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" aria-label="{$QUESTION_FIELDS.question.title}"></button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            {foreach from=$QUESTIONS item=question}
                                            <li><a class="dropdown-item" href="#" data-toggle="addQuestion">{$question.title}</a></li>
                                            {/foreach}
                                        </ul>
                                        {/if}
                                    </div>
                                    <div class="invalid-feedback"></div>
                                    {if $QUESTION_FIELDS.question.description}<div class="form-text">{$QUESTION_FIELDS.question.description}</div>{/if}
                                </div>
                                {/if}
                                {if isset($QUESTION_FIELDS.answer)}
                                <div class="col-md-6">
                                    <label class="form-label" for="ei_answer">{$QUESTION_FIELDS.answer.title}{if $QUESTION_FIELDS.answer.required} <span class="text-danger">*</span>{/if}</label>
                                    <input type="text" class="form-control {$QUESTION_FIELDS.answer.class}" id="ei_answer"
                                        placeholder="{$QUESTION_FIELDS.answer.title}" value="" name="answer" autocomplete="off"
                                        minlength="{$QUESTION_FIELDS.answer.min_length}" maxlength="{$QUESTION_FIELDS.answer.max_length}"
                                        {if $QUESTION_FIELDS.answer.required} data-valid data-error-type="feedback"{/if}
                                        data-error-mess="{$QUESTION_FIELDS.answer.errmess}"
                                    >
                                    <div class="invalid-feedback"></div>
                                    {if $QUESTION_FIELDS.answer.description}<div class="form-text">{$QUESTION_FIELDS.answer.description}</div>{/if}
                                </div>
                                {/if}
                            </div>
                            <input type="hidden" name="checkss" value="{$DATA.checkss}">
                            <div class="d-flex flex-wrap gap-2 mt-4">
                                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> {$LANG->getModule('editinfo_confirm')}</button>
                                <button type="button" class="btn btn-outline-secondary" data-toggle="nv-reset-form"><i class="fa-solid fa-rotate-left"></i> {$LANG->getGlobal('reset')}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            {/if}

            {* Tài khoản bên thứ ba *}
            {if $TABS.openid}
            <div class="tab-pane fade{if $ACTIVE == 'openid'} show active{/if}" id="usersEditinfo-openid" role="tabpanel" aria-labelledby="usersEditinfoTab-openid" tabindex="0">
                <div class="card">
                    <div class="card-body">
                        <h2 class="h5 border-bottom pb-3 mb-3">{$LANG->getModule('openid_administrator')}</h2>
                        {if not empty($OPENIDS)}
                        <form action="{$EDITINFO_FORM}/openid" method="post" class="mb-3" data-toggle="ajax-form" data-form="usersEditinfo" data-precheck="nv_precheck_form" autocomplete="off" novalidate>
                            <div class="table-responsive">
                                <table class="table table-striped align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-nowrap" style="width:5%">
                                                {if $OPENID_DEL_COUNT > 1}
                                                <input type="checkbox" class="form-check-input checkAll" data-toggle="checkAll" aria-label="{$LANG->getModule('openid_del')}">
                                                {/if}
                                            </th>
                                            <th class="text-nowrap" style="width:40%">{$LANG->getModule('openid_server')}</th>
                                            <th class="text-nowrap" style="width:55%">{$LANG->getModule('openid_email_or_id')}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {foreach from=$OPENIDS item=openid}
                                        <tr>
                                            <td>
                                                {if $openid.disabled}
                                                <i class="fa-solid fa-shield-halved text-danger" data-bs-toggle="tooltip" title="{$LANG->getModule('openid_default')}" aria-label="{$LANG->getModule('openid_default')}"></i>
                                                {else}
                                                <input type="checkbox" class="form-check-input checkSingle" name="openid_del[]" value="{$openid.opid}" data-toggle="checkSingle" aria-label="{$openid.openid}">
                                                {/if}
                                            </td>
                                            <td>{$openid.openid}</td>
                                            <td class="text-break">{$openid.email_or_id}</td>
                                        </tr>
                                        {/foreach}
                                    </tbody>
                                </table>
                            </div>
                            {if $OPENID_DEL_COUNT}
                            <input type="hidden" name="checkss" value="{$DATA.checkss}">
                            <div class="mt-3">
                                <button type="submit" class="btn btn-danger"><i class="fa-solid fa-link-slash"></i> {$LANG->getModule('openid_del')}</button>
                            </div>
                            {/if}
                        </form>
                        {/if}
                        <div class="border rounded-3 p-3 bg-body-tertiary text-center">
                            <div class="mb-3">{$LANG->getModule('openid_add_new')}</div>
                            {assign var="oauth_icons" value=[
                                'single-sign-on' => '<i class="fa-solid fa-building-lock"></i>',
                                'google' => '<i class="fa-brands fa-google"></i>',
                                'facebook' => '<i class="fa-brands fa-facebook"></i>',
                                'zalo' => '<svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="1.2em" height="1.2em" viewBox="0,0,256,256"><g fill="currentColor" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(5.12,5.12)"><path d="M9,4c-2.74952,0 -5,2.25048 -5,5v32c0,2.74952 2.25048,5 5,5h32c2.74952,0 5,-2.25048 5,-5v-32c0,-2.74952 -2.25048,-5 -5,-5zM9,6h6.58008c-3.57109,3.71569 -5.58008,8.51808 -5.58008,13.5c0,5.16 2.11016,10.09984 5.91016,13.83984c0.12,0.21 0.21977,1.23969 -0.24023,2.42969c-0.29,0.75 -0.87023,1.72961 -1.99023,2.09961c-0.43,0.14 -0.70969,0.56172 -0.67969,1.01172c0.03,0.45 0.36078,0.82992 0.80078,0.91992c2.87,0.57 4.72852,-0.2907 6.22852,-0.9707c1.35,-0.62 2.24133,-1.04047 3.61133,-0.48047c2.8,1.09 5.77938,1.65039 8.85938,1.65039c4.09369,0 8.03146,-0.99927 11.5,-2.88672v3.88672c0,1.66848 -1.33152,3 -3,3h-32c-1.66848,0 -3,-1.33152 -3,-3v-32c0,-1.66848 1.33152,-3 3,-3zM33,15c0.55,0 1,0.45 1,1v9c0,0.55 -0.45,1 -1,1c-0.55,0 -1,-0.45 -1,-1v-9c0,-0.55 0.45,-1 1,-1zM18,16h5c0.36,0 0.70086,0.19953 0.88086,0.51953c0.17,0.31 0.15875,0.69977 -0.03125,1.00977l-4.04883,6.4707h3.19922c0.55,0 1,0.45 1,1c0,0.55 -0.45,1 -1,1h-5c-0.36,0 -0.70086,-0.19953 -0.88086,-0.51953c-0.17,-0.31 -0.15875,-0.69977 0.03125,-1.00977l4.04883,-6.4707h-3.19922c-0.55,0 -1,-0.45 -1,-1c0,-0.55 0.45,-1 1,-1zM27.5,19c0.61,0 1.17945,0.16922 1.68945,0.44922c0.18,-0.26 0.46055,-0.44922 0.81055,-0.44922c0.55,0 1,0.45 1,1v5c0,0.55 -0.45,1 -1,1c-0.35,0 -0.63055,-0.18922 -0.81055,-0.44922c-0.51,0.28 -1.07945,0.44922 -1.68945,0.44922c-1.93,0 -3.5,-1.57 -3.5,-3.5c0,-1.93 1.57,-3.5 3.5,-3.5zM38.5,19c1.93,0 3.5,1.57 3.5,3.5c0,1.93 -1.57,3.5 -3.5,3.5c-1.93,0 -3.5,-1.57 -3.5,-3.5c0,-1.93 1.57,-3.5 3.5,-3.5zM27.5,21c-0.10375,0 -0.20498,0.01131 -0.30273,0.03125c-0.19551,0.03988 -0.37754,0.11691 -0.53711,0.22461c-0.15957,0.1077 -0.2966,0.24473 -0.4043,0.4043c-0.10769,0.15957 -0.18473,0.3416 -0.22461,0.53711c-0.01994,0.09775 -0.03125,0.19898 -0.03125,0.30273c0,0.10375 0.01131,0.20498 0.03125,0.30273c0.01994,0.09775 0.04805,0.19149 0.08594,0.28125c0.03789,0.08977 0.08482,0.17607 0.13867,0.25586c0.05385,0.07979 0.11578,0.15289 0.18359,0.2207c0.06781,0.06781 0.14092,0.12975 0.2207,0.18359c0.15957,0.10769 0.3416,0.18473 0.53711,0.22461c0.09775,0.01994 0.19898,0.03125 0.30273,0.03125c0.10375,0 0.20498,-0.01131 0.30273,-0.03125c0.68428,-0.13959 1.19727,-0.7425 1.19727,-1.46875c0,-0.83 -0.67,-1.5 -1.5,-1.5zM38.5,21c-0.10375,0 -0.20498,0.01131 -0.30273,0.03125c-0.09775,0.01994 -0.19149,0.04805 -0.28125,0.08594c-0.08977,0.03789 -0.17607,0.08482 -0.25586,0.13867c-0.07979,0.05385 -0.15289,0.11578 -0.2207,0.18359c-0.13562,0.13563 -0.24648,0.29703 -0.32227,0.47656c-0.03789,0.08976 -0.066,0.1835 -0.08594,0.28125c-0.01994,0.09775 -0.03125,0.19898 -0.03125,0.30273c0,0.10375 0.01131,0.20498 0.03125,0.30273c0.01994,0.09775 0.04805,0.19149 0.08594,0.28125c0.03789,0.08977 0.08482,0.17607 0.13867,0.25586c0.05385,0.07979 0.11578,0.15289 0.18359,0.2207c0.06781,0.06781 0.14092,0.12975 0.2207,0.18359c0.07979,0.05385 0.16609,0.10078 0.25586,0.13867c0.08976,0.03789 0.1835,0.066 0.28125,0.08594c0.09775,0.01994 0.19898,0.03125 0.30273,0.03125c0.10375,0 0.20498,-0.01131 0.30273,-0.03125c0.68428,-0.13959 1.19727,-0.7425 1.19727,-1.46875c0,-0.83 -0.67,-1.5 -1.5,-1.5z"></path></g></g></svg>'
                            ]}
                            <div class="d-flex flex-wrap justify-content-center gap-2">
                                {foreach from=$OPENID_SERVERS item=server}
                                <a class="btn btn-{$server.icon} d-inline-flex align-items-center gap-2" href="{$server.href}" data-toggle="openID_load">
                                    {$oauth_icons[$server.icon]|default:''} {$server.title}
                                </a>
                                {/foreach}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {/if}

            {* Nhóm thành viên *}
            {if $TABS.group}
            <div class="tab-pane fade{if $ACTIVE == 'group'} show active{/if}" id="usersEditinfo-group" role="tabpanel" aria-labelledby="usersEditinfoTab-group" tabindex="0">
                <div class="card">
                    <div class="card-body">
                        <h2 class="h5 border-bottom pb-3 mb-3">{$LANG->getModule('group')}</h2>
                        <form action="{$EDITINFO_FORM}/group" method="post" data-toggle="ajax-form" data-form="usersEditinfo" data-precheck="nv_precheck_form" autocomplete="off" novalidate>
                            <div class="table-responsive">
                                <table class="table table-striped align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-nowrap" style="width:5%">
                                                {if $GROUPS|@count > 1}
                                                <input type="checkbox" class="form-check-input checkAll" data-toggle="checkAll" aria-label="{$LANG->getModule('group_reg')}"{if $GROUP_CHECK_ALL} checked{/if}>
                                                {/if}
                                            </th>
                                            <th class="text-nowrap" style="width:35%">{$LANG->getModule('group_name')}</th>
                                            <th class="text-nowrap" style="width:55%">{$LANG->getModule('group_description')}</th>
                                            <th class="text-nowrap" style="width:5%"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {foreach from=$GROUPS item=group}
                                        <tr>
                                            <td>
                                                {if $group.group_type}
                                                <input type="checkbox" class="form-check-input checkSingle" name="in_groups[]" value="{$group.group_id}" data-toggle="checkSingle" aria-label="{$group.title}"{if $group.checked} checked{/if}{if $group.is_leader} disabled{/if}>
                                                {* Trưởng nhóm không thể tự rời nhóm, checkbox bị khóa nên gửi giá trị qua input ẩn *}
                                                {if $group.is_leader}<input type="hidden" name="in_groups[]" value="{$group.group_id}">{/if}
                                                {/if}
                                            </td>
                                            <td>
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#usersGroupModal-{$group.group_id}"><strong>{$group.title}</strong></a>
                                                <div class="small text-success">{$group.group_type_mess}</div>
                                                {if $group.is_leader}
                                                <div class="small"><a class="link-danger" href="{$group.leader_url}"><i class="fa-solid fa-users"></i> {$LANG->getModule('group_manage')}</a></div>
                                                {/if}
                                            </td>
                                            <td>{$group.description}</td>
                                            <td class="text-end">
                                                {if $group.status == 1}
                                                <i class="fa-solid fa-check text-success" data-bs-toggle="tooltip" title="{$group.status_mess}" aria-label="{$group.status_mess}"></i>
                                                {elseif $group.status == 2}
                                                <i class="fa-solid fa-hourglass-half text-warning" data-bs-toggle="tooltip" title="{$group.status_mess}" aria-label="{$group.status_mess}"></i>
                                                {else}
                                                <i class="fa-solid fa-power-off text-muted" data-bs-toggle="tooltip" title="{$group.status_mess}" aria-label="{$group.status_mess}"></i>
                                                {/if}
                                            </td>
                                        </tr>
                                        {/foreach}
                                    </tbody>
                                </table>
                            </div>
                            <input type="hidden" name="checkss" value="{$DATA.checkss}">
                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-user-plus"></i> {$LANG->getModule('group_reg')}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            {/if}

            {* Các trường dữ liệu tùy chỉnh *}
            {if $TABS.others}
            <div class="tab-pane fade{if $ACTIVE == 'others'} show active{/if}" id="usersEditinfo-others" role="tabpanel" aria-labelledby="usersEditinfoTab-others" tabindex="0">
                <div class="card">
                    <div class="card-body">
                        <h2 class="h5 border-bottom pb-3 mb-3">{$LANG->getModule('edit_others')}</h2>
                        <form action="{$EDITINFO_FORM}/others" method="post" data-toggle="ajax-form" data-form="usersEditinfo" data-precheck="nv_precheck_form" autocomplete="off" novalidate>
                            <div class="row g-3">
                                {include file='custom_fields_form.tpl'}
                            </div>
                            <input type="hidden" name="checkss" value="{$DATA.checkss}">
                            <div class="d-flex flex-wrap gap-2 mt-4">
                                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> {$LANG->getModule('editinfo_confirm')}</button>
                                <button type="button" class="btn btn-outline-secondary" data-toggle="nv-reset-form"><i class="fa-solid fa-rotate-left"></i> {$LANG->getGlobal('reset')}</button>
                            </div>
                            {if not empty($GROUP_MANAGE)}
                            <div class="mt-3">
                                <a href="{$GROUP_MANAGE.link}"><i class="fa-solid fa-caret-right"></i> {$GROUP_MANAGE.title}</a>
                            </div>
                            {/if}
                        </form>
                    </div>
                </div>
            </div>
            {/if}

            {* Bật chế độ an toàn *}
            {if $TABS.safemode}
            <div class="tab-pane fade{if $ACTIVE == 'safemode'} show active{/if}" id="usersEditinfo-safemode" role="tabpanel" aria-labelledby="usersEditinfoTab-safemode" tabindex="0">
                <div class="card">
                    <div class="card-body">
                        <h2 class="h5 border-bottom pb-3 mb-3">{$LANG->getModule('safe_mode')}</h2>
                        {if $PASS_EMPTY}
                        <div class="alert alert-danger">
                            <i class="fa-solid fa-triangle-exclamation"></i> {$LANG->getModule('safe_deactive_notvalid')}
                            <button type="button" class="btn btn-primary btn-sm ms-1" data-toggle="usersEditinfoAddPass">{$LANG->getModule('add_pass')}</button>
                        </div>
                        {/if}
                        <form action="{$EDITINFO_FORM}/safemode" method="post" class="{if $PASS_EMPTY}d-none{/if}" data-toggle="ajax-form" data-form="usersEditinfo" data-precheck="nv_precheck_form" autocomplete="off" novalidate>
                            <h3 class="h5 text-center mb-3"><i class="fa-solid fa-shield-halved text-danger"></i> {$LANG->getModule('safe_activate')}</h3>
                            <div class="alert alert-info" data-area="info" data-default="{$LANG->getModule('safe_activate_info')|escape}">{$LANG->getModule('safe_activate_info')}</div>
                            <div data-area="form">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label" for="ei_safemode_password">{$LANG->getGlobal('password')} <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa-solid fa-key"></i></span>
                                            <input type="password" class="form-control" id="ei_safemode_password" name="nv_password" value="" autocomplete="current-password"
                                                placeholder="{$LANG->getGlobal('password')}" maxlength="{$GCONFIG.nv_upassmax}"
                                                data-valid data-error-type="feedback" data-error-mess="{$LANG->getGlobal('password_empty')}"
                                            >
                                        </div>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label" for="ei_safe_key">{$LANG->getModule('safe_key')} <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa-solid fa-shield"></i></span>
                                            <input type="text" class="form-control" id="ei_safe_key" name="safe_key" value="" autocomplete="off"
                                                placeholder="{$LANG->getModule('safe_key')}" minlength="32" maxlength="32"
                                                data-valid data-error-type="feedback" data-error-mess="{$LANG->getModule('safe_key_invalid')}"
                                            >
                                            <button type="button" class="btn btn-secondary" data-toggle="usersSafeKeySend">
                                                <i class="fa-solid fa-paper-plane" data-icon="fa-paper-plane"></i> {$LANG->getModule('verifykey_send')}
                                            </button>
                                        </div>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <input type="hidden" name="checkss" value="{$DATA.checkss}">
                                <div class="d-flex flex-wrap gap-2 mt-4">
                                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> {$LANG->getModule('editinfo_confirm')}</button>
                                    <button type="button" class="btn btn-outline-secondary" data-toggle="nv-reset-form"><i class="fa-solid fa-rotate-left"></i> {$LANG->getGlobal('reset')}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            {/if}
        </div>
    </div>
</div>
{if not empty($NAVS)}
<ul class="list-inline">
    {foreach from=$NAVS item=nav}
    <li class="list-inline-item text-nowrap me-3"><a href="{$nav.href}"><i class="fa-solid fa-caret-right"></i> {$nav.title}</a></li>
    {/foreach}
</ul>
{/if}
{* Chi tiết các nhóm, đặt ngoài tab-pane để modal không bị ảnh hưởng bởi khung chứa *}
{if $TABS.group}
{foreach from=$GROUPS item=group}
<div class="modal fade" id="usersGroupModal-{$group.group_id}" tabindex="-1" aria-labelledby="usersGroupModalLabel-{$group.group_id}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="usersGroupModalLabel-{$group.group_id}">{$group.title}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="modal-body">
                {if $group.description}<p class="text-muted">{$group.description}</p>{/if}
                <div class="d-flex gap-3 mb-3">
                    <img src="{$group.group_avatar}" alt="{$group.title}" width="80" height="80" class="fw-80 fh-80 rounded object-fit-cover flex-shrink-0">
                    <div>
                        <div><strong>{$LANG->getModule('group_type')}:</strong> {$group.group_type_mess}{if $group.group_type_note} ({$group.group_type_note}){/if}</div>
                        <div><strong>{$LANG->getModule('group_exp_time')}:</strong> {$group.exp}</div>
                        <div><strong>{$LANG->getModule('group_userr')}:</strong> {$group.numbers}</div>
                    </div>
                </div>
                {$group.content}
            </div>
        </div>
    </div>
</div>
{/foreach}
{/if}
{* Các modal của khóa đăng nhập, users.passkey.js tìm theo data-toggle *}
{if $TABS.passkey}
<div class="modal fade" tabindex="-1" data-toggle="md-complete-passkey">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{$LANG->getModule('passkey_created')}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="modal-body">
                {$LANG->getModule('passkey_created_body')}
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-success" data-toggle="passkey-reload">{$LANG->getGlobal('complete')}</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" data-toggle="md-edit-passkey">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{$LANG->getModule('passkey_nickname_edit')}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="modal-body">
                <form action="{$EDITINFO_FORM}/passkey" id="passkey-form-nickname" method="post" autocomplete="off" novalidate>
                    <input type="hidden" name="checkss" value="{$DATA.checkss}">
                    <input type="hidden" name="id" value="0">
                    <div class="mb-3">
                        <label for="element_nickname" class="form-label">{$LANG->getModule('passkey_nickname')} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nickname" data-nickname="" id="element_nickname" value="" maxlength="100" autocomplete="off">
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-floppy-disk" data-icon="fa-floppy-disk" aria-hidden="true"></i> {$LANG->getGlobal('save')}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{/if}
