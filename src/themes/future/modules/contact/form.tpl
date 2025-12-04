<form method="post" action="{if isset($ACTION_FILE)}{$ACTION_FILE}{else}{$smarty.const.NV_BASE_SITEURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$CONFIG.module}{/if}" data-toggle="ajax-form" data-precheck="nv_precheck_form" novalidate
    {if $MODULE_CAPTCHA eq 'recaptcha' and $GCONFIG.recaptcha_ver eq 3} data-recaptcha3="1"
    {elseif $MODULE_CAPTCHA eq 'recaptcha' and $GCONFIG.recaptcha_ver eq 2} data-recaptcha2="1"
    {elseif $MODULE_CAPTCHA eq 'turnstile'} data-turnstile="1"
    {elseif $MODULE_CAPTCHA eq 'captcha'} data-captcha="fcode"{/if}
>
    <div class="card bg-body-tertiary">
        <div class="card-body">

            {if !empty($CATS)}
            {$count=count($CATS)}
            <div class="mb-2">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-folder-open"></i></span>
                    <select class="form-select" name="fcat" aria-label="{$LANG->getModule('selectCat')}">
                        <option value="">{$LANG->getModule('selectCat')}</option>
                        {foreach $CATS as $cat}
                            {if $count > 1}
                            <optgroup label="{$cat.name}">
                                {foreach $cat.items as $item}
                                <option value="{$item.val}">{$item.name}</option>
                                {/foreach}
                            </optgroup>
                            {else}
                                {foreach $cat.items as $item}
                                <option value="{$item.val}">{$item.name}</option>
                                {/foreach}
                            {/if}
                        {/foreach}
                    </select>
                </div>
            </div>
            {/if}

            <div class="mb-2">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-file-lines"></i></span>
                    <input class="form-control" type="text" name="ftitle" value="" data-valid data-error-type="feedback" data-allowed-empty="0" minlength="3" maxlength="255" placeholder="{$LANG->getModule('title')}" aria-label="{$LANG->getModule('title')}">
                    <span class="input-group-text text-danger">*</span>
                </div>
                <div class="invalid-feedback">{$LANG->getModule('error_title')}</div>
            </div>

            <div class="mb-2">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                    <input class="form-control{if $smarty.const.NV_IS_USER} disabled{/if}" {if $smarty.const.NV_IS_USER}disabled="disabled"{/if} type="text" name="fname" value="{$CONTENT.fname|default:''}" data-valid data-error-type="feedback" minlength="3" maxlength="100" placeholder="{$LANG->getModule('fullname')}" aria-label="{$LANG->getModule('fullname')}">
                    {if !$smarty.const.NV_IS_USER}<span class="input-group-text pointer" title="{$LANG->getGlobal('loginsubmit')}" data-toggle="loginForm"><i class="fa-solid fa-right-to-bracket"></i></span>{/if}
                    <span class="input-group-text text-danger">*</span>
                </div>
                <div class="invalid-feedback">{$LANG->getModule('error_fullname')}</div>
            </div>

            <div class="mb-2">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                    <input class="form-control{if $smarty.const.NV_IS_USER} disabled{/if}" {if $smarty.const.NV_IS_USER}disabled="disabled"{/if} type="email" name="femail" value="{$CONTENT.femail|default:''}" data-valid data-error-type="feedback" maxlength="60" placeholder="{$LANG->getModule('email')}" aria-label="{$LANG->getModule('email')}">
                    <span class="input-group-text text-danger">*</span>
                </div>
                <div class="invalid-feedback">{$LANG->getModule('error_email')}</div>
            </div>

            {if !empty($FEEDBACK_PHONE)}
            <div class="mb-2">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-phone"></i></span>
                    <input class="form-control" type="text" name="fphone" value="{$CONTENT.fphone|default:''}" data-valid data-error-type="feedback" data-allowed-empty="{if $CONTENT.sender_phone_required}0{else}1{/if}" minlength="3" maxlength="60" placeholder="{$LANG->getModule('phone')}" aria-label="{$LANG->getModule('phone')}">
                    {if $CONTENT.sender_phone_required}<span class="input-group-text text-danger">*</span>{/if}
                </div>
                <div class="invalid-feedback">{$LANG->getModule('phone_error')}</div>
            </div>
            {/if}

            {if !empty($FEEDBACK_ADDRESS)}
            <div class="mb-2">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-house"></i></span>
                    <input class="form-control" type="text" name="faddress" value="{$CONTENT.faddress|default:''}" data-valid data-error-type="feedback" data-allowed-empty="{if $CONTENT.sender_address_required}0{else}1{/if}" minlength="3" maxlength="60" placeholder="{$LANG->getModule('address')}" aria-label="{$LANG->getModule('address')}">
                    {if $CONTENT.sender_address_required}<span class="input-group-text text-danger">*</span>{/if}
                </div>
                <div class="invalid-feedback">{$LANG->getModule('address_error')}</div>
            </div>
            {/if}

            <div class="mb-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-file"></i></span>
                    <textarea class="form-control" name="fcon" data-valid="text" data-error-type="feedback" data-allowed-empty="0" minlength="3" maxlength="1000" placeholder="{$LANG->getModule('content')}" aria-label="{$LANG->getModule('content')}" style="height:130px"></textarea>
                    <span class="input-group-text text-danger">*</span>
                </div>
                <div class="invalid-feedback">{$LANG->getModule('error_content')}</div>
            </div>

            {if $DATA_WARNING.active || $ANTISPAM_WARNING.active}
            <div class="alert alert-info confirm">
                {if $DATA_WARNING.active}
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="data_permission_confirm" value="1" id="data_permission_confirm">
                    <label class="form-check-label" for="data_permission_confirm"><small>{$DATA_WARNING.mess}</small></label>
                </div>
                {/if}
                {if $ANTISPAM_WARNING.active}
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="antispam_confirm" value="1" id="antispam_confirm">
                    <label class="form-check-label" for="antispam_confirm"><small>{$ANTISPAM_WARNING.mess}</small></label>
                </div>
                {/if}
            </div>
            {/if}

            <div class="text-center d-flex gap-2 justify-content-center">
                <input type="hidden" name="checkss" value="{if isset($CHECKSS)}{$CHECKSS}{else}{$smarty.const.NV_CHECK_SESSION}{/if}">
                <input type="hidden" name="request_form" value="{$REQUEST_FORM}">
                <button type="reset" class="btn btn-outline-secondary">{$LANG->getModule('reset')}</button>
                <button type="submit" class="btn btn-primary">{$LANG->getModule('sendcontact')}</button>
            </div>
        </div>
    </div>
</form>
