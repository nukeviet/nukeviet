<script type="text/javascript" src="{$smarty.const.ASSETS_STATIC_URL}/js/clipboard/clipboard.min.js"></script>
<script type="text/javascript" src="{$smarty.const.ASSETS_STATIC_URL}/js/select2/select2.min.js"></script>
<script src="{$smarty.const.ASSETS_LANG_STATIC_URL}/js/select2/i18n/{$smarty.const.NV_LANG_INTERFACE}.js"></script>
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/flatpickr/flatpickr.min.js"></script>
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/language/flatpickr-{$smarty.const.NV_LANG_INTERFACE}.js"></script>
{if $IS_MAIN and empty($ROLE_COUNT)}
<meta http-equiv="refresh" content="5;{$ADD_API_ROLE_URL}">
<div class="alert alert-info text-center">
    {$LANG->getModule('api_roles_empty2')}
    <img src="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/images/load_bar.gif" alt="Loading" />
</div>
{elseif $IS_MAIN}
{if empty($REMOTE_API_ACCESS)}
<div class="alert alert-danger">
    {$REMOTE_API_OFF}
</div>
{/if}
<div id="credentiallist" data-page-url="{$PAGE_URL}" data-role-id="{$ROLE_ID}">
    <div class="row mb-3">
        <div class="col-sm-6">
            <div class="input-group mb-3">
                <span class="input-group-text">{$LANG->getModule('api_role')}</span>
                <select class="form-select role-id w-100">
                    <option value="-1">{$LANG->getModule('api_role_select')}</option>
                    {foreach $ROLE_LIST as $ROLE}
                    <option value="{$ROLE.role_id}" {if $ROLE.role_id == $ROLE_ID}selected="selected"{/if}>{$ROLE.role_title} ({$LANG->getModule('api_role_type')}: {$LANG->getModule('api_role_type_'|cat:$ROLE.role_type)}; {$LANG->getModule('api_role_object')}: {$LANG->getModule('api_role_object_'|cat:$ROLE.role_object)})</option>
                    {/foreach}
                </select>
            </div>
        </div>
        {if !empty($ROLE_ID)}
        <div class="col-sm-6 text-end">
            <button type="button" class="btn btn-primary mb-3" data-toggle="credential-add" data-title="{$LANG->getModule('api_role_credential_add')}">{$LANG->getModule('api_role_credential_add')}</a>
        </div>
        {/if}
    </div>
    {if !empty($ROLE_ID)}
    {if empty($CREDENTIAL_COUNT)}
    <div class="alert alert-info text-center">
        {$LANG->getModule('api_role_credential_empty')}
    </div>
    {else}
    <div class="mb-3">{$LANG->getModule('api_role_credential_count')}: {$CREDENTIAL_COUNT}</div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr class="bg-primary">
                    <th class="text-nowrap text-center" style="width: 1%;vertical-align:middle">{$LANG->getModule('api_role_credential_userid')}</th>
                    <th style="vertical-align:middle">{$LANG->getModule('api_role_credential_username')}</th>
                    <th style="vertical-align:middle">{$LANG->getModule('api_role_credential_fullname')}</th>
                    <th class="text-nowrap text-center" style="width: 1%;vertical-align:middle">{$LANG->getModule('api_role_credential_addtime')}</th>
                    <th class="text-nowrap text-center" style="width: 1%;vertical-align:middle">{$LANG->getModule('endtime')}</th>
                    <th class="text-nowrap text-center" style="width: 1%;vertical-align:middle">{$LANG->getModule('quota')}</th>
                    <th class="text-nowrap text-center" style="width: 1%;vertical-align:middle">{$LANG->getModule('api_role_credential_access_count')}</th>
                    <th class="text-nowrap text-center" style="width: 1%;vertical-align:middle">{$LANG->getModule('api_role_credential_last_access')}</th>
                    <th class="text-nowrap text-center" style="width: 1%;vertical-align:middle">{$LANG->getModule('status')}</th>
                    <th class="text-nowrap text-center" style="width: 1%;vertical-align:middle"></th>
                </tr>
            </thead>
            <tbody>
                {foreach $CREDENTIAL_LIST as $CREDENTIAL}                
                <tr class="item" data-userid="{$CREDENTIAL.userid}">
                    <td class="text-nowrap text-center" style="width: 1%;">{$CREDENTIAL.userid}</td>
                    <td>
                        {if !empty($CREDENTIAL.level)}
                        <img alt="Admin level" src="{$smarty.const.NV_BASE_SITEURL}themes/{$NV_ADMIN_THEME}/images/admin{$CREDENTIAL.level}.png" width="38" height="18" />
                        {/if}
                        {$CREDENTIAL.username}
                    </td>
                    <td>{$CREDENTIAL.fullname}</td>
                    <td class="text-nowrap text-center" style="width: 1%;">{$CREDENTIAL.addtime}</td>
                    <td class="text-nowrap text-center" style="width: 1%;">{$CREDENTIAL.endtime}</td>
                    <td class="text-nowrap text-center" style="width: 1%;">{$CREDENTIAL.quota}</td>
                    <td class="text-nowrap text-center" style="width: 1%;">{$CREDENTIAL.access_count}</td>
                    <td class="text-nowrap text-center" style="width: 1%;">{$CREDENTIAL.last_access}</td>
                    <td class="text-nowrap text-center" style="width: 1%;">
                        <select class="form-control change-status" style="width: 100px;">
                            {assign var="STATUS_L" value=[$LANG->getModule('suspended'), $LANG->getModule('active')]}
                            {foreach $STATUS_L as $K => $STATUS}
                            <option value="{$K}" {if $K == $CREDENTIAL.status}selected="selected"{/if}>{$STATUS}</option>
                            {/foreach}
                        </select>
                    </td>
                    <td class="text-nowrap text-center" style="width: 1%;">
                        <button type="button" class="btn btn-secondary" data-toggle="credential-edit" data-title="{$LANG->getModule('api_role_credential_edit')}" title="{$LANG->getGlobal('edit')}"><i class="fa fa-pencil-square-o"></i></button>
                        <button type="button" class="btn btn-secondary" data-toggle="changeAuth" title="{$LANG->getModule('authentication')}"><i class="fa fa-shield"></i></button>
                        <button type="button" class="btn btn-secondary" data-toggle="credentialDel" data-confirm="{$LANG->getModule('deprivation_confirm')}" title="{$LANG->getModule('deprivation')}"><i class="fa fa-ban"></i></button>
                    </td>
                </tr>
                {/foreach}
            </tbody>
            {if !empty($GENERATE_PAGE)}
            <tfoot>
                <tr>
                    <td colspan="8" class="text-center">
                        {$GENERATE_PAGE}
                    </td>
                </tr>
            </tfoot>
            {/if}
        </table>
    </div>
    {/if}
    {/if}
