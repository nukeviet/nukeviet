<link data-offset="0" rel="stylesheet" href="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/select2/select2.min.css"/>
<script type="text/javascript" src="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script type="text/javascript" src="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/select2/i18n/{$NV_LANG_INTERFACE}.js"></script>
{if not empty($ERROR)}
<div role="alert" class="alert alert-danger alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="far fa-times-circle"></i></div>
    <div class="message">{$ERROR}</div>
</div>
{/if}
<div class="card card-border-color card-border-color-primary">
    <div class="card-header">
        {if $CREDENTIAL_IDENT}{$LANG->get('api_cr_edit')}{else}{$LANG->get('api_cr_add')}{/if}
    </div>
    <div class="card-body">
        <form method="post" action="{$FORM_ACTION}" autocomplete="off">
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="credential_title">{$LANG->get('api_cr_title')} <i class="text-danger">(*)</i></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="text" class="form-control form-control-sm" id="credential_title" name="credential_title" value="{$DATA.credential_title}">
                </div>
            </div>
            {if empty($CREDENTIAL_IDENT)}
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="admin_id">{$LANG->get('api_cr_for_admin')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <select id="admin_id" name="admin_id" class="select2 select2-sm">
                        <option value="0">--</option>
                        {foreach from=$ARRAY_ADMINS item=admin}
                        <option value="{$admin.admin_id}"{if $admin.admin_id eq $DATA.admin_id} selected="selected"{/if}>{$admin.username}{if not empty($admin.full_name)} ({$admin.full_name}){/if}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            {/if}
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right pt-1">{$LANG->get('api_cr_roles')}</label>
                <div class="col-12 col-sm-9 col-lg-9 pt-0">
                    <div class="row">
                        {foreach from=$ARRAY_ROLES key=key item=role}
                        <div class="col-12 col-sm-6">
                            <label class="custom-control custom-checkbox custom-control-inline mb-1 w-100 mr-0">
                                <input class="custom-control-input" type="checkbox" name="api_roles[]" value="{$role.role_id}"{if in_array($role.role_id, $DATA.api_roles)} checked="checked"{/if}><span class="custom-control-label text-truncate" title="{$role.role_title}">{$role.role_title}</span>
                            </label>
                        </div>
                        {/foreach}
                    </div>
                </div>
            </div>
            <div class="form-group row mb-0 pb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right"></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <button class="btn btn-space btn-primary" type="submit" name="submit">{$LANG->get('save')}</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    $('[name="admin_id"]').select2({
        width: "100%",
        containerCssClass: "select2-sm"
    });
});
</script>
