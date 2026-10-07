<form action="{$ACTIONFORM}" id="site_config" method="post" autocomplete="off" novalidate data-toggle="siteConfig">
    <input type="text" value="" class="d-none" tabindex="-1" aria-hidden="true">
    <input type="password" value="" class="d-none" tabindex="-1" aria-hidden="true">
    <p class="text-body-secondary">{$LANG->getModule('properties')} <span class="text-danger">*</span> {$LANG->getModule('is_required')}</p>
    {if not empty($DATA.error)}
    <div class="alert alert-danger">{$DATA.error}</div>
    {/if}
    <div class="row g-3">
        <div class="col-12">
            <label for="site_name" class="form-label">{$LANG->getModule('sitename')} <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="site_name" name="site_name" value="{$DATA.site_name}" required>
            <div class="form-text">{$LANG->getModule('sitename_note')}</div>
        </div>
        <div class="col-sm-6">
            <label for="nv_login_iavim" class="form-label">{$LANG->getModule('admin_account')} <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="nv_login_iavim" name="nv_login" value="{$DATA.nv_login}" minlength="5" required>
            <div class="form-text">{$LANG->getModule('admin_account_note')}</div>
        </div>
        <div class="col-sm-6">
            <label for="nv_email_iavim" class="form-label">{$LANG->getModule('admin_email')} <span class="text-danger">*</span></label>
            <input type="email" class="form-control" id="nv_email_iavim" name="nv_email" value="{$DATA.nv_email}" required>
            <div class="form-text">{$LANG->getModule('admin_email_note')}</div>
        </div>
        <div class="col-sm-6">
            <label for="nv_password_iavim" class="form-label">{$LANG->getModule('admin_pass')} <span class="text-danger">*</span></label>
            <input type="password" class="form-control" id="nv_password_iavim" name="nv_password" value="{$DATA.nv_password}" minlength="6" autocomplete="new-password" required>
            <div class="form-text">{$LANG->getModule('admin_pass_note')}</div>
        </div>
        <div class="col-sm-6">
            <label for="re_password_iavim" class="form-label">{$LANG->getModule('admin_repass')} <span class="text-danger">*</span></label>
            <input type="password" class="form-control" id="re_password_iavim" name="re_password" value="{$DATA.re_password}" autocomplete="new-password" required>
            <div class="form-text">{$LANG->getModule('admin_repass_note')}</div>
        </div>
        <div class="col-sm-6">
            <label for="question" class="form-label">{$LANG->getModule('question')} <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="question" name="question" value="{$DATA.question}" required>
            <div class="form-text">{$LANG->getModule('question_note')}</div>
        </div>
        <div class="col-sm-6">
            <label for="answer_question" class="form-label">{$LANG->getModule('answer_question')} <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="answer_question" name="answer_question" value="{$DATA.answer_question}" required>
            <div class="form-text">{$LANG->getModule('answer_question_note')}</div>
        </div>
        <div class="col-12">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" id="lang_multi" name="lang_multi" value="1"{if not empty($DATA.lang_multi)} checked{/if}>
                <label class="form-check-label" for="lang_multi">{$LANG->getModule('lang_multi')}</label>
            </div>
            <div class="form-text mt-0 mb-2">{$LANG->getModule('lang_multi_note')}</div>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" id="dev_mode" name="dev_mode" value="1"{if not empty($DATA.dev_mode)} checked{/if}>
                <label class="form-check-label" for="dev_mode">{$LANG->getModule('dev_mode')}</label>
            </div>
            <div class="form-text mt-0">{$LANG->getModule('dev_mode_note')}</div>
        </div>
        <div class="col-12">
            <button class="btn btn-primary" type="submit"><i class="fa-solid fa-floppy-disk"></i> {$LANG->getModule('refesh')}</button>
        </div>
    </div>
</form>
<div class="install-nav">
    <a class="btn btn-outline-secondary back_step" href="{$STEP_URL}5"><i class="fa-solid fa-arrow-left"></i> {$LANG->getModule('previous')}</a>
    {if $NEXTSTEP}
    <span class="next_step"><a class="btn btn-primary" href="{$STEP_URL}7">{$LANG->getModule('next_step')} <i class="fa-solid fa-arrow-right"></i></a></span>
    {/if}
</div>
