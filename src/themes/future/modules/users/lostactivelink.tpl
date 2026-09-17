<div class="d-flex justify-content-center">
    <div class="rounded-4 border shadow-lg p-4">
        <div class="mb-2 d-flex justify-content-center">
            <div class="d-flex fw-40 fh-40 align-items-center rounded-circle justify-content-center bg-primary-subtle text-primary-emphasis">
                <i class="fa-solid fa-link"></i>
            </div>
        </div>
        <h1 class="h2 text-center mb-3">{$LANG->getModule('lostactive_pagetitle')}</h1>
        {if $DATA.step == 2}
        {* Bước 2: Trả lời câu hỏi bảo mật *}
        <form action="{$smarty.const.NV_BASE_SITEURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=lostactivelink" method="post" class="fw-300"
            data-precheck="nv_precheck_form" autocomplete="off" novalidate
        >
            <input type="hidden" name="userField" value="{$DATA.userField}">
            <input type="hidden" name="nv_seccode" value="{$DATA.nv_seccode}">
            <input type="hidden" name="checkss" value="{$DATA.checkss}">
            <input type="hidden" name="send" value="1">
            <div class="text-center fw-bold mb-3">{$DATA.info}</div>
            <div class="alert alert-info mb-3">
                {$LANG->getModule('lostpass_question')}: <strong>{$QUESTION}</strong>
            </div>
            <div class="mb-3 position-relative">
                <input type="text" class="form-control ps-with-fw-icon" name="answer" maxlength="255" value=""
                    placeholder="{$LANG->getModule('answer_question')}" aria-label="{$LANG->getModule('answer_question')}"
                    data-valid data-error-type="tooltip"
                    data-error-mess="{$LANG->getModule('answer_empty')}"
                >
                <i class="z-10 text-center fa-fw fa-solid fa-pen-to-square position-absolute top-50 start-0 ms-2 translate-middle-y"></i>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">
                    {$LANG->getModule('lostactivelink_submit')} <i class="fa-solid fa-arrow-right-long align-baseline-xs ms-1"></i>
                </button>
            </div>
        </form>
        {else}
        {* Bước 1: Tên đăng nhập hoặc email *}
        <form action="{$smarty.const.NV_BASE_SITEURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=lostactivelink" method="post" class="fw-300"
            data-precheck="nv_precheck_form" autocomplete="off" novalidate {$CAPTCHA_ATTRS}
        >
            <input type="hidden" name="checkss" value="{$DATA.checkss}">
            <div class="alert alert-info mb-3">
                {$LANG->getModule('lostactive_noactive')}<br>- {$LANG->getModule('lostactive_info1')}<br>- {$LANG->getModule('lostactive_info2')}
            </div>
            <div class="text-center fw-bold mb-3">{$DATA.info}</div>
            <div class="mb-3 position-relative">
                <input type="text" class="form-control ps-with-fw-icon" name="userField" maxlength="100" value=""
                    placeholder="{$LANG->getModule('username_or_email')}" aria-label="{$LANG->getModule('username_or_email')}"
                    minlength="3" data-valid data-error-type="tooltip"
                >
                <i class="z-10 text-center fa-fw fa-solid fa-user position-absolute top-50 start-0 ms-2 translate-middle-y"></i>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">
                    {$LANG->getModule('lostactivelink_submit')} <i class="fa-solid fa-arrow-right-long align-baseline-xs ms-1"></i>
                </button>
            </div>
        </form>
        {/if}
        {if not empty($NAVS)}
        <div class="fw-300 mx-auto mt-4 text-center">
            <ul class="list-inline mb-0">
                {foreach from=$NAVS item=nav}
                <li class="list-inline-item text-nowrap">
                    <a href="{$nav.href}">
                        <i class="fa-solid fa-caret-right"></i>&nbsp;{$nav.title}
                    </a>
                </li>
                {/foreach}
            </ul>
        </div>
        {/if}
    </div>
</div>