</div>
<!-- START FORFOOTER -->
<div id="credential-add" role="dialog" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title"><strong class="credential-title"></strong></div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="{$ADD_CREDENTIAL_URL}" class="form-horizontal">
                </form>
            </div>
        </div>
    </div>
</div>
<!-- END FORFOOTER -->
<!-- START FORFOOTER -->
<div id="changeAuth" role="dialog" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title"></div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="close"></button>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>
<!-- END FORFOOTER -->
{elseif $IS_AUTH}
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
                    <button class="btn btn-outline-secondary active" type="button" data-clipboard-target="#{$METHOD.key}-credential_ident" data-bs-toggle="tooltip" title="{$LANG->getModule('value_copied')}" data-bs-placement="left" data-bs-container="body" data-bs-trigger="manual" data-bs-animation="false"><i class="fa fa-copy"></i></button>
                </div>
            </div>
        </div>
        <div class="mb-3">
            <label><strong>{$LANG->getModule('api_credential_secret')}</strong></label>
            <div class="input-group">
                <input type="text" name="{$METHOD.key}_secret" id="{$METHOD.key}-credential_secret" value="" class="form-control" readonly="readonly">
                <div class="input-group-btn">
                <button class="btn btn-outline-secondary active" type="button" data-clipboard-target="#{$METHOD.key}-credential_secret" data-bs-toggle="tooltip" title="{$LANG->getModule('value_copied')}" data-bs-placement="left" data-bs-container="body" data-bs-trigger="manual" data-bs-animation="false"><i class="fa fa-copy"></i></button>
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
{elseif $IS_ADD}
{if empty($CREDENTIAL.userid)}
<input type="hidden" name="add" value="1" />
<div class="row mb-4">
    <label class="col-sm-3 col-form-label text-end">{$CREDENTIAL_ADD_LABEL}</label>
    <div class="col-sm-9">
        <select class="form-control w-100" name="userid" id="getUser" data-get-user-url="{$GET_USER_URL}" data-placeholder="{$LANG->getModule('api_role_credential_search')}">
        </select>
    </div>
