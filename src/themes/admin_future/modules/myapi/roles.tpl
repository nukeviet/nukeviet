{if !empty($IS_MAIN)}

{if empty($GCONFIG.remote_api_access)}
<div class="alert alert-danger">
    {$REMOTE_API_OFF}
</div>
{/if}
<div id="rolelist" data-page-url="{$PAGE_URL}" data-checkss="{$CHECKSS}">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="row col-sm-6">
                    <div class="col">
                        <div class="input-group">
                            <span class="input-group-text">{$LANG->getModule('api_role_type')}</span>
                            <select class="form-select role-type">
                                <option value="">{$LANG->getModule('all')}</option>
                                {foreach $TYPES as $TYPE}
                                <option value="{$TYPE}" {if $TYPE == $TYPE_API}selected="selected"{/if}>{$LANG->getModule("api_role_type_"|cat:$TYPE)}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="input-group">
                            <span class="input-group-text">{$LANG->getModule('api_role_object')}</span>
                            <select class="form-select role-object">
                                <option value="">{$LANG->getModule('all')}</option>
                                {foreach $OBJECTS as $OBJECT}
                                <option value="{$OBJECT}" {if $OBJECT == $OBJECT_API}selected="selected"{/if}>{$LANG->getModule("api_role_object_"|cat:$OBJECT)}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-6 text-end">
                    <a href="{$ADD_API_ROLE_URL}" class="btn btn-primary mb-3">{$LANG->getModule('add_role')}</a>
                </div>
            </div>
        </div>
        {if empty($ROLE_LIST)}
        <div class="card-body">
            <div class="alert alert-info text-center">
                {$LANG->getModule('api_roles_empty')}
            </div>
        </div>
        {else}
        <div class="card-body">
            <div class="table-responsive table-card">
                <table class="table table-bordered table-striped">
                    <thead class="bg-primary">
                        <tr>
                            <th class="text-nowrap text-center">{$LANG->getModule('api_roles_title')}</th>
                            <th class="text-nowrap text-center" style="width: 1%;">{$LANG->getModule('api_role_type')}</th>
                            <th class="text-nowrap text-center" style="width: 1%;">{$LANG->getModule('api_role_object')}</th>
                            <th class="text-nowrap text-center" style="width: 1%;">{$LANG->getModule('api_addtime')}</th>
                            <th class="text-nowrap text-center" style="width: 1%;">{$LANG->getModule('api_edittime')}</th>
                            <th class="text-nowrap text-center" style="width: 1%;">{$LANG->getModule('status')}</th>
                            <th class="text-nowrap text-center" style="width: 1%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach $ROLE_LIST as $ROLE}
                        <tr class="item" data-id="{$ROLE.id}">
                            <td>{$ROLE.title}</td>
                            <td class="text-nowrap text-center" style="width: 1%;">{$ROLE.type}</td>
                            <td class="text-nowrap text-center" style="width: 1%;">{$ROLE.object}</td>
                            <td class="text-nowrap text-center" style="width: 1%;">{$ROLE.addtime}</td>
                            <td class="text-nowrap text-center" style="width: 1%;">{$ROLE.edittime}</td>
                            <td class="text-nowrap text-center" style="width: 1%;">
                                <select class="form-control change-status" style="width: 100px;">
                                    {foreach [$LANG->getModule('inactive'), $LANG->getModule('active')] as $K_STATUS => $STATUS}
                                    <option value="{$K_STATUS}" {if $K_STATUS == $ROLE.status}selected="selected"{/if}>{$STATUS}</option>
                                    {/foreach}
                                </select>
                            </td>
                            <td class="text-nowrap text-center" style="width: 1%">
                                <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#apiroledetail{$ROLE.id}">{$LANG->getModule('api_roles_allowed')}</button>
                                <!-- START FORFOOTER -->
                                <div id="apiroledetail{$ROLE.id}" tabindex="-1" role="dialog" class="modal fade">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <div class="modal-title"><strong>{$LANG->getModule('api_roles_detail')}: {$ROLE.title}</strong></div>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="close"></button>
                                            </div>
                                            <div class="modal-body">                        
                                                {if !empty($ROLE.apis[''])}
                                                {foreach $ROLE.apis[''] as $CAT_DATA}
                                                <div class="card mb-3 border">
                                                    <div class="card-header api-header"><strong><i class="fa fa-folder-open-o"></i> {$LANG->getModule('api_of_system')}: {$CAT_DATA.title}</strong></div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            {foreach $CAT_DATA.apis as $API_DATA}
                                                            <div class="col-sm-6">
                                                                <div class="text-truncate mb-3"><i class="fa fa-caret-right"></i> {$API_DATA}</div>
                                                            </div>
                                                            {/foreach}
                                                        </div>
                                                    </div>
                                                </div>
                                                {/foreach}
                                                {/if}
                                                {assign var='FORLANGS' value=[]}
                                                {foreach $GCONFIG.setup_langs as $KEY_LANG => $_LG}
                                                    {if $_LG == $smarty.const.NV_LANG_DATA}
                                                        {append var='FORLANGS' value=['active' => 'active', 'in' => ' in active show', 'expanded' => 'true', 'langkey' => $_LG, 'langname' => $LANGUAGE_ARRAY[$_LG].name] index=$_LG}
                                                    {else}
                                                        {append var='FORLANGS' value=['active' => '', 'in' => '', 'expanded' => 'false', 'langkey' => $_LG, 'langname' => $LANGUAGE_ARRAY[$_LG].name] index=$_LG}
                                                    {/if}
                                                {/foreach}
                                                <div>
                                                    <ul class="nav nav-tabs mb-3" role="tablist">
                                                        {foreach $FORLANGS as $FORLANG}
                                                        <li role="presentation" class="nav-item"><a id="forlang-{$FORLANG.langkey}-{$ROLE.id}-tab" href="#forlang-{$FORLANG.langkey}-{$ROLE.id}" class="nav-link {$FORLANG.active}" aria-controls="forlang-{$FORLANG.langkey}-{$ROLE.id}" role="tab" data-bs-toggle="tab" aria-expanded="{$FORLANG.expanded}">{$FORLANG.langname}</a></li>
                                                        {/foreach}
                                                    </ul>
                                                    <div class="tab-content">
                                                        {foreach $FORLANGS as $_LG => $FORLANG}
                                                        <div role="tabpanel" class="tab-pane fade{$FORLANG.in}" id="forlang-{$FORLANG.langkey}-{$ROLE.id}" aria-labelledby="forlang-{$FORLANG.langkey}-{$ROLE.id}-tab">
                                                            {if !empty($ROLE.apis.$_LG)}
                                                            {foreach $ROLE.apis.$_LG as $MOD_TITLE => $MOD_DATA}
                                                            {foreach $MOD_DATA as $CAT_DATA}
                                                            <div class="card mb-3 border">
                                                                <div class="card-header api-header"><strong><i class="fa fa-folder-open-o"></i> {$SITE_MOD.$MOD_TITLE.custom_title}
                                                                        {if !empty($CAT_DATA.title)}
                                                                        <i class="fa fa-angle-right"></i> {$CAT_DATA.title}
                                                                        {/if}
                                                                    </strong></div>
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        {foreach $CAT_DATA.apis as $API_DATA}
                                                                        <div class="col-sm-6">
                                                                            <div class="text-truncate mb-3" title="{$API_DATA}"><i class="fa fa-caret-right"></i> {$API_DATA}</div>
                                                                        </div>
                                                                        {/foreach}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            {/foreach}
                                                            {/foreach}
                                                            {/if}
                                                        </div>
                                                        {/foreach}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END FORFOOTER -->
                                <a href="{$ADD_API_ROLE_URL}&amp;id={$ROLE.id}" class="btn btn-secondary"><i class="fa fa-pencil"></i> {$LANG->getGlobal('edit')}</a>
                                <button type="button" class="btn btn-secondary" data-toggle="apiroledel"><i class="fa fa-trash-o"></i> {$LANG->getGlobal('delete')}</button>
                            </td>
                        </tr>
                        {/foreach}
                    </tbody>
                </table>
            </div>
        </div>
        {if !empty($GENERATE_PAGE)}
        <div class="card-footer border-top">
            <div class="d-flex flex-wrap justify-content-end align-items-center">
                {$GENERATE_PAGE}
            </div>
        </div>
        {/if}
    </div>
    {/if}
