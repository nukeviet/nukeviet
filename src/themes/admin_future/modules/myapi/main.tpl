<!-- BEGIN: main -->
<script type="text/javascript" src="{$smarty.const.ASSETS_STATIC_URL}/js/clipboard/clipboard.min.js"></script>
<div id="my-role-api" data-page-url="{$PAGE_URL}">
    <div class="tools">
        <div class="mb-4">
            <ul class="nav nav-pills">
                <li class="nav-item"><a class="nav-link {$TYPE_PUBLIC.active}" href="{$TYPE_PUBLIC.url}">{$TYPE_PUBLIC.name}</a></li>
                <li class="nav-item"><a class="nav-link {$TYPE_PRIVATE.active}" href="{$TYPE_PRIVATE.url}">{$TYPE_PRIVATE.name}</a></li>
            </ul>
        </div>
        <div>
            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#credential_auth"><i class="fa fa-shield fa-lg text-danger"></i> {$LANG->getModule('authentication')}</button>
            <!-- START FORFOOTER -->
            <div id="credential_auth" tabindex="-1" role="dialog" class="modal fade">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" data-dismiss="modal" aria-hidden="true" class="close"><span class="fa fa-times"></span></button>
                            <div class="modal-title"><strong>{$LANG->getModule('authentication')}</strong></div>
                        </div>
                        <div class="modal-body">
                            <div class="m-bottom"><strong>{$LANG->getModule('auth_method')}</strong></div>
                            <ul class="nav nav-tabs m-bottom" role="tablist">
                                {foreach $METHODS as $KEY => $METHOD}
                                <li role="presentation" class="{$METHOD.active}"><a href="#{$METHOD.key}-panel" aria-controls="{$METHOD.key}-panel" role="tab" data-toggle="tab">{$METHOD.name}</a></li>
                                {/foreach}
                            </ul>

                            <div class="tab-content">
                                {foreach $METHODS as $KEY => $METHOD}
                                <div role="tabpanel" class="tab-pane {$METHOD.active}" id="{$METHOD.key}-panel">
                                    <div class="form-group">
                                        <label><strong>{$LANG->getModule('api_credential_ident')}</strong></label>
                                        <div class="input-group">
                                            <input type="text" name="{$METHOD.key}_ident" id="{$METHOD.key}-credential_ident" value="{$METHOD.ident}" class="form-control bg-white" readonly="readonly">
                                            <div class="input-group-btn">
                                                <button class="btn btn-default active" type="button" data-clipboard-target="#{$METHOD.key}-credential_ident" data-toggle="clipboard" data-title="{$LANG->getModule('value_copied')}" data-placement="left" data-container="body" data-trigger="manual" data-animation="false"><i class="fa fa-copy"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label><strong>{$LANG->getModule('api_credential_secret')}</strong></label>
                                        <div class="input-group">
                                            <input type="text" name="{$METHOD.key}_secret" id="{$METHOD.key}-credential_secret" value="" class="form-control bg-white" readonly="readonly">
                                            <div class="input-group-btn">
                                                <button class="btn btn-default active" type="button" data-clipboard-target="#{$METHOD.key}-credential_secret" data-toggle="clipboard" data-title="{$LANG->getModule('value_copied')}" data-placement="left" data-container="body" data-trigger="manual" data-animation="false"><i class="fa fa-copy"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    {if !empty($smarty.const.NV_IS_SPADMIN or $KEY == 'password_verify' or $KEY == 'md5_verify')}
                                    <div class="row m-bottom">
                                        <div class="col-xs-12">
                                            <button type="button" class="btn btn-primary btn-block create_authentication" data-method="{$METHOD.key}">{$LANG->getModule('create_access_authentication')}</button>
                                        </div>
                                        <div class="col-xs-12">
                                            <button type="button" class="btn btn-danger btn-block delete_authentication" data-method="{$METHOD.key}">{$LANG->getModule('delete_authentication')}</button>
                                        </div>
                                    </div>
                                    {/if}

                                    <div class="api_ips"{if $METHOD.not_access_authentication}style="display:none"{/if}>
                                        <div class="form-group">
                                            <label><strong>{$LANG->getModule('api_ips')}</strong></label>
                                            <textarea class="form-control ips" name="{$METHOD.key}_ips">{$METHOD.ips}</textarea>
                                            <div class="help-block">{$LANG->getModule('api_ips_help')}</div>
                                        </div>
                                        {if !empty($smarty.const.NV_IS_SPADMIN or $KEY == 'password_verify' or $KEY == 'md5_verify')}
                                        <div class="text-center">
                                            <button type="button" class="btn btn-primary api_ips_update" data-method="{$METHOD.key}">{$LANG->getModule('api_ips_update')}</button>
                                        </div>
                                        {/if}
                                    </div>
                                </div>
                                {/foreach}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END FORFOOTER -->
        </div>
    </div>

    {if empty($GCONFIG.remote_api_access)}
    <div class="alert alert-danger">
        {$LANG->getModule('api_remote_off2')}
    </div>
    {/if}

    {if empty($ROLE_COUNT)}
    <div class="alert alert-info text-center">
        {$LANG->getModule('api_roles_empty')}
    </div>
    {else}
    <!-- END: role_empty -->
    <!-- BEGIN: rolelist -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive table-card">
                <table class="table table-striped align-middle table-sticky mb-0">
                    <thead class="text-muted tableFloatingHeaderOriginal">
                        <tr>
                            <th class="text-nowrap text-center" style="vertical-align:middle">{$LANG->getModule('api_roles_list')}</th>
                            <th class="text-nowrap text-center" style="width: 1%;vertical-align:middle">{$LANG->getModule('api_role_object')}</th>
                            <th class="text-nowrap text-center" style="width: 1%;vertical-align:middle">{$LANG->getModule('api_role_status')}</th>
                            <th class="text-nowrap text-center" style="width: 1%;vertical-align:middle">{$LANG->getModule('api_role_credential_status')}</th>
                            <th class="text-nowrap text-center" style="width: 1%;vertical-align:middle">{$LANG->getModule('api_role_credential_addtime')}</th>
                            <th class="text-nowrap text-center" style="width: 1%;vertical-align:middle">{$LANG->getModule('endtime')}</th>
                            <th class="text-nowrap text-center" style="width: 1%;vertical-align:middle">{$LANG->getModule('quota')}</th>
                            <th class="text-nowrap text-center" style="width: 1%;vertical-align:middle">{$LANG->getModule('api_role_credential_access_count')}</th>
                            <th class="text-nowrap text-center" style="width: 1%;vertical-align:middle">{$LANG->getModule('api_role_credential_last_access')}</th>
                            <th class="text-nowrap text-center" style="width: 1%;vertical-align:middle"></th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach $ROLE_LIST as $ROLE}
                        <!-- BEGIN: role -->
                        <tr class="item{if $ROLE.credential_status !== 1} text-muted{/if}" data-role-id="{$ROLE.role_id}">
                            <td>
                                <strong>{$ROLE.role_title}</strong>
                                {if !empty($ROLE.role_description)}
                                <!-- BEGIN: description -->
                                <p class="description">{$ROLE.role_description}</p>
                                <!-- END: description -->
                                {/if}
                            </td>
                            <td class="text-nowrap text-center" style="width: 1%;">{$ROLE.object}</td>
                            <td class="text-nowrap text-center" style="width: 1%;">{$ROLE.status}</td>
                            <td class="text-nowrap text-center" style="width: 1%;">{$ROLE.credential_status_format}</td>
                            <td class="text-center" style="width: 1%;">{$ROLE.credential_addtime}</td>
                            <td class="text-center" style="width: 1%;">{$ROLE.credential_endtime}</td>
                            <td class="text-center" style="width: 1%;">{$ROLE.credential_quota}</td>
                            <td class="text-nowrap text-center" style="width: 1%;">{$ROLE.credential_access_count}</td>
                            <td class="text-nowrap text-center" style="width: 1%;">{$ROLE.credential_last_access}</td>
                            <td class="text-nowrap text-center" style="width: 1%;">
                                <button type="button" class="btn btn-default" data-toggle="modal" data-target="#apiroledetail{$ROLE.role_id}">{$LANG->getModule('api_roles_allowed')}</button>
                                <!-- START FORFOOTER -->
                                <div id="apiroledetail{$ROLE.role_id}" tabindex="-1" role="dialog" class="modal fade">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" data-dismiss="modal" aria-hidden="true" class="close"><span class="fa fa-times"></span></button>
                                                <div class="modal-title"><strong>{$LANG->getModule('api_roles_detail')}: {$ROLE.role_title}</strong></div>
                                            </div>
                                            <div class="modal-body">
                                                {assign var = 'empty_key' value = ''}
                                                {if !empty($ROLE.apis.$empty_key)}
                                                {foreach $ROLE.apis.$empty_key as $CATSYS}
                                                <!-- BEGIN: catsys -->
                                                <div class="panel panel-default">
                                                    <div class="panel-heading"><strong><i class="fa fa-folder-open-o"></i> {$LANG->getModule('api_of_system')}: {$CAT_DATA.title}</strong></div>
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            {foreach $CAT_SYS.apis as $API_DATA}
                                                            <!-- BEGIN: loop -->
                                                            <div class="col-sm-12">
                                                                <div class="text-truncate m-bottom"><i class="fa fa-caret-right"></i> {$API_DATA}</div>
                                                            </div>
                                                            <!-- END: loop -->
                                                            {/foreach}
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- END: catsys -->
                                                {/foreach}
                                                {/if}
        
                                                <div>
                                                    <ul class="nav nav-tabs m-bottom" role="tablist">
                                                        <!--
                                                        $xtpl->assign('FORLANG', [
                                                //             'active' => $_lg == NV_LANG_DATA ? 'active' : '',
                                                //             'in' => $_lg == NV_LANG_DATA ? ' in active' : '',
                                                //             'expanded' => $_lg == NV_LANG_DATA ? 'true' : 'false',
                                                //             'langkey' => $_lg,
                                                //             'langname' => $language_array[$_lg]['name']
                                                //         ]);
                                                        -->
                                                        {assign var='FORLANGS' value=[]}
                                                        {foreach $GCONFIG.setup_langs as $KEY_LANG => $_LG}
                                                            {if $_LG == $smarty.const.NV_LANG_DATA}
                                                                {append var='FORLANGS' value=['active' => 'active', 'in' => ' in active', 'expanded' => 'true', 'langkey' => $_LG, 'langname' => $LANGUAGE_ARRAY[$_LG].name] index=$_LG}
                                                            {else}
                                                                {append var='FORLANGS' value=['active' => '', 'in' => '', 'expanded' => 'false', 'langkey' => $_LG, 'langname' => $LANGUAGE_ARRAY[$_LG].name] index=$_LG}
                                                            {/if}
                                                        {/foreach}
                                                        {foreach $FORLANGS as $FORLANG}
                                                        <!-- BEGIN: forlang -->
                                                        <li role="presentation" class="{$FORLANG.active}"><a id="forlang-{$FORLANG.langkey}-{$ROLE.id}-tab" href="#forlang-{$FORLANG.langkey}-{$ROLE.id}" aria-controls="forlang-{$FORLANG.langkey}-{$ROLE.id}" role="tab" data-toggle="tab" aria-expanded="{$FORLANG.expanded}">{$FORLANG.langname}</a></li>
                                                        <!-- END: forlang -->
                                                        {/foreach}
                                                    </ul>
                                                    <div class="tab-content">
                                                        {foreach $FORLANGS as $_LG => $FORLANG}
                                                        <!-- BEGIN: tabcontent_forlang -->
                                                        <div role="tabpanel" class="tab-pane fade{$FORLANG.in}" id="forlang-{$FORLANG.langkey}-{$ROLE.id}" aria-labelledby="forlang-{$FORLANG.langkey}-{$ROLE.id}-tab">
                                                            {if !empty($ROLE.apis.$_LG)}
                                                            <!-- BEGIN: apimod -->
                                                            <!-- BEGIN: mod -->
                                                            <!--  foreach ($role['apis'][$_lg] as $mod_title => $mod_data) { -->
                                                            {foreach $ROLE.apis.$_LG as $MOD_TITLE => $MOD_DATA}
                                                            <!-- foreach ($mod_data as $cat_data) { -->
                                                            {foreach $MOD_DATA as $CAT_DATA}
                                                            <div class="panel panel-default">
                                                                <div class="panel-heading"><strong><i class="fa fa-folder-open-o"></i> {$MOD_TITLE}
                                                                        {if !empty($CAT_DATA.title)}
                                                                        <!-- BEGIN: title --> <i class="fa fa-angle-right"></i> {$CAT_DATA.title}
                                                                        <!-- END: title -->
                                                                        {/if}
                                                                    </strong></div>
                                                                <div class="panel-body">
                                                                    <div class="row">
                                                                        <!-- foreach ($cat_data['apis'] as $api_data) { -->
                                                                        {foreach $CAT_DATA.apis as $API_DATA}
                                                                        <!-- BEGIN: loop -->
                                                                        <div class="col-sm-12">
                                                                            <div class="text-truncate m-bottom" title="{$API_DATA}"><i class="fa fa-caret-right"></i> {$API_DATA}</div>
                                                                        </div>
                                                                        <!-- END: loop -->
                                                                        {/foreach}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            {/foreach}
                                                            {/foreach}
                                                            <!-- END: mod -->
                                                            <!-- END: apimod -->
                                                            {/if}
                                                        </div>
                                                        <!-- END: tabcontent_forlang -->
                                                        {/foreach}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END FORFOOTER -->
                                {if $TYPE == 'public'}
                                <!-- BEGIN: is_public -->
                                {if $ROLE.credential_status == 1}
                                <!-- BEGIN: activate -->
                                <button type="button" class="btn btn-default credential-activate">{$LANG->getModule('activate')}</button>
                                <!-- END: activate -->
                                {elseif $ROLE.credential_status == -1}
                                <!-- BEGIN: deactivate -->
                                <button type="button" class="btn btn-default credential-deactivate">{$LANG->getModule('deactivate')}</button>
                                <!-- END: deactivate -->
                                {/if}
                                <!-- END: is_public -->
                                {/if}
                            </td>
                        </tr>
                        <!-- END: role -->
                        {/foreach}
                    </tbody>
                </table>
                <!-- BEGIN: generate_page -->
                {if !empty($GENERATE_PAGE)}
                <div class="text-center">
                    {$GENERATE_PAGE}
                </div>
                {/if}
                <!-- END: generate_page -->
            </div>
        </div>
    </div>
    <!-- END: rolelist -->
    {/if}
</div>
<!-- END: main -->
