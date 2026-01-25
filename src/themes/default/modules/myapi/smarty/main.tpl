<script type="text/javascript" src="{$smarty.const.ASSETS_STATIC_URL}/js/clipboard/clipboard.min.js"></script>
<div id="my-role-api" data-page-url="{$PAGE_URL}">
    <div class="tools">
        <div>
            <ul class="nav nav-pills m-bottom">
                <li role="presentation"{if $TYPE=='public'} class="active"{/if}><a href="{$PAGE_URL}">{$LANG->getModule('api_role_type_public2')}</a></li>
                <li role="presentation"{if $TYPE=='private'} class="active"{/if}><a href="{$PAGE_URL}&amp;type=private">{$LANG->getModule('api_role_type_private2')}</a></li>
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
{foreach $METHODS as $method}
                                <li role="presentation"{if $method.key == 'password_verify'} class="active"{/if}><a href="#{$method.key}-panel" aria-controls="{$method.key}-panel" role="tab" data-toggle="tab">{$method.name}</a></li>
{/foreach}
                            </ul>
                            <div class="tab-content">
{foreach $METHODS as $method}
                                <div role="tabpanel" class="tab-pane{if $method.key == 'password_verify'} active{/if}" id="{$method.key}-panel">
                                    <div class="form-group">
                                        <label><strong>{$LANG->getModule('api_credential_ident')}</strong></label>
                                        <div class="input-group">
                                            <input type="text" name="{$method.key}_ident" id="{$method.key}-credential_ident" value="{$method.ident}" class="form-control bg-white" readonly="readonly">
                                            <div class="input-group-btn">
                                                <button class="btn btn-default active" type="button" data-clipboard-target="#{$method.key}-credential_ident" data-toggle="clipboard" data-title="{$LANG->getModule('value_copied')}" data-placement="left" data-container="body" data-trigger="manual" data-animation="false"><i class="fa fa-copy"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label><strong>{$LANG->getModule('api_credential_secret')}</strong></label>
                                        <div class="input-group">
                                            <input type="text" name="{$method.key}_secret" id="{$method.key}-credential_secret" value="" class="form-control bg-white" readonly="readonly">
                                            <div class="input-group-btn">
                                                <button class="btn btn-default active" type="button" data-clipboard-target="#{$method.key}-credential_secret" data-toggle="clipboard" data-title="{$LANG->getModule('value_copied')}" data-placement="left" data-container="body" data-trigger="manual" data-animation="false"><i class="fa fa-copy"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row m-bottom">
                                        <div class="col-xs-12">
                                            <button type="button" class="btn btn-primary btn-block create_authentication" data-method="{$method.key}">{$LANG->getModule('create_access_authentication')}</button>
                                        </div>
                                        <div class="col-xs-12">
                                            <button type="button" class="btn btn-danger btn-block delete_authentication" data-method="{$method.key}">{$LANG->getModule('delete_authentication')}</button>
                                        </div>
                                    </div>

                                    <div class="row m-bottom api_ips"{if $method.not_access_authentication} style="display:none"{/if}>
                                        <div class="form-group">
                                            <label><strong>{$LANG->getModule('api_ips')}</strong></label>
                                            <textarea class="form-control ips" name="{$method.key}_ips">{$method.ips}</textarea>
                                            <div class="help-block">{$LANG->getModule('api_ips_help')}</div>
                                        </div>
                                        <div class="text-center">
                                            <button type="button" class="btn btn-primary api_ips_update" data-method="{$method.key}">{$LANG->getModule('api_ips_update')}</button>
                                        </div>
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