</div>
{elseif !empty($IS_ROLE)}
<form method="post" class="row g-3 ajax-submit" action="{$FORM_ACTION}" autocomplete="off" id="role" novalidate>
    <div class="col-12">
        <div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0">
            <div class="card-header py-2">
                <div class="hstack gap-2 align-items-center justify-content-between">
                    <div class="fw-medium fs-5">{$LANG->getModule('api_role_properties')}</div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <label for="role_title" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('api_roles_title')} <span class="text-danger">*</span>:</label>
                    <div class="col-sm-8 col-lg-6 col-xxl-5">
                        <input type="text" id="role_title" name="role_title" value="{$DATA.role_title}" class="form-control w350" maxlength="250" required />
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="role_description" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('api_roles_description')}</label>
                    <div class="col-sm-8 col-lg-6 col-xxl-5">
                        <textarea class="form-control w350" id="role_description" name="role_description" rows="2" maxlength="250">{$DATA.role_description}</textarea>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('api_role_type')}</label>
                    <div class="col-sm-8 col-lg-6 col-xxl-5 pt-2">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" value="private" name="role_type" id="role_type_private" {$DATA.role_type_private_checked}>
                            <label class="form-check-label" for="role_type_private"> {$LANG->getModule('api_role_type_private')}</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" value="public" name="role_type" id="role_type_public" {$DATA.role_type_public_checked}>
                            <label class="form-check-label" for="role_type_public"> {$LANG->getModule('api_role_type_public')}</label>
                        </div>
                        <div class="form-text">
                            <ul class="role_note note">
                                <li>{$LANG->getModule('api_role_type_private_note')}</li>
                                <li>{$LANG->getModule('api_role_type_public_note')}</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('api_role_object')}</label>
                    <div class="col-sm-8 col-lg-6 col-xxl-5 pt-2">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" value="private" name="role_object" id="role_object_admin" {$DATA.role_object_admin_checked}>
                            <label class="form-check-label" for="role_object_admin"> {$LANG->getModule('api_role_object_admin')}</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" value="public" name="role_object" id="role_object_user" {$DATA.role_object_user_checked}>
                            <label class="form-check-label" for="role_object_user"> {$LANG->getModule('api_role_object_user')}</label>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('log_period')}</label>
                    <div class="col-sm-8 col-lg-6 col-xxl-5">
                        <div class="input-group">
                            <input type="text" class="form-control number" name="log_period" value="{$DATA.log_period}" maxlength="10" style="width: 100px;">
                            <span class="input-group-text border-start-0">{$LANG->getModule('hours')}</span>
                        </div>
                        <div class="form-text">{$LANG->getModule('log_period_note')}</div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('flood_blocker')}</label>
                    <div class="col-sm-8 col-lg-6 col-xxl-5">
                        <div class="items">
                            {if empty($DATA.flood_rules)}
                                {append var='DATA' value=['' => ''] index='flood_rules'}
                            {/if}
                            {foreach $DATA.flood_rules as $INTERVAL => $LIMIT}
                            <div class="flood_rule item mb-2">
                                <div class="input-group" style="width: fit-content;">
                                    <span class="input-group-text">{$LANG->getModule('flood_limit')}</span>
                                    <input type="text" class="form-control number" name="flood_rules_limit[]" value="{$LIMIT}" maxlength="15" style="width: 100px;">
                                    <span class="input-group-text border-start-0">{$LANG->getModule('flood_interval')}</span>
                                    <input type="text" class="form-control number border-start-0" style="width: 100px;" name="flood_rules_interval[]" value="{if !empty($INTERVAL)}{math equation="round(x / y)" x=$INTERVAL y=60}{/if}" maxlength="10">
                                    <span class="input-group-text border-start-0">{$LANG->getModule('minutes')}</span>
                                    <span class="input-group-btn">
                                        <button class="btn btn-default del-rule" type="button"><em class="fa fa-minus"></em></button>
                                        <button class="btn btn-default add-rule" type="button"><em class="fa fa-plus"></em></button>
                                    </span>
                                </div>
                            </div>
                            {/foreach}
                        </div>
                        <div class="form-text">{$LANG->getModule('flood_blocker_note')}</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-8 offset-sm-3 d-flex justify-content-sm-start justify-content-center">
                        <input type="hidden" name="checkss" value="{$CHECKSS}">
                        <button type="submit" class="btn btn-primary">{$LANG->getGlobal('submit')}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0">
            <div class="card-header py-2">
                <div class="hstack gap-2 align-items-center justify-content-between">
                    <div class="fw-medium fs-5">{$LANG->getModule('apis_list')}</div>
                </div>
            </div>
            <div id="apicheck" class="card-body">
                <div id="role">
                    {$APICHECK}
                </div>
            </div>
            <div class="card-footer border-top">
                <div class="row gy-3">
                    <div class="col-sm-6">
                        <select name="save" class="form-select" style="display:inline-block;">
                            {foreach $SAVEOPTS as $KEY => $NAME}
                            <option value="{$KEY}">{$NAME}</option>
                            {/foreach}
                        </select>
                    </div>
                    <div class="col-sm-6 d-flex justify-content-sm-start justify-content-center">
                        <button type="submit" class="btn btn-primary">{$LANG->getGlobal('submit')}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
{elseif !empty($IS_API)}
<div class="row mb-2">
    <div class="col-sm-3 text-sm-end">
        {$LANG->getModule('api_roles_allowed')}: <span class="total-api-enabled api-count{$TOTAL_API_CHECKED}">{$TOTAL_API_ENABLED}</span>
    </div>
