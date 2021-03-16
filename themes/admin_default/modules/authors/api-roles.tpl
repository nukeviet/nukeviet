<!-- BEGIN: main -->
<!-- BEGIN: empty -->
<div class="alert alert-info">{LANG.api_roles_empty}.</div>
<!-- END: empty -->
<!-- BEGIN: data -->
<div class="alert alert-info">{LANG.api_role_notice}.</div>
<!-- END: data -->
<div id="addeditarea">
    <!-- BEGIN: add_notice -->
    <div class="alert alert-info">{LANG.api_role_notice_lang}.</div>
    <!-- END: add_notice -->
    <!-- BEGIN: error -->
    <div class="alert alert-danger">{ERROR}</div>
    <!-- END: error -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>{CAPTION}</strong>
        </div>
        <div class="panel-body">
            <form method="post" action="{FORM_ACTION}" class="form-horizontal" autocomplete="off">
                <div class="form-group">
                    <label class="col-sm-6 control-label" for="role_title">{LANG.api_roles_title} <span class="text-danger">(*)</span>:</label>
                    <div class="col-sm-18 col-lg-10">
                        <input type="text" id="role_title" name="role_title" value="{DATA.role_title}" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-6 control-label" for="role_description">{LANG.api_roles_description} <span class="text-danger">(*)</span>:</label>
                    <div class="col-sm-18 col-lg-10">
                        <textarea type="text" class="form-control" id="role_description" name="role_description" rows="2">{DATA.role_description}</textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-18 col-sm-offset-6">
                        <strong id="apiRoleAll">{LANG.api_roles_allowed}<!-- BEGIN: total_api_enabled --> <span class="text-danger">{TOTAL_API_ENABLED}</span><!-- END: total_api_enabled --></strong>
                    </div>
                </div>
                <hr />
                <div class="row">
                    <div class="col-sm-18 col-sm-offset-6">
                        <input type="hidden" name="current_cat" value="{CURRENT_CAT}">
                        <button type="submit" name="submit" value="submit" class="btn btn-primary">{GLANG.save}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- BEGIN: scrolltop -->
<script type="text/javascript">
$(document).ready(function() {
    $("html,body").animate({scrollTop: $('#addeditarea').offset().top}, 100);
});
</script>
<!-- END: scrolltop -->
<!-- END: main -->



{if empty($ARRAY)}
{else}
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
    <div class="card card-border-color card-border-color-primary">
        <div class="card-body">
            <form method="post" action="{$FORM_ACTION}">

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
            </form>
        </div>
    </div>
</div>
