<form method="post" action="{$ACTION_FILE}" data-toggle="ajax-form" data-precheck="nv_precheck_form" novalidate{$CAPTCHA_ATTRS}>
    {if !empty($CATS)}
    {$count=count($CATS)}
    <div class="mb-3">
        <label class="form-label" for="fcat">{$LANG->getModule('selectCat')}</label>
        <select class="form-select" name="fcat" id="fcat">
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
    {/if}

    <div class="mb-3">
        <label class="form-label" for="ftitle">{$LANG->getModule('title')} <span class="text-danger">(*)</span></label>
        <input class="form-control" type="text" name="ftitle" id="ftitle" value="{$CONTENT.ftitle|default:''}" data-valid data-error-type="feedback" data-allowed-empty="0" minlength="3" maxlength="255" placeholder="{$LANG->getModule('title')}">
        <div class="invalid-feedback">{$LANG->getModule('error_title')} {$LANG->getModule('minlength3')}</div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <label class="form-label" for="fname">{$LANG->getModule('fullname')} <span class="text-danger">(*)</span></label>
            {if !$smarty.const.NV_IS_USER}<small class="ms-2"><a href="#" role="button" title="{$LANG->getGlobal('loginsubmit')}" data-toggle="loginForm" aria-label="{$LANG->getGlobal('loginsubmit')}"><i class="fa-solid fa-right-to-bracket"></i></a></small>{/if}
            <input class="form-control" {if $smarty.const.NV_IS_USER}disabled{/if} type="text" name="fname" id="fname" value="{$CONTENT.fname|default:''}" data-valid data-error-type="feedback" minlength="3" maxlength="100" placeholder="{$LANG->getModule('fullname')}">
            <div class="invalid-feedback">{$LANG->getModule('error_fullname')} {$LANG->getModule('minlength3')}</div>
        </div>
        <div class="col-md-6">
            <label class="form-label" for="femail">{$LANG->getModule('email')} <span class="text-danger">(*)</span></label>
            <input class="form-control" {if $smarty.const.NV_IS_USER}disabled{/if} type="email" name="femail" id="femail" value="{$CONTENT.femail|default:''}" data-valid="email" data-error-type="feedback" maxlength="60" placeholder="{$LANG->getModule('email')}">
            <div class="invalid-feedback">{$LANG->getModule('error_email')}</div>
        </div>
    </div>

    {if !empty($MCONFIG.feedback_phone) or !empty($MCONFIG.feedback_address)}
    <div class="row g-3 mb-3">
        {if !empty($MCONFIG.feedback_phone)}
        <div class="{if !empty($MCONFIG.feedback_address)}col-md-6{else}col-12{/if}">
            <label class="form-label" for="fphone">{$LANG->getModule('phone')}{if $CONTENT.sender_phone_required} <span class="text-danger">(*)</span>{/if}</label>
            <input class="form-control" type="tel" name="fphone" id="fphone" value="{$CONTENT.fphone|default:''}" data-valid="phone" data-error-type="feedback" data-allowed-empty="{if $CONTENT.sender_phone_required}0{else}1{/if}" minlength="3" maxlength="20" placeholder="{$LANG->getModule('phone')}">
            <div class="invalid-feedback">{$LANG->getModule('phone_error')}</div>
        </div>
        {/if}
        {if !empty($MCONFIG.feedback_address)}
        <div class="{if !empty($MCONFIG.feedback_phone)}col-md-6{else}col-12{/if}">
            <label class="form-label" for="faddress">{$LANG->getModule('address')}{if $CONTENT.sender_address_required} <span class="text-danger">(*)</span>{/if}</label>
            <input class="form-control" type="text" name="faddress" id="faddress" value="{$CONTENT.faddress|default:''}" data-valid data-error-type="feedback" data-allowed-empty="{if $CONTENT.sender_address_required}0{else}1{/if}" minlength="3" maxlength="250" placeholder="{$LANG->getModule('address')}">
            <div class="invalid-feedback">{$LANG->getModule('address_error')} {$LANG->getModule('minlength3')}</div>
        </div>
        {/if}
    </div>
    {/if}

    <div class="mb-3">
        <label class="form-label" for="fcon">{$LANG->getModule('content')} <span class="text-danger">(*)</span></label>
        <textarea class="form-control" name="fcon" id="fcon" data-valid data-error-type="feedback" data-allowed-empty="0" minlength="3" maxlength="65535" placeholder="{$LANG->getModule('content')}" rows="5"></textarea>
        <div class="invalid-feedback">{$LANG->getModule('error_content')} {$LANG->getModule('minlength3')}</div>
    </div>

    {if !empty($CONTENT.sendcopy)}
    <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" name="sendcopy" value="1" id="sendcopy" checked>
        <label class="form-check-label" for="sendcopy">{$LANG->getModule('sendcopy')}</label>
    </div>
    {/if}

    {if !empty($GCONFIG.data_warning) || !empty($GCONFIG.antispam_warning)}
    <div class="alert alert-info confirm">
        {if !empty($GCONFIG.data_warning)}
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="data_permission_confirm" value="1" id="data_permission_confirm" data-valid="checkbox" data-min="1" data-max="1" data-error-type="feedback">
            <label class="form-check-label" for="data_permission_confirm"><small>{$GCONFIG.data_warning_content|default:$LANG->getGlobal('data_warning_content')}</small></label>
            <div class="invalid-feedback">{$LANG->getGlobal('data_warning_error')}</div>
        </div>
        {/if}
        {if !empty($GCONFIG.antispam_warning)}
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="antispam_confirm" value="1" id="antispam_confirm" data-valid="checkbox" data-min="1" data-max="1" data-error-type="feedback">
            <label class="form-check-label" for="antispam_confirm"><small>{$GCONFIG.antispam_warning_content|default:$LANG->getGlobal('antispam_warning_content')}</small></label>
            <div class="invalid-feedback">{$LANG->getGlobal('antispam_warning_error')}</div>
        </div>
        {/if}
    </div>
    {/if}

    <div class="mt-4">
        <input type="hidden" name="checkss" value="{$CHECKSS}">
        <div class="row g-2">
            <div class="col-6">
                <button type="reset" class="btn btn-outline-secondary w-100"><i class="fa-solid fa-rotate-left me-2"></i>{$LANG->getModule('reset')}</button>
            </div>
            <div class="col-6">
                <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-paper-plane me-2"></i>{$LANG->getModule('sendcontact')}</button>
            </div>
        </div>
    </div>
</form>
