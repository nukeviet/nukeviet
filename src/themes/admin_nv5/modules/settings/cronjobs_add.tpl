{if not empty($ERROR)}
<div role="alert" class="alert alert-danger alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="far fa-times-circle"></i></div>
    <div class="message">{$ERROR}</div>
</div>
{else}
<div role="alert" class="alert alert-primary alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="fas fa-info-circle"></i></div>
    <div class="message">{if $IS_ADD}{$LANG->get('nv_admin_add_title')}{else}{$LANG->get('nv_admin_edit_title')}{/if}</div>
</div>
{/if}
<form method="post" action="{$FORM_ACTION}" autocomplete="off">
    <div class="card card-border-color card-border-color-primary">
        <div class="card-body">
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="cron_name">{$LANG->get('cron_name')} <i class="text-danger">(*)</i></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="text" class="form-control form-control-sm" id="cron_name" name="cron_name" value="{$DATA['cron_name']}" maxlength="100">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="run_file">{$LANG->get('run_file')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <select class="select2" id="run_file" name="run_file">
                        <option value="">{$LANG->get('file_none')}</option>
                        {foreach from=$FILELIST item=file}
                        <option value="{$file}"{if $file eq $DATA['run_file']} selected="selected"{/if}>{$file}</option>
                        {/foreach}
                    </select>
                    <span class="form-text text-muted">{$LANG->get('run_file_info')}</span>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="run_func_iavim">{$LANG->get('run_func')} <i class="text-danger">(*)</i></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="text" class="form-control form-control-sm" id="run_func_iavim" name="run_func_iavim" value="{$DATA['run_func']}" maxlength="255">
                    <span class="form-text text-muted">{$LANG->get('run_func_info')}</span>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="params_iavim">{$LANG->get('params')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="text" class="form-control form-control-sm" id="params_iavim" name="params_iavim" value="{$DATA['params']}" maxlength="255">
                    <span class="form-text text-muted">{$LANG->get('params_info')}</span>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="ftp_server">{$LANG->get('start_time')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 flex-shrink-1">
                            <select class="form-control form-control-sm" name="hour">
                                {for $key=0 to 23}
                                <option value="{$key}"{if $key eq $DATA['hour']} selected="selected"{/if}>{$key}</option>
                                {/for}
                            </select>
                        </div>
                        <div class="flex-grow-0 flex-shrink-0 pr-1 pl-1">{$LANG->get('hour')}</div>
                        <div class="flex-grow-1 flex-shrink-1">
                            <select class="form-control form-control-sm" name="min">
                                {for $key=0 to 59}
                                <option value="{$key}"{if $key eq $DATA['min']} selected="selected"{/if}>{$key}</option>
                                {/for}
                            </select>
                        </div>
                        <div class="flex-grow-0 flex-shrink-0 pr-1 pl-1">{$LANG->get('min')}, {$LANG->get('day')}</div>
                        <div class="flex-grow-1 flex-shrink-1">
                            <input type="text" class="form-control form-control-sm bsdatepicker" id="start_date" name="start_date" value="{"d/m/Y"|date:$DATA['start_time']}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row pb-1">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="interval_iavim">{$LANG->get('interval')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 flex-shrink-1">
                            <input type="text" class="form-control form-control-sm" id="interval_iavim" name="interval_iavim" value="{$DATA['interval']}" maxlength="11">
                        </div>
                        <div class="flex-grow-0 flex-shrink-0 pl-2">{$LANG->get('min')}</div>
                    </div>
                    <span class="form-text text-muted">{$LANG->get('interval_info')}</span>
                </div>
            </div>
            <div class="form-group row pt-0 pb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="cron_del">{$LANG->get('is_del')}</label>
                <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                    <label class="custom-control custom-checkbox custom-control-inline">
                        <input class="custom-control-input" type="checkbox" id="cron_del" name="del" value="1"{if {$DATA['del']}} checked="checked"{/if}><span class="custom-control-label"></span>
                    </label>
                </div>
            </div>
            <div class="form-group row mb-0 pb-0 pt-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right"></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input name="save" type="hidden" value="1" />
                    <button class="btn btn-space btn-primary" type="submit" name="go_add">{$LANG->get('submit')}</button>
                </div>
            </div>
        </div>
    </div>
</form>

<link data-offset="0" rel="stylesheet" href="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/select2/select2.min.css">
<link data-offset="0" rel="stylesheet" href="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/bootstrap-datepicker/css/bootstrap-datepicker.min.css">

<script src="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script src="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/select2/i18n/{$NV_LANG_INTERFACE}.js"></script>
<script src="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/bootstrap-datepicker/locales/bootstrap-datepicker.{$NV_LANG_INTERFACE}.min.js"></script>
<script>
$(document).ready(function() {
    $(".select2").select2({
        width: "100%",
        containerCssClass: "select2-sm"
    });
    $(".bsdatepicker").datepicker({
        autoclose: 1,
        templates: {
            rightArrow: '<i class="fas fa-chevron-right"></i>',
            leftArrow: '<i class="fas fa-chevron-left"></i>'
        },
        language: '{$NV_LANG_INTERFACE}',
        orientation: 'auto',
        todayHighlight: true,
        format: 'dd/mm/yyyy'
    });
});
</script>
