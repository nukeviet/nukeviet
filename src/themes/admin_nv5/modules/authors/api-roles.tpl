{if empty($ARRAY)}
<div role="alert" class="alert alert-primary alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="fas fa-info-circle"></i></div>
    <div class="message">{$LANG->get('api_roles_empty')}.</div>
</div>
{else}

{/if}
<div id="addeditarea">
    {if not $IS_SUBMIT_FORM}
    <div role="alert" class="alert alert-primary alert-dismissible">
        <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
        <div class="icon"><i class="fas fa-info-circle"></i></div>
        <div class="message">{$LANG->get('api_role_notice_lang')}</div>
    </div>
    {/if}
    {if not empty($ERROR)}
    <div role="alert" class="alert alert-danger alert-dismissible">
        <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
        <div class="icon"><i class="far fa-times-circle"></i></div>
        <div class="message">{$ERROR}</div>
    </div>
    {/if}
    <div class="card card-border-color card-border-color-primary">
        <div class="card-header card-header-divider">
            {if $ROLE_ID}{$LANG->get('api_roles_edit')}{else}{$LANG->get('api_roles_add')}{/if}
        </div>
        <div class="card-body">
            <form method="post" action="{$FORM_ACTION}" autocomplete="off">
                <div class="form-group row">
                    <label class="col-12 col-sm-3 col-form-label text-sm-right" for="role_title">{$LANG->get('api_roles_title')} <i class="text-danger">(*)</i></label>
                    <div class="col-12 col-sm-8 col-lg-6">
                        <input type="text" class="form-control form-control-sm" id="role_title" name="role_title" value="{$DATA.role_title}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-12 col-sm-3 col-form-label text-sm-right" for="role_description">{$LANG->get('api_roles_description')}</label>
                    <div class="col-12 col-sm-8 col-lg-6">
                        <textarea type="text" class="form-control" id="role_description" name="role_description" rows="2">{$DATA.role_description}</textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-sm-8 col-lg-6 offset-sm-3">
                        <h4 class="mb-0" id="apiRoleAll">{$LANG->get('api_roles_allowed')}{if $TOTAL_API_ENABLED} <span class="text-danger">{$TOTAL_API_ENABLED}</span>{/if}</h4>
                    </div>
                </div>
                <div class="card-divider"></div>
                <div class="form-group row">
                    <div class="col-12 col-sm-3">
                        <div class="root-api-actions">
                            <ul>
                                {foreach from=$ARRAY_API_TREES item=apilev1}
                                <li><a data-toggle="apicat" data-cat="{$apilev1.key}" href="#api-child-{$apilev1.key}"{if $apilev1.active} class="active"{/if}>{$apilev1.name}{if $apilev1.total_api} <span>({$apilev1.total_api})</span> {/if}</a></li>
                                {foreach from=$apilev1.subs item=apilev2}
                                <li><a data-toggle="apicat" data-cat="{$apilev2.key}" href="#api-child-{$apilev2.key}"{if $apilev2.active} class="active"{/if}> &nbsp; &nbsp; {$apilev2.name}{if $apilev2.total_api} <span>({$apilev2.total_api})</span> {/if}</a></li>
                                {/foreach}
                                {/foreach}
                            </ul>
                        </div>
                    </div>
                    <div class="col-12 col-sm-9">
                        <div class="child-apis">
                            <div class="panel-body">
                                {foreach from=$ARRAY_API_CONTENTS item=apicontent}
                                <div data-toggle="apichid" class="child-apis-item" id="api-child-{$apicontent.key}"{if $apicontent.active} style="display: block;"{/if}>
                                    <div class="child-apis-item-ctn">
                                        <div class="row">
                                            {foreach from=$apicontent.apis item=api}
                                            <div class="col-12 col-sm-6">
                                                <label class="custom-control custom-checkbox my-1">
                                                    <input data-toggle="apiroleit" class="custom-control-input" type="checkbox" name="api_{$apicontent.key}[]" value="{$api.cmd}"{if $api.checked} checked="checked"{/if}><span class="custom-control-label">{$api.name}</span>
                                                </label>
                                            </div>
                                            {/foreach}
                                        </div>
                                    </div>
                                    <div class="child-apis-item-tool">
                                        <hr />
                                        <ul class="list-inline list-unstyled">
                                            <li><i class="fa fa-fw fa-check-circle-o" aria-hidden="true"></i><a href="#api-child-{$apicontent.key}" data-toggle="apicheck">{$LANG->get('api_roles_checkall')}</a></li>
                                            <li><i class="fa fa-fw fa-circle-o" aria-hidden="true"></i><a href="#api-child-{$apicontent.key}" data-toggle="apiuncheck">{$LANG->get('api_roles_uncheckall')}</a></li>
                                        </ul>
                                    </div>
                                </div>
                                {/foreach}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row mb-0 pb-0">
                    <label class="col-12 col-sm-3 col-form-label text-sm-right"></label>
                    <div class="col-12 col-sm-8 col-lg-6">
                        <input type="hidden" name="current_cat" value="{$CURRENT_CAT}">
                        <button class="btn btn-space btn-primary" type="submit" name="submit">{$LANG->get('save')}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
{if $IS_SUBMIT_FORM or $ROLE_ID}
{literal}
<script type="text/javascript">
$(document).ready(function() {
    $("html,body").animate({scrollTop: $('#addeditarea').offset().top}, 100);
});
</script>
{/literal}
{/if}

{*
<!-- BEGIN: main -->

<!-- BEGIN: data -->
<div class="alert alert-info">{LANG.api_role_notice}.</div>
<form>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <caption>
                <i class="fa fa-fw fa-file-o"></i>{LANG.api_roles_list}
            </caption>
            <thead>
                <tr>
                    <th style="width: 25%;">{LANG.api_roles_title}</th>
                    <th style="width: 30%;">{LANG.api_roles_description}</th>
                    <th style="width: 15%;">{LANG.api_addtime}</th>
                    <th style="width: 15%;">{LANG.api_edittime}</th>
                    <th style="width: 15%;" class="text-center">{LANG.funcs}</th>
                </tr>
            </thead>
            <tbody>
                <!-- BEGIN: loop -->
                <tr>
                    <td>
                        <a href="#apiroledetail{ROW.role_id}" data-toggle="apiroledetail" data-title="{LANG.api_roles_detail}: {ROW.role_title}">{ROW.role_title}</a> <strong class="text-danger">({ROW.apitotal})</strong>
                    </td>
                    <td>{ROW.role_description}</td>
                    <td>{ROW.addtime}</td>
                    <td>{ROW.edittime}</td>
                    <td class="text-center">
                        <a href="{ROW.link_edit}" class="btn btn-xs btn-default"><i class="fa fa-fw fa-edit"></i>{GLANG.edit}</a> <a href="#" data-id="{ROW.role_id}" data-toggle="apiroledel" class="btn btn-xs btn-danger"><i class="fa fa-fw fa-trash"></i>{GLANG.delete}</a>
                    </td>
                </tr>
                <!-- END: loop -->
            </tbody>
        </table>
    </div>
</form>
<!-- BEGIN: loop_detail -->
<div id="apiroledetail{ROW.role_id}" class="hidden">
    <!-- BEGIN: cat -->
    <div class="form-group">
        <h2>
            <strong>{CAT_NAME}</strong>:
        </h2>
        <div class="row">
            <!-- BEGIN: loop -->
            <div class="col-xs-12">{API_NAME}</div>
            <!-- END: loop -->
        </div>
    </div>
    <!-- END: cat -->
</div>
<!-- END: loop_detail -->
<!-- END: data -->


*}
