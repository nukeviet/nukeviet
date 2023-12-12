<div class="nv-fullbg">
    <form method="post" action="{$ACTION_FILE}" data-toggle="feedback" novalidate{if $CAPTCHA == 'captcha'} data-captcha="fcode"{elseif $CAPTCHA == 'recaptcha'} data-recaptcha2="1"{elseif $CAPTCHA == 'recaptcha3'} data-recaptcha3="1"{/if}>
{if !empty($CATS)}
{$count=count($CATS)}
        <div class="form-group">
            <div class="input-group">
                <span class="input-group-addon"><em class="fa fa-folder-open fa-lg fa-fw"></em></span>
                <select class="form-control" name="fcat">
                    <option value="">{$LANG->getModule('selectCat')}</option>
{foreach $CATS as $cat}
{if $count > 1}
                    <optgroup label="{$cat.name}">
{/if}
{foreach $cat.items as $item}
                        <option value="{$item.val}">
                            {$item.name}
                        </option>
{/foreach}
{if $count > 1}
                    </optgroup>
{/if}
{/foreach}
                </select>
            </div>
        </div>
{/if}
        <div class="form-group">
            <div class="input-group">
                <span class="input-group-addon"><em class="fa fa-file-text fa-lg fa-fw"></em></span>
                <input type="text" maxlength="255" class="form-control required" value="" name="ftitle" placeholder="{$LANG->getModule('title')}" data-pattern="{literal}/^(.){3,}$/{/literal}" data-toggle="fb_validErrorHidden" data-mess="{$LANG->getModule('error_title')}" />
            </div>
        </div>

        <div class="form-group">
            <div class="input-group">
                <span class="input-group-addon"><em class="fa fa-user fa-lg fa-fw"></em></span>
                <input type="text" maxlength="100"{if $smarty.const.NV_IS_USER} value="{$CONTENT.fname}"{else} value=""{/if} name="fname" class="form-control required{if $smarty.const.NV_IS_USER} disabled{/if}"{if $smarty.const.NV_IS_USER} disabled="disabled"{/if} placeholder="{$LANG->getModule('fullname')}" data-toggle="fb_validErrorHidden" data-mess="{$LANG->getModule('error_fullname')}" data-callback="nv_uname_check" />
                {if !$smarty.const.NV_IS_USER}<span class="input-group-addon pointer" title="{$LANG->getGlobal('loginsubmit')}" data-toggle="loginForm"><em class="fa fa-sign-in fa-lg"></em></span>{/if}
            </div>
        </div>
        <div class="form-group">
            <div class="input-group">
                <span class="input-group-addon"><em class="fa fa-envelope fa-lg fa-fw"></em></span>
                <input type="email" maxlength="60"{if $smarty.const.NV_IS_USER} value="{$CONTENT.femail}"{else} value=""{/if} name="femail" class="form-control required{if $smarty.const.NV_IS_USER} disabled{/if}"{if $smarty.const.NV_IS_USER} disabled="disabled"{/if} placeholder="{$LANG->getModule('email')}" data-toggle="fb_validErrorHidden" data-mess="{$LANG->getModule('error_email')}" />
            </div>
        </div>
{if $FEEDBACK_PHONE}
        <div class="form-group">
            <div class="input-group">
                <span class="input-group-addon"><em class="fa fa-phone fa-lg fa-fw"></em></span>
                <input type="text" maxlength="60" value="{$CONTENT.fphone}" name="fphone" class="form-control{$CONTENT.phone_required}" placeholder="{$LANG->getModule('phone')}" data-pattern="{literal}/^(.){3,}$/{/literal}" data-toggle="fb_validErrorHidden" data-mess="{$LANG->getModule('phone_error')}" />
            </div>
        </div>
{/if}
{if $FEEDBACK_ADDRESS}
        <div class="form-group">
            <div class="input-group">
                <span class="input-group-addon"><em class="fa fa-home fa-lg fa-fw"></em></span>
                <input type="text" maxlength="60" value="{$CONTENT.faddress}" name="faddress" class="form-control{$CONTENT.address_required}" placeholder="{$LANG->getModule('address')}" data-pattern="{literal}/^(.){3,}$/{/literal}" data-toggle="fb_validErrorHidden" data-mess="{$LANG->getModule('address_error')}" />
            </div>
        </div>
{/if}
        <div class="form-group">
            <div>
                <textarea name="fcon" class="form-control required" style="height:130px" maxlength="1000" placeholder="{$LANG->getModule('content')}" data-toggle="fb_validErrorHidden" data-mess="{$LANG->getModule('error_content')}"></textarea>
            </div>
        </div>
{if $CONTENT.sendcopy}
        <div class="checkbox">
            <label><input type="checkbox" class="form-control" style="margin-top:2px" name="sendcopy" value="1" checked="checked" /><span>{$LANG->getModule('sendcopy')}</span></label>
        </div>
{/if}
{if $DATA_WARNING.active || $ANTISPAM_WARNING.active}
        <div class="alert alert-info confirm" style="padding:0 10px">
{if $DATA_WARNING.active}
            <div class="checkbox">
                <label>
                    <input type="checkbox" class="form-control required" style="margin-top:2px" name="data_permission_confirm" value="1" data-mess="{$LANG->getGlobal('data_warning_error')}" data-toggle="fb_errorHidden"> <small>{$DATA_WARNING.mess}</small>
                </label>
            </div>
{/if}

{if $ANTISPAM_WARNING.active}
            <div class="checkbox">
                <label>
                    <input type="checkbox" class="form-control required" style="margin-top:2px" name="antispam_confirm" value="1" data-mess="{$LANG->getGlobal('antispam_warning_error')}" data-toggle="fb_errorHidden"> <small>{$ANTISPAM_WARNING.mess}</small>
                </label>
            </div>
{/if}
        </div>
{/if}
        <div class="text-center form-group">
            <input type="hidden" name="checkss" value="{$CHECKSS}" />
            <input type="button" value="{$LANG->getModule('reset')}" class="btn btn-default" data-toggle="fb_validReset" />
            <input type="submit" value="{$LANG->getModule('sendcontact')}" class="btn btn-primary" />
        </div>
    </form>
    <div class="contact-result alert"></div>
</div>
