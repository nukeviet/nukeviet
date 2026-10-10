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
            <form method="get" action="{$smarty.const.NV_BASE_ADMINURL}index.php">
                <input type="hidden" name="{$smarty.const.NV_LANG_VARIABLE}" value="{$smarty.const.NV_LANG_DATA}">
                <input type="hidden" name="{$smarty.const.NV_NAME_VARIABLE}" value="{$MODULE_NAME}">
                <input type="hidden" name="{$smarty.const.NV_OP_VARIABLE}" value="{$OP}">
                {if !empty($ROLE_ID)}<input type="hidden" name="role_id" value="{$ROLE_ID}">
                <input type="hidden" name="adv" value="{$SEARCH.adv}">{/if}
                <div class="row g-2 align-items-center">
                    <div class="col-12 col-sm-5 col-md-4 col-xl-3">
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text" title="{$LANG->getModule('api_role')}"><i class="fa-solid fa-object-group"></i></span>
                            <select class="form-select role-id" id="element_role_id">
                                <option value="-1">{$LANG->getModule('api_role_select')}</option>
                                {foreach $ROLE_LIST as $ROLE}
                                <option value="{$ROLE.role_id}" {if $ROLE.role_id == $ROLE_ID}selected="selected"{/if}>{$ROLE.role_title} ({$LANG->getModule('api_role_type')}: {$LANG->getModule('api_role_type_'|cat:$ROLE.role_type)}; {$LANG->getModule('api_role_object')}: {$LANG->getModule('api_role_object_'|cat:$ROLE.role_object)})</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                    {if !empty($ROLE_ID)}
                    <div class="col">
                        <div class="input-group flex-nowrap">
                            <input type="text" class="form-control" name="q" value="{$SEARCH.qhtml}" maxlength="64" placeholder="{$LANG->getGlobal('keyword')}">
                            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i></button>
                            <button type="button" class="btn btn-secondary" title="{$LANG->getModule('search_adv')}" data-bs-toggle="collapse" data-bs-target="#credential-search-adv" aria-expanded="{if $SEARCH.adv}true{else}false{/if}" aria-controls="credential-search-adv"><i class="fa-solid fa-sliders"></i></button>
                        </div>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-success" data-toggle="credential-add" data-title="{$LANG->getModule('api_role_credential_add')}"><i class="fa-solid fa-plus"></i> <span class="d-none d-lg-inline">{$LANG->getModule('api_role_credential_add')}</span></button>
                    </div>
                    {/if}
                </div>
                {if !empty($ROLE_ID)}
                <div class="collapse{if $SEARCH.adv} show{/if} pt-3" id="credential-search-adv">
                    <div class="row g-2">
                        <div class="col-6 col-lg-4 col-xl-2">
                            <label for="cred_add_from" class="form-label text-truncate">{$LANG->getModule('api_role_credential_addtime')} {$LANG->getModule('from_date_short')}</label>
                            <div class="input-group flex-nowrap">
                                <input type="text" id="cred_add_from" name="add_from" value="{$SEARCH.addtime_from}" class="form-control cred-filter-date" maxlength="10" autocomplete="off">
                                <button class="btn btn-secondary" data-toggle="focusDate" type="button"><i class="fa-regular fa-calendar"></i></button>
                            </div>
                        </div>
                        <div class="col-6 col-lg-4 col-xl-2">
                            <label for="cred_add_to" class="form-label text-truncate">{$LANG->getModule('api_role_credential_addtime')} {$LANG->getModule('to_date_short')}</label>
                            <div class="input-group flex-nowrap">
                                <input type="text" id="cred_add_to" name="add_to" value="{$SEARCH.addtime_to}" class="form-control cred-filter-date" maxlength="10" autocomplete="off">
                                <button class="btn btn-secondary" data-toggle="focusDate" type="button"><i class="fa-regular fa-calendar"></i></button>
                            </div>
                        </div>
                        <div class="col-6 col-lg-4 col-xl-2">
                            <label for="cred_end_from" class="form-label text-truncate">{$LANG->getModule('endtime')} {$LANG->getModule('from_date_short')}</label>
                            <div class="input-group flex-nowrap">
                                <input type="text" id="cred_end_from" name="end_from" value="{$SEARCH.endtime_from}" class="form-control cred-filter-date" maxlength="10" autocomplete="off">
                                <button class="btn btn-secondary" data-toggle="focusDate" type="button"><i class="fa-regular fa-calendar"></i></button>
                            </div>
                        </div>
                        <div class="col-6 col-lg-4 col-xl-2">
                            <label for="cred_end_to" class="form-label text-truncate">{$LANG->getModule('endtime')} {$LANG->getModule('to_date_short')}</label>
                            <div class="input-group flex-nowrap">
                                <input type="text" id="cred_end_to" name="end_to" value="{$SEARCH.endtime_to}" class="form-control cred-filter-date" maxlength="10" autocomplete="off">
                                <button class="btn btn-secondary" data-toggle="focusDate" type="button"><i class="fa-regular fa-calendar"></i></button>
                            </div>
                        </div>
                        <div class="col-6 col-lg-4 col-xl-2">
                            <label for="cred_last_from" class="form-label text-truncate">{$LANG->getModule('api_role_credential_last_access')|strip_tags} {$LANG->getModule('from_date_short')}</label>
                            <div class="input-group flex-nowrap">
                                <input type="text" id="cred_last_from" name="last_from" value="{$SEARCH.last_access_from}" class="form-control cred-filter-date" maxlength="10" autocomplete="off">
                                <button class="btn btn-secondary" data-toggle="focusDate" type="button"><i class="fa-regular fa-calendar"></i></button>
                            </div>
                        </div>
                        <div class="col-6 col-lg-4 col-xl-2">
                            <label for="cred_last_to" class="form-label text-truncate">{$LANG->getModule('api_role_credential_last_access')|strip_tags} {$LANG->getModule('to_date_short')}</label>
                            <div class="input-group flex-nowrap">
                                <input type="text" id="cred_last_to" name="last_to" value="{$SEARCH.last_access_to}" class="form-control cred-filter-date" maxlength="10" autocomplete="off">
                                <button class="btn btn-secondary" data-toggle="focusDate" type="button"><i class="fa-regular fa-calendar"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                {/if}
            </form>
        </div>
        <div class="card-body">
            {if !empty($ROLE_ID)}
            {if empty($CREDENTIAL_COUNT)}
            <div class="alert alert-info text-center mb-0">
                {if $SEARCH.q or $SEARCH.adv}
                {$LANG->getModule('api_role_credential_not_found')}
                {else}
                {$LANG->getModule('api_role_credential_empty')}
                {/if}
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