{if empty($ROLECOUNT)}
    <div class="alert alert-info text-center">
        {$LANG->getModule('api_roles_empty')}
    </div>
{else}
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="bg-primary small">
                <tr>
                    <td class="text-nowrap text-center" style="vertical-align:middle">{$LANG->getModule('api_roles_list')}</td>
                    <td class="text-nowrap text-center" style="width: 1%;vertical-align:middle">{$LANG->getModule('api_role_status')}</td>
                    <td class="text-nowrap text-center" style="width: 1%;vertical-align:middle">{$LANG->getModule('api_role_credential_status')}</td>
                    <td class="text-nowrap text-center" style="width: 1%;vertical-align:middle">{$LANG->getModule('api_role_credential_addtime')}</td>
                    <td class="text-nowrap text-center" style="width: 1%;vertical-align:middle">{$LANG->getModule('endtime')}</td>
                    <td class="text-nowrap text-center" style="width: 1%;vertical-align:middle">{$LANG->getModule('quota')}</td>
                    <td class="text-nowrap text-center" style="width: 1%;vertical-align:middle">{$LANG->getModule('api_role_credential_access_count')}</td>
                    <td class="text-nowrap text-center" style="width: 1%;vertical-align:middle">{$LANG->getModule('api_role_credential_last_access')}</td>
                    <td class="text-nowrap text-center" style="width: 1%;vertical-align:middle"></td>
                </tr>
            </thead>
            <tbody>
{foreach $ROLELIST as $role}
{$role.credential_status = (int) $role.credential_status}
                <tr class="item{if $role.credential_status !== 1} text-muted{/if}" data-role-id="{$role.role_id}">
                    <td>
                        <strong>{$role.role_title}</strong>
{if !empty($role.role_description)}
                        <p class="description">{$role.role_description}</p>
{/if}
                    </td>
                    <td class="text-nowrap text-center" style="width: 1%;">{if !empty($role.status)}{$LANG->getModule('active')}{else}{$LANG->getModule('inactive')}{/if}</td>
                    <td class="text-nowrap text-center" style="width: 1%;">{if $role.credential_status === 1}{$LANG->getModule('activated')}{elseif $role.credential_status === 0}{$LANG->getModule('suspended')}{else}{$LANG->getModule('not_activated')}{/if}</td>
                    <td class="text-center" style="width: 1%;">{if $role.credential_addtime > 0}{$role.credential_addtime_format}{/if}</td>
                    <td class="text-center" style="width: 1%;">{if $role.credential_endtime > 0}{$role.credential_endtime_format}{elseif $role.credential_endtime == 0}{$LANG->getModule('indefinitely')}{/if}</td>
                    <td class="text-center" style="width: 1%;">{if $role.credential_quota > 0}{$role.credential_quota|string_format:"%02d"}{elseif $role.credential_quota == 0}{$LANG->getModule('no_quota')}{/if}</td>
                    <td class="text-nowrap text-center" style="width: 1%;">{if $role.credential_access_count >= 0}{$role.credential_access_count}{/if}</td>
                    <td class="text-nowrap text-center" style="width: 1%;">{if $role.credential_last_access > 0}{$role.credential_last_access_format}{/if}</td>
                    <td class="text-nowrap text-center" style="width: 1%;">
                        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#apiroledetail{$role.role_id}">{$LANG->getModule('api_roles_allowed')}</button>
                        <!-- START FORFOOTER -->
                        <div id="apiroledetail{$role.role_id}" tabindex="-1" role="dialog" class="modal fade">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" data-dismiss="modal" aria-hidden="true" class="close"><span class="fa fa-times"></span></button>
                                        <div class="modal-title"><strong>{$LANG->getModule('api_roles_detail')}: {$role.role_title}</strong></div>
                                    </div>
                                    <div class="modal-body">
{if !empty($role.apis[''])}
{foreach $role.apis[''] as $cat_data}
                                        <div class="panel panel-default">
                                            <div class="panel-heading"><strong><i class="fa fa-folder-open-o"></i> {$LANG->getModule('api_of_system')}: {$cat_data.title}</strong></div>
                                            <div class="panel-body">
                                                <div class="row">
{foreach $cat_data.apis as $api_data}
                                                    <div class="col-sm-12">
                                                        <div class="text-truncate m-bottom"><i class="fa fa-caret-right"></i> {$api_data}</div>
                                                    </div>
{/foreach}
                                                </div>
                                            </div>
                                        </div>
{/foreach}
{/if}
                                        <div>
                                            <ul class="nav nav-tabs m-bottom" role="tablist">
{foreach $SETUP_LANGS as $forlang}
                                                <li role="presentation"{if $forlang == $NV_LANG_DATA} class="active"{/if}"><a id="forlang-{$forlang}-{$role.role_id}-tab" href="#forlang-{$forlang}-{$role.role_id}" aria-controls="forlang-{$forlang}-{$role.role_id}" role="tab" data-toggle="tab" aria-expanded="{if $forlang == $NV_LANG_DATA}true{else}false{/if}">{$LANGUAGE_ARRAY.$forlang.name}</a></li>
{/foreach}
                                            </ul>
                                            <div class="tab-content">
{foreach $SETUP_LANGS as $forlang}
                                                <div role="tabpanel" class="tab-pane fade{if $forlang == $NV_LANG_DATA} in active{/if}" id="forlang-{$forlang}-{$role.role_id}" aria-labelledby="forlang-{$forlang}-{$role.role_id}-tab">
{if !empty($role.apis.$forlang)}
{foreach $role.apis.$forlang as $mod_title => $mod_data}
{foreach $mod_data as $cat_data}
                                                    <div class="panel panel-default">
                                                        <div class="panel-heading"><strong><i class="fa fa-folder-open-o"></i> {$SITE_MODS.$mod_title.custom_title}{if !empty($cat_data.title)} <i class="fa fa-angle-right"></i> {$cat_data.title}{/if}</strong></div>
                                                        <div class="panel-body">
                                                            <div class="row">
{foreach $cat_data['apis'] as $api_data}
                                                                <div class="col-sm-12">
                                                                    <div class="text-truncate m-bottom" title="{$api_data}"><i class="fa fa-caret-right"></i> {$api_data}</div>
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
{if $TYPE=='public'}
{if $role.credential_status === -1}
                        <button type="button" class="btn btn-default credential-activate">{$LANG->getModule('activate')}</button>
{elseif $role.credential_status === -1}
                        <button type="button" class="btn btn-default credential-deactivate">{$LANG->getModule('deactivate')}</button>
{/if}
{/if}
                    </td>
                </tr>
{/foreach}
            </tbody>
        </table>
{if !empty($GENERATE_PAGE)}
        <div class="text-center">
            {$GENERATE_PAGE}
        </div>
{/if}
    </div>
{/if}
</div>
