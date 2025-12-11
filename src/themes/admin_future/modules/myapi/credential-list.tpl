<script type="text/javascript" src="{$smarty.const.ASSETS_STATIC_URL}/js/select2/select2.min.js"></script>
<script src="{$smarty.const.ASSETS_LANG_STATIC_URL}/js/select2/i18n/{$smarty.const.NV_LANG_INTERFACE}.js"></script>
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/flatpickr/flatpickr.min.js"></script>
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/language/flatpickr-{$smarty.const.NV_LANG_INTERFACE}.js"></script>
{if empty($ROLE_COUNT)}
<meta http-equiv="refresh" content="5;{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=roles&amp;action=role">
<div class="alert alert-info text-center">
    <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
    </div><br><br>
    {$LANG->getModule('api_roles_empty2')}
</div>
{else}
{if empty($GCONFIG.remote_api_access)}
<div class="alert alert-danger">
    {$LANG->getModule('api_remote_off', $smarty.const.NV_BASE_ADMINURL|cat:'index.php?':$smarty.const.NV_LANG_VARIABLE:'=':$smarty.const.NV_LANG_DATA:'&amp;':$smarty.const.NV_NAME_VARIABLE:'=':$MODULE_NAME:'&amp;':$smarty.const.NV_OP_VARIABLE:'=config')}
