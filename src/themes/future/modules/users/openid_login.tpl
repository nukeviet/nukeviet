<div class="d-flex justify-content-center my-3">
    <div class="rounded-4 border shadow-lg p-4 w-100 maxw-500" data-toggle="usersOpenid">
        <div class="mb-2 d-flex justify-content-center">
            <div class="d-flex fw-40 fh-40 align-items-center rounded-circle justify-content-center bg-primary-subtle text-primary-emphasis">
                <i class="fa-solid fa-link"></i>
            </div>
        </div>
        <h1 class="h2 text-center mb-3">{$PAGE_TITLE}</h1>
        <div class="mb-3">{$INFO}</div>
        {if not empty($ACTIONS)}
        <div class="mb-3">
            <select class="form-select" data-toggle="usersOpenidChooseAction" aria-label="{$LANG->getModule('openid_note')}">
                {foreach from=$ACTIONS item=action}
                <option value="{$action.key}">{$action.name}</option>
                {/foreach}
            </select>
        </div>
        {/if}
        {if isset($OP_PROCESS.create)}
        <div class="{if $FIRST neq 'create'}d-none{/if}" data-area="usersOpenidCreate">
            <form action="{$FORM_ACTION}" method="post" data-precheck="nv_precheck_form" autocomplete="off" novalidate>
                {* Ô mồi tránh trình duyệt tự điền thông tin đăng nhập đã lưu *}
                <input type="email" name="email_hidden" class="d-none" tabindex="-1" aria-hidden="true">
                <input type="password" name="password_hidden" class="d-none" tabindex="-1" aria-hidden="true">
                <div class="mb-3">
                    <label class="form-label" for="openid_reg_username">{$LANG->getGlobal('username')} <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="openid_reg_username" name="reg_username" value="{$REG.username}" maxlength="{$GCONFIG.nv_unickmax}"
                        data-valid data-error-type="feedback"
                        data-error-mess="{$LANG->getGlobal('username_empty')}"
                    >
                    <div class="invalid-feedback"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="openid_reg_email">{$LANG->getGlobal('email')} <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="openid_reg_email" name="reg_email" value="{$REG.email}" maxlength="100"{if not empty($REG.email)} readonly{/if}
                        data-valid data-error-type="feedback"
                        data-error-mess="{$LANG->getGlobal('email_incorrect')}"
                    >
                    <div class="invalid-feedback"></div>
                </div>
                {if empty($REG.email)}
                <div class="mb-3">
                    <label class="form-label" for="openid_verify_code">{$LANG->getModule('verifykey')} <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="openid_verify_code" name="verify_code" value="" autocomplete="off" minlength="8" maxlength="8"
                            placeholder="{$LANG->getModule('verifykey')}"
                            data-valid data-error-type="feedback"
                        >
                        <button type="button" class="btn btn-secondary" data-toggle="usersOpenidKeySend">
                            <i class="fa-solid fa-paper-plane" data-icon="fa-paper-plane"></i> {$LANG->getModule('verifykey_send')}
                        </button>
                    </div>
                    <div class="invalid-feedback"></div>
                </div>
                {/if}
                <div class="mb-3">
                    <label class="form-label" for="openid_reg_password">{$LANG->getGlobal('password')} <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="openid_reg_password" name="reg_password" value="" autocomplete="new-password" maxlength="{$GCONFIG.nv_upassmax}"
                        data-pattern="{$PASSWORD_PATTERN}" data-valid data-error-type="feedback"
                        data-error-mess="{$PASSWORD_RULE}"
                    >
                    <div class="invalid-feedback"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="openid_reg_repassword">{$LANG->getGlobal('password2')} <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="openid_reg_repassword" name="reg_repassword" value="" autocomplete="new-password" maxlength="{$GCONFIG.nv_upassmax}"
                        data-valid data-error-type="feedback" data-valid-callback="userOpenidRepassCheck"
                        data-error-mess="{$LANG->getGlobal('passwordsincorrect')}"
                    >
                    <div class="invalid-feedback"></div>
                </div>
                <input type="hidden" name="nv_reg" value="1">
                {if not empty($NV_REDIRECT)}
                <input type="hidden" name="nv_redirect" value="{$NV_REDIRECT}">
                {/if}
                <button type="submit" class="btn btn-primary w-100">{$LANG->getGlobal('register')}</button>
            </form>
        </div>
        {/if}
        {if isset($OP_PROCESS.connect)}
        <div class="{if $FIRST neq 'connect'}d-none{/if}" data-area="usersOpenidConnect">
            <form action="{$FORM_ACTION}" method="post" data-precheck="nv_precheck_form" autocomplete="off" novalidate>
                <div class="mb-3">
                    <label class="form-label" for="openid_login">{$LANG->getGlobal('username')} <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="openid_login" name="login" value="" autocomplete="username" maxlength="100"
                        data-valid data-error-type="feedback"
                        data-error-mess="{$LANG->getGlobal('username_empty')}"
                    >
                    <div class="invalid-feedback"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="openid_password">{$LANG->getGlobal('password')} <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="openid_password" name="password" value="" autocomplete="current-password" maxlength="100"
                        data-valid data-error-type="feedback"
                        data-error-mess="{$LANG->getGlobal('password_empty')}"
                    >
                    <div class="invalid-feedback"></div>
                </div>
                <input type="hidden" name="nv_login" value="1">
                {if not empty($NV_REDIRECT)}
                <input type="hidden" name="nv_redirect" value="{$NV_REDIRECT}">
                {/if}
                <button type="submit" class="btn btn-primary w-100">
                    {$LANG->getGlobal('loginsubmit')} <i class="fa-solid fa-arrow-right-long align-baseline-xs ms-1"></i>
                </button>
            </form>
        </div>
        {/if}
        {if isset($OP_PROCESS.auto)}
        <div class="d-none" data-area="usersOpenidAuto">
            <form action="{$FORM_ACTION}" method="post">
                <input type="hidden" name="nv_auto" value="1">
                {if not empty($NV_REDIRECT)}
                <input type="hidden" name="nv_redirect" value="{$NV_REDIRECT}">
                {/if}
            </form>
        </div>
        {/if}
    </div>
</div>
