<div class="mb-3"><strong>{$LANG->getModule('auth_method')}</strong></div>
<ul class="nav nav-tabs mb-3" role="tablist">
    {foreach $METHODS as $METHOD}
    <li role="presentation" class="nav-item"><a class="nav-link {if $METHOD.key == 'password_verify'}active{/if}" href="#{$METHOD.key}-panel" aria-controls="{$METHOD.key}-panel" role="tab" data-bs-toggle="tab">{$METHOD.name}</a></li>
    {/foreach}
</ul>

<div class="tab-content">
    {foreach $METHODS as $METHOD}
    <div role="tabpanel" class="tab-pane{if $METHOD.key == 'password_verify'} active{/if}" id="{$METHOD.key}-panel">
        <div>
            <label><strong>{$LANG->getModule('api_credential_ident')}</strong></label>
            <div class="input-group">
                <input type="text" name="{$METHOD.key}_ident" id="{$METHOD.key}-credential_ident" value="{$METHOD.ident}" class="form-control" readonly="readonly">
                <div class="input-group-btn">
                    <button class="btn btn-secondary active" type="button" data-clipboard-target="#{$METHOD.key}-credential_ident" data-bs-toggle="tooltip" title="{$LANG->getModule('value_copied')}" data-bs-placement="left" data-bs-container="body" data-bs-trigger="manual" data-bs-animation="false"><i class="fa fa-copy"></i></button>
                </div>
            </div>
        </div>
        <div class="mb-3">
            <label><strong>{$LANG->getModule('api_credential_secret')}</strong></label>
            <div class="input-group">
                <input type="text" name="{$METHOD.key}_secret" id="{$METHOD.key}-credential_secret" value="" class="form-control" readonly="readonly">
                <div class="input-group-btn">
                <button class="btn btn-secondary active" type="button" data-clipboard-target="#{$METHOD.key}-credential_secret" data-bs-toggle="tooltip" title="{$LANG->getModule('value_copied')}" data-bs-placement="left" data-bs-container="body" data-bs-trigger="manual" data-bs-animation="false"><i class="fa fa-copy"></i></button>
                </div>
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
            <div>
                <label><strong>{$LANG->getModule('api_ips')}</strong></label>
                <textarea class="form-control ips" name="{$METHOD.key}_ips">{$METHOD.ips}</textarea>
                <div class="help-block">{$LANG->getModule('api_ips_help')}</div>
            </div>
            <div class="text-center">
                <button type="button" class="btn btn-primary api_ips_update" data-method="{$METHOD.key}" data-userid="{$USERID}">{$LANG->getModule('api_ips_update')}</button>
            </div>
        </div>
    </div>
    {/foreach}
</div>