</div>
{/if}
<div id="credentiallist" data-page-url="{$PAGE_URL}" data-role-id="{$ROLE_ID}" data-checkss="{$CHECKSS}">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-sm-7 col-xl-6 mb-2 mb-sm-0">
                    <div class="input-group flex-nowrap">
                        <span class="input-group-text">{$LANG->getModule('api_role')}</span>
                        <select class="form-select role-id" id="element_role_id">
                            <option value="-1">{$LANG->getModule('api_role_select')}</option>
                            {foreach $ROLE_LIST as $ROLE}
                            <option value="{$ROLE.role_id}" {if $ROLE.role_id == $ROLE_ID}selected="selected"{/if}>{$ROLE.role_title} ({$LANG->getModule('api_role_type')}: {$LANG->getModule('api_role_type_'|cat:$ROLE.role_type)}; {$LANG->getModule('api_role_object')}: {$LANG->getModule('api_role_object_'|cat:$ROLE.role_object)})</option>
                            {/foreach}
                        </select>
                    </div>
                </div>
                {if !empty($ROLE_ID)}
                <div class="col-sm-5 col-xl-6 text-sm-end">
                    <button type="button" class="btn btn-primary" data-toggle="credential-add" data-title="{$LANG->getModule('api_role_credential_add')}">{$LANG->getModule('api_role_credential_add')}</a>
                </div>
                {/if}
            </div>
        </div>
        <div class="card-body">
            {if !empty($ROLE_ID)}
            {if empty($CREDENTIAL_COUNT)}
            <div class="alert alert-info text-center mb-0">
                {$LANG->getModule('api_role_credential_empty')}
            </div>
            {else}
            <div class="mb-4">{$LANG->getModule('api_role_credential_count')}: <strong class="text-primary">{$CREDENTIAL_COUNT}</strong></div>
            <div class="table-responsive table-card">
                <table class="table table-striped align-middle mb-1">
                    <thead>
                        <tr>
                            <th class="text-nowrap" style="width: 1%;vertical-align:middle">{$LANG->getModule('api_role_credential_userid')}</th>
                            <th class="text-nowrap" style="width: 20%;vertical-align:middle">{$LANG->getModule('api_role_credential_username')}</th>
                            <th class="text-nowrap" style="width: 20%;vertical-align:middle">{$LANG->getModule('api_role_credential_fullname')}</th>
                            <th class="text-nowrap text-center" style="width: 10%;vertical-align:middle">{$LANG->getModule('api_role_credential_addtime')}</th>
                            <th class="text-nowrap text-center" style="width: 10%;vertical-align:middle">{$LANG->getModule('endtime')}</th>
                            <th class="text-nowrap text-center" style="width: 10%;vertical-align:middle">{$LANG->getModule('quota')}</th>
                            <th class="text-nowrap text-center" style="width: 4%;vertical-align:middle">{$LANG->getModule('api_role_credential_access_count')}</th>
                            <th class="text-nowrap text-center" style="width: 10%;vertical-align:middle">{$LANG->getModule('api_role_credential_last_access')}</th>
                            <th class="text-nowrap text-center" style="width: 5%;vertical-align:middle">{$LANG->getModule('status')}</th>
                            <th class="text-nowrap text-center" style="width: 20%;vertical-align:middle"></th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach $CREDENTIAL_LIST as $CREDENTIAL}
                        <tr class="item" data-userid="{$CREDENTIAL.userid}">
                            <td class="text-nowrap text-center">{$CREDENTIAL.userid}</td>
                            <td>
                                {if !empty($CREDENTIAL.level)}
                                <img alt="Admin level" src="{$smarty.const.NV_BASE_SITEURL}themes/{$GCONFIG.module_theme}/images/admin{$CREDENTIAL.level}.png" width="38" height="18">
                                {/if}
                                {$CREDENTIAL.username}
                            </td>
                            <td>{$CREDENTIAL.fullname}</td>
                            <td class="text-nowrap text-center">{$CREDENTIAL.addtime|ddatetime}</td>
                            <td class="text-nowrap text-center">{if !empty($CREDENTIAL.endtime)}{$CREDENTIAL.endtime|ddatetime}{else}{$LANG->getModule('indefinitely')}{/if}</td>
                            <td class="text-nowrap text-center">{if !empty($CREDENTIAL.quota)}{$CREDENTIAL.quota|nnum_format}{else}{$LANG->getModule('no_quota')}{/if}</td>
                            <td class="text-nowrap text-center">{$CREDENTIAL.access_count|nnum_format}</td>
                            <td class="text-nowrap text-center">{if !empty($CREDENTIAL.last_access)}{$CREDENTIAL.last_access|ddatetime}{/if}</td>
                            <td class="text-nowrap text-center">
                                <select class="form-select change-status" style="width: 120px;" name="status">
                                    {assign var="STATUS_L" value=[$LANG->getModule('suspended'), $LANG->getModule('active')]}
                                    {foreach $STATUS_L as $K => $STATUS}
                                    <option value="{$K}" {if $K == $CREDENTIAL.status}selected="selected"{/if}>{$STATUS}</option>
                                    {/foreach}
                                </select>
                            </td>
                            <td class="text-nowrap text-center">
                                <button type="button" class="btn btn-secondary" data-toggle="credential-edit" data-title="{$LANG->getModule('api_role_credential_edit')}: {$CREDENTIAL.username}" title="{$LANG->getGlobal('edit')}" aria-label="{$LANG->getGlobal('edit')}"><i class="fa-solid fa-pencil"></i></button>
                                <button type="button" class="btn btn-secondary text-primary" data-toggle="changeAuth" title="{$LANG->getModule('authentication')}" aria-label="{$LANG->getModule('authentication')}"><i class="fa fa-shield-halved"></i></button>
                                <button type="button" class="btn btn-secondary text-danger" data-toggle="credentialDel" data-confirm="{$LANG->getModule('deprivation_confirm')}" title="{$LANG->getModule('deprivation')}" aria-label="{$LANG->getModule('deprivation')}"><i class="fa-solid fa-ban"></i></button>
                            </td>
                        </tr>
                        {/foreach}
                    </tbody>
                </table>
            </div>
            {/if}
            {/if}
        </div>
        {if !empty($GENERATE_PAGE)}
        <div class="card-footer">
            <div class="d-flex flex-wrap justify-content-end align-items-center">
                <div class="pagination-wrap">{$GENERATE_PAGE}</div>
            </div>
        </div>
        {/if}
    </div>
</div>
<!-- START FORFOOTER -->
<div id="credential-add" role="dialog" class="modal fade" aria-labelledby="credential-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title" id="credential-title"><strong class="credential-title-str"></strong></div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="{$ADD_CREDENTIAL_URL}" class="form-horizontal ajax-submit">
                </form>
            </div>
        </div>
    </div>
</div>
<!-- END FORFOOTER -->
<!-- START FORFOOTER -->
<div id="changeAuth" role="dialog" class="modal fade" aria-labelledby="changeAuthTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title" id="changeAuthTitle"></div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>
<!-- END FORFOOTER -->
{/if}