</div>
<div class="row mb-3">
    <div class="root-api-actions col-sm-3 mb-3 mb-sm-0">
        <ul class="nav nav-pills flex-column">
            {assign var='COUNT_API' value=0}
            {foreach $API_TREES as $API_TREE}
            <li role="presentation" class="nav-item"><a role="tab" data-bs-toggle="tab" data-bs-cat="{$API_TREE.key}" href="#{$API_TREE.href}" aria-controls="{$API_TREE.href}" aria-expanded="{$API_TREE.expanded}" class="main nav-link{if !empty($API_TREE['active'])} active{/if} border {if $COUNT_API > 0} border-top-0{/if}"><i class="fa fa-folder-open-o"></i>&nbsp;{$API_TREE.name}
                {if !empty($API_TREE.total)}
                <span class="api-count{$API_TREE.api_checked}"><span class="total_api">{$API_TREE.total_api}</span>/{$API_TREE.total}</span>
                {/if}
                </a></li>
            {foreach $API_TREE.subs as $SUB}
            <li role="presentation" class="nav-item"><a role="tab" data-bs-toggle="tab" data-bs-cat="{$SUB.key}" href="#{$SUB.href}" aria-controls="api-child-{$SUB.key}" aria-expanded="{$SUB.expanded}" class="sub nav-link{if !empty($SUB['active'])} active{/if} border border-top-0">{$SUB.name}
                    <span class="api-count{$SUB.api_checked}"><span class="total_api">{$SUB.total_api}</span>/{$SUB.total}</span>
                </a></li>
            {/foreach}
            {assign var='COUNT_API' value=$COUNT_API + 1}
            {/foreach}
        </ul>
    </div>
    <div class="col-sm-8 tab-content child-apis">
        {foreach $API_CONTENTS as $API_CONTENT}
        <div role="tabpanel" class="tab-pane child-apis-item{if !empty($API_CONTENT.active)} active{/if}" id="{$API_CONTENT.id}">
            <table class="table table-bordered">
                <tbody>
                    <tr class="apilist">
                        <th style="width: 1%;"><input type="checkbox" class="form-check-input checkall" title="{$LANG->getModule('api_roles_checkall')}" {$API_CONTENT.checkall} /></th>
                        <th>{$LANG->getModule('cat_api_list')}</th>
                    </tr>
                    {foreach $API_CONTENT.apis as $API}
                    <tr class="item">
                        <td style="width: 1%;"><input type="checkbox" class="form-check-input checkitem" name="api_{$API_CONTENT.input_key}[]" id="api_{$API.cmd}" value="{$API.cmd}" {if !empty($API.checked)}checked="checked"{/if} /></td>
                        <td><label for="api_{$API.cmd}" class="pointer mb-0">{$API.cmd} - {$API.name}</label></td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
        {/foreach}
        <div role="tabpanel" class="tab-pane" id="empty-content"></div>
    </div>
</div>
{/if}
