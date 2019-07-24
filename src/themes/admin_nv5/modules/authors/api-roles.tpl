{if empty($ARRAY)}
<div role="alert" class="alert alert-primary alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="fas fa-info-circle"></i></div>
    <div class="message">{$LANG->get('api_roles_empty')}.</div>
</div>
{else}
<div role="alert" class="alert alert-primary alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="fas fa-info-circle"></i></div>
    <div class="message">{$LANG->get('api_role_notice')}.</div>
</div>
<div class="card card-table">
    <div class="card-header">
        {$LANG->get('api_roles_list')}
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th style="width: 25%;">{$LANG->get('api_roles_title')}</th>
                        <th style="width: 30%;">{$LANG->get('api_roles_description')}</th>
                        <th style="width: 15%;">{$LANG->get('api_addtime')}</th>
                        <th style="width: 15%;">{$LANG->get('api_edittime')}</th>
                        <th style="width: 15%;" class="text-center">{$LANG->get('funcs')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$ARRAY item=row}
                    <tr>
                        <td>
                            <a href="#apiroledetail{$row.role_id}" data-toggle="modal">{$row.role_title}</a> <strong class="text-danger">({$row.apitotal})</strong>
                        </td>
                        <td>{$row.role_description}</td>
                        <td>{"H:i d/m/Y"|date:$row.addtime}</td>
                        <td>{if $row.edittime}{"H:i d/m/Y"|date:$row.edittime}{/if}</td>
                        <td class="text-center">
                            <a href="{$NV_BASE_ADMINURL}index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&amp;{$NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$NV_OP_VARIABLE}={$OP}&amp;role_id={$row.role_id}" class="btn btn-sm btn-hspace btn-secondary"><i class="icon icon-left fas fa-pencil-alt"></i> {$LANG->get('edit')}</a>
                            <a href="#" class="btn btn-sm btn-danger" data-id="{$row.role_id}" data-toggle="apiroledel"><i class="icon icon-left fas fa-trash-alt"></i> {$LANG->get('delete')}</a>
                        </td>
                    </tr>
                    <!-- START FORFOOTER -->
                    <div id="apiroledetail{$row.role_id}" tabindex="-1" role="dialog" class="modal fade colored-header colored-header-primary">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header modal-header-colored">
                                    <h3 class="modal-title">{$LANG->get('api_roles_detail')}: {$row.role_title}</h3>
                                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close"><span class="fas fa-times"></span></button>
                                </div>
                                <div class="modal-body p-4">
                                    {if not empty($row.apis[''])}
                                    {foreach from=$row.apis[''] key=cat_key item=cat_data}
                                    <h4>{$LANG->get('api_of_system')}: {$cat_data.title}</h4>
                                    <div class="row">
                                        {foreach from=$cat_data.apis item=api_data}
                                        <div class="col-12 col-sm-6">
                                            <div class="text-truncate"><i class="fas fa-genderless"></i> {$api_data}</div>
                                        </div>
                                        {/foreach}
                                    </div>
                                    {/foreach}
                                    {/if}
                                    {if not empty($row.apis[$NV_LANG_DATA])}
                                    {foreach from=$row.apis[$NV_LANG_DATA] key=mod_title item=mod_data}
                                    {foreach from=$mod_data key=cat_key item=cat_data}
                                    <h4>{$SITE_MODS[$mod_title].custom_title}{if not empty($cat_data.title)}: {$cat_data.title}{/if}</h4>
                                    <div class="row">
                                        {foreach from=$cat_data.apis item=api_data}
                                        <div class="col-12 col-sm-6">
                                            <div class="text-truncate" title="{$api_data}"><i class="fas fa-genderless"></i> {$api_data}</div>
                                        </div>
                                        {/foreach}
                                    </div>
                                    {/foreach}
                                    {/foreach}
                                    {/if}
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END FORFOOTER -->
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
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
                                            <li class="list-inline-item"><a href="#api-child-{$apicontent.key}" data-toggle="apicheck"><i class="fas fa-check-circle text-muted"></i> {$LANG->get('api_roles_checkall')}</a></li>
                                            <li class="list-inline-item"><a href="#api-child-{$apicontent.key}" data-toggle="apiuncheck"><i class="fas fa-circle text-muted"></i> {$LANG->get('api_roles_uncheckall')}</a></li>
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