</div>
{else}
<input type="hidden" name="edit" value="1" />
<input type="hidden" name="userid" value="{$CREDENTIAL.userid}" />
{/if}
<div class="row mb-4">
    <label class="col-sm-3 col-form-label text-end">{$LANG->getModule('api_role_credential_addtime')}</label>
    <div class="col-sm-9">
        <div class="input-group" style="width:fit-content">
            <input type="text" class="form-control w-50 adddate" name="adddate" value="{$CREDENTIAL.adddate}" maxlength="10" placeholder="{$LANG->getModule('api_role_credential_addtime')}" />
            <select name="addhour" class="form-control" style="width: fit-content">
                {for $I = 0 to 23}
                <option value="{$I}" {if $I == $CREDENTIAL.addhour}selected="selected"{/if}>{$I|string_format:"%'.02d"}</option>
                {/for}
            </select>
            <select name="addmin" class="form-control" style="width: fit-content">
                {for $I = 0 to 59}
                <option value="{$I}" {if $I == $CREDENTIAL.addmin}selected="selected"{/if}>{$I|string_format:"%'.02d"}</option>
                {/for}
            </select>
        </div>
        <div class="help-block mb-0">{$LANG->getModule('addtime_note')}</div>
    </div>
</div>
<div class="row mb-4">
    <label class="col-sm-3 col-form-label text-end">{$LANG->getModule('endtime')}</label>
    <div class="col-sm-9">
        <div class="input-group" style="width:fit-content">
            <input type="text" class="form-control w-50 enddate" name="enddate" value="{$CREDENTIAL.enddate}" maxlength="10" placeholder="{$LANG->getModule('endtime')}" />
            <select name="endhour" class="form-control" style="width: fit-content">
                {for $I = 0 to 23}
                <option value="{$I}" {if $I == $CREDENTIAL.endhour}selected="selected"{/if}>{$I|string_format:"%'.02d"}</option>
                {/for}
            </select>
            <select name="endmin" class="form-control" style="width: fit-content">
                {for $I = 0 to 59}
                <option value="{$I}" {if $I == $CREDENTIAL.endmin}selected="selected"{/if}>{$I|string_format:"%'.02d"}</option>
                {/for}
            </select>
        </div>
        <div class="help-block mb-0">{$LANG->getModule('endtime_note')}</div>
    </div>
</div>
<div class="row mb-4">
    <label class="col-sm-3 col-form-label text-end">{$LANG->getModule('quota')}</label>
    <div class="col-sm-9">
        <input type="text" class="form-control w100 number quota" name="quota" value="{$CREDENTIAL.quota}" maxlength="20" placeholder="{$LANG->getModule('quota')}" />
        <div class="help-block mb-0">{$LANG->getModule('quota_note')}</div>
    </div>
</div>
<div class="row">
    <div class="text-center">
        <button type="submit" class="btn btn-primary">{$LANG->getGlobal('submit')}</button>
    </div>
</div>
<!-- END: add_credential -->
{/if}
