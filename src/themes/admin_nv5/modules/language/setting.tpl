{if not $IS_SAVED}
<div class="card card-table">
    <div class="card-header">
        {$LANG->get('nv_lang_show')}
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th style="width: 10%;">{$LANG->get('nv_lang_key')}</th>
                        <th style="width: 20%;">{$LANG->get('nv_lang_name')}</th>
                        <th style="width: 20%;">{$LANG->get('nv_lang_native_name')}</th>
                        <th style="width: 50%;" class="text-right">{$LANG->get('actions')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$ARRAYS item=row}
                    <tr>
                        <td class="text-nowrap">{$row.key}</td>
                        <td class="text-nowrap">{$row.language}</td>
                        <td class="text-nowrap">{$row.name}</td>
                        <td class="text-right text-nowrap">
                            {if isset($row.funcs.read)}<a href="{$BASE_URL}={$row.funcs.read}" class="btn btn-sm btn-secondary"><i class="icon icon-left fas fa-database"></i> {$LANG->get('nv_admin_read_all')}</a>{/if}
                            {if isset($row.funcs.write)}<a href="{$BASE_URL}={$row.funcs.write}" class="btn btn-sm btn-secondary"><i class="icon icon-left fas fa-marker"></i> {$LANG->get('nv_admin_write')}</a>{/if}
                            {if isset($row.funcs.download)}<a href="{$BASE_URL}={$row.funcs.download}" class="btn btn-sm btn-secondary"><i class="icon icon-left fas fa-file-archive"></i> {$LANG->get('nv_admin_download')}</a>{/if}
                            {if isset($row.funcs.delete)}<a href="{$BASE_URL}={$row.funcs.delete}" class="btn btn-sm btn-danger"><i class="icon icon-left fas fa-trash-alt"></i> {$LANG->get('nv_admin_delete')}</a>{/if}
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="card card-border-color card-border-color-primary">
    <div class="card-header card-header-divider">
        {$LANG->get('nv_setting_read')}
    </div>
    <div class="card-body">
        <form method="post" action="{$FORM_ACTION}" autocomplete="off">
            <input type="hidden" name ="checkss" value="{$NV_CHECK_SESSION}">
            {foreach from=$READTYPES key=key item=value}
            <div class="form-group row pt-1 pb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right d-none d-sm-block"></label>
                <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                    <label class="custom-control custom-radio m-0">
                        <input class="custom-control-input" type="radio" name="read_type" value="{$key}"{if $key eq $CONFIG['read_type']} checked="checked"{/if}><span class="custom-control-label">{$value}</span>
                    </label>
                </div>
            </div>
            {/foreach}
            <div class="form-group row mb-0 pb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right"></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <button class="btn btn-space btn-primary" type="submit">{$LANG->get('nv_admin_edit_save')}</button>
                </div>
            </div>
        </form>
    </div>
</div>
{else}
<div role="alert" class="alert alert-success alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="fas fa-check"></i></div>
    <div class="message">{$LANG->get('nv_setting_save')}</div>
</div>
<script>
setTimeout(function() {
    window.location = '{$URL_REDIRECT}';
}, 5000);
</script>
{/if}
