<div class="form-label">{$LANG->getModule('auth_method')}</div>
{assign var='activeMethod' value=''}
<ul class="d-none d-sm-flex nav nav-tabs mb-3" role="tablist" data-toggle="tablist">
    {foreach $METHODS as $METHOD}
    {if empty($activeMethod)}
    {assign var='activeMethod' value=$METHOD.name}
    {/if}
    <li role="presentation" class="nav-item"><a class="nav-link {if $METHOD.key == 'password_verify'}active{/if}" href="#{$METHOD.key}-panel" aria-controls="{$METHOD.key}-panel" role="tab" data-bs-toggle="tab">{$METHOD.name}</a></li>
    {/foreach}
</ul>
<div class="d-block d-sm-none mb-3">
    <div class="dropdown">
        <button class="w-100 btn btn-secondary dropdown-toggle" type="button" data-toggle="credential_auth_dropdown_btn" data-bs-toggle="dropdown" aria-expanded="false">
            {$activeMethod}
        </button>
        <ul class="dropdown-menu">
            {foreach $METHODS as $KEY => $METHOD}
            <li><a class="dropdown-item" data-toggle="credential_auth_dropdown_item" href="#{$METHOD.key}-panel">{$METHOD.name}</a></li>
            {/foreach}
        </ul>
    </div>
</div>
<div class="tab-content">
    {foreach $METHODS as $METHOD}
    <div role="tabpanel" class="tab-pane{if $METHOD.key == 'password_verify'} active{/if}" id="{$METHOD.key}-panel">
        <div class="mb-3">
            <label class="form-label" for="{$METHOD.key}-credential_ident">{$LANG->getModule('api_credential_ident')}</label>
            <div class="input-group">
                <input type="text" name="{$METHOD.key}_ident" id="{$METHOD.key}-credential_ident" value="{$METHOD.ident}" class="form-control" readonly="readonly">
                <button class="btn btn-secondary active" type="button" data-clipboard-target="#{$METHOD.key}-credential_ident" data-bs-toggle="tooltip" title="{$LANG->getModule('value_copied')}" data-bs-placement="left" data-bs-container="body" data-bs-trigger="manual" data-bs-animation="false"><i class="fa-solid fa-copy"></i></button>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label" for="{$METHOD.key}-credential_secret">{$LANG->getModule('api_credential_secret')}</label>
            <div class="input-group">
                <input type="text" name="{$METHOD.key}_secret" id="{$METHOD.key}-credential_secret" value="" class="form-control" readonly="readonly">
                <button class="btn btn-secondary active" type="button" data-clipboard-target="#{$METHOD.key}-credential_secret" data-bs-toggle="tooltip" title="{$LANG->getModule('value_copied')}" data-bs-placement="left" data-bs-container="body" data-bs-trigger="manual" data-bs-animation="false"><i class="fa-solid fa-copy"></i></button>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-6">
                <button type="button" class="btn btn-primary w-100 create_authentication" data-method="{$METHOD.key}" data-userid="{$USERID}">{$LANG->getModule('create_access_authentication')}</button>
            </div>
            <div class="col-6">
                <button type="button" class="btn btn-danger w-100 delete_authentication" data-method="{$METHOD.key}" data-userid="{$USERID}">{$LANG->getModule('delete_authentication')}</button>
            </div>
        </div>
        <div class="api_ips" {if empty($API_USER[$METHOD.key])}style="display:none"{/if}>
            <div class="mb-3">
                <label class="form-label" for="{$METHOD.key}_ips">{$LANG->getModule('api_ips')}</label>
                <textarea class="form-control ips" name="{$METHOD.key}_ips" id="{$METHOD.key}_ips">{$METHOD.ips}</textarea>
                <div class="form-text">{$LANG->getModule('api_ips_help')}</div>
            </div>
            <div class="text-center">
                <button type="button" class="btn btn-primary api_ips_update" data-method="{$METHOD.key}" data-userid="{$USERID}">{$LANG->getModule('api_ips_update')}</button>
            </div>
        </div>
    </div>
    {/foreach}
</div>
