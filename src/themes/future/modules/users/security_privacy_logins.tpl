{assign var='browserIcons' value=[
    'opera' => 'fa-opera',
    'operamini' => 'fa-opera',
    'explorer' => 'fa-internet-explorer',
    'edge' => 'fa-edge',
    'firefox' => 'fa-firefox',
    'mozilla' => 'fa-firefox',
    'safari' => 'fa-safari',
    'iphone' => 'fa-safari',
    'ipod' => 'fa-safari',
    'ipad' => 'fa-safari',
    'chrome' => 'fa-chrome',
    'android' => 'fa-android'
]}
{assign var='osIcons' value=[
    'win' => 'fa-windows',
    'apple' => 'fa-apple',
    'linux' => 'fa-linux',
    'android' => 'fa-android'
]}
{foreach from=$LOGINS item=login}
{if isset($browserIcons[$login.browser_key])}{assign var='iconBrowser' value="fa-brands `$browserIcons[$login.browser_key]`"}{else}{assign var='iconBrowser' value='fa-solid fa-globe'}{/if}
{if isset($osIcons[$login.os_family])}{assign var='iconOs' value="fa-brands `$osIcons[$login.os_family]`"}{else}{assign var='iconOs' value='fa-solid fa-server'}{/if}
<div class="border rounded-3 p-3 mb-2{if $login.is_current} border-primary{/if}">
    <div class="d-flex align-items-center gap-3">
        <div class="position-relative flex-shrink-0">
            <div class="d-flex fw-40 fh-40 align-items-center justify-content-center rounded-circle bg-body-secondary">
                <i class="{$iconOs}" aria-hidden="true"></i>
            </div>
            <span class="position-absolute bottom-0 end-0 d-flex fw-20 fh-20 align-items-center justify-content-center rounded-circle border border-white bg-body-tertiary small">
                <i class="{$iconBrowser}" aria-hidden="true"></i>
            </span>
        </div>
        <div class="flex-grow-1">
            <div>
                <strong>{$login.os_name} - {$login.browser_name}</strong>
                {if $login.is_current}<span class="badge text-bg-primary">{$LANG->getModule('login_session_current')}</span>{/if}
                {if $login.is_admin}<span class="badge text-bg-success">{$LANG->getModule('login_session_admin')}</span>{/if}
            </div>
            <div class="text-muted small text-break">{$login.ip} · {$LANG->getModule('login')}: {$login.current_login_text}</div>
        </div>
        {if not $login.is_current}
        <div class="flex-shrink-0">
            <button type="button" class="btn btn-danger btn-sm text-nowrap" data-toggle="usersLoginRemove" data-idlogin="{$login.id}">
                <i class="fa-solid fa-right-from-bracket" data-icon="fa-right-from-bracket"></i> {$LANG->getGlobal('logout')}
            </button>
        </div>
        {/if}
    </div>
</div>
{/foreach}
