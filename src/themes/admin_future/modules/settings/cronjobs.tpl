<div class="row g-4">
    <div class="col-xl-7">
        <form method="post" class="ajax-submit" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" novalidate>
            <div class="card">
                <div class="card-header fw-medium fs-5 py-2">{$LANG->getModule('general_settings')}</div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-3 col-xl-4 col-form-label text-sm-end">{$LANG->getModule('cron_launcher')}</div>
                        <div class="col-sm-8 col-lg-6 col-xl-7">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="cronjobs_launcher" value="system" id="cronjobs_launcher_system"{if !isset($GCONFIG.cronjobs_launcher) or $GCONFIG.cronjobs_launcher neq 'server'} checked{/if}>
                                <label class="form-check-label" for="cronjobs_launcher_system">{$LANG->getModule('cron_launcher_system')}</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="cronjobs_launcher" value="server" id="cronjobs_launcher_server"{if isset($GCONFIG.cronjobs_launcher) and $GCONFIG.cronjobs_launcher eq 'server'} checked{/if}>
                                <label class="form-check-label" for="cronjobs_launcher_server">{$LANG->getModule('cron_launcher_server')}</label>
                            </div>
                            <div class="form-text">{$LANG->getModule('cron_launcher_server_help')}: <code>{$LAUCHER_SERVER_URL}</code></div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3 col-xl-4 col-form-label text-sm-end">{$LANG->getModule('cron_launcher_interval')}</div>
                        <div class="col-sm-8 col-lg-6 col-xl-7">
                            <select name="cronjobs_interval" class="form-select">
                                {for $min=1 to 59}
                                <option value="{$min}"{if $min eq $GCONFIG.cronjobs_interval} selected{/if}>{plural($min, {$LANG->getGlobal('plural_min')})}</option>
                                {/for}
                            </select>
                            <div class="form-text">{$LANG->getModule('cron_launcher_interval1')}</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8 offset-sm-3 offset-xl-4">
                            <input type="hidden" name="cfg" value="1">
                            <input type="hidden" name="checkss" value="{$smarty.const.NV_CHECK_SESSION}">
                            <button type="submit" class="btn btn-primary">{$LANG->getModule('submit')}</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="col-xl-5">
        {if isset($GCONFIG.cronjobs_launcher) and $GCONFIG.cronjobs_launcher eq 'server'}
        <div class="card mb-3">
            <div class="p-2">
                <div class="mb-1 fw-medium">{$LANG->getModule('cron_launcher_server_note')}</div>
                <div class="border rounded-1 p-2 bg-body-tertiary">
                    <code>{$CRON_CODE}</code>
                </div>
            </div>
        </div>
        {/if}
        {if $GCONFIG.cronjobs_last_time gt 0}
        <ul class="list-group">
            <li class="list-group-item">
                {$LANG->getModule('cron_last_time', $GCONFIG.cronjobs_last_time|ddatetime)}
            </li>
            <li class="list-group-item">
                {$LANG->getModule('cron_next_time', ($GCONFIG.cronjobs_last_time + $GCONFIG.cronjobs_interval * 60)|ddatetime)}
            </li>
        </ul>
        {/if}
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-body border-bottom">
                <div class="hstack gap-2 justify-content-between align-items-center">
                    <div class="fw-medium fs-5">
                        <div class="hstack gap-2 align-items-center">
                            <span>{$LANG->getModule('cron_list')}</span>
                            <button data-toggle="cronAdd" data-autofile="{$AUTO_ADD_FILE}" type="button" class="btn btn-success btn-sm" aria-label="{$LANG->getModule('nv_admin_add')}" data-bs-title="{$LANG->getModule('nv_admin_add')}" data-bs-trigger="hover" data-bs-toggle="tooltip"><i class="fa-solid fa-plus"></i></button>
                        </div>
                    </div>
                    <div>{$LANG->getModule('last_time')}</div>
                </div>
            </div>
            <div class="accordion accordion-flush mb-1" id="cronlist">
                {foreach from=$CRONLISTS key=crid item=crinfo}
                <div class="accordion-item">
                    <div class="accordion-header">
                        <button class="accordion-button collapsed gap-2" type="button" data-bs-toggle="collapse" data-bs-target="#cron-{$crid}" aria-expanded="false" aria-controls="cron-{$crid}">
                            <div class="d-flex flex-grow-1 gap-2 justify-content-between align-items-center{if empty($crinfo.act)} text-muted{/if}">
                                <div>{$crinfo.caption}</div>
                                <div>
                                    {if empty($crinfo.last_time)}{$LANG->getModule('last_time0')}{else}
                                    {$crinfo.last_time_title}
                                    {if $crinfo.last_result}
                                    <i class="fa-solid fa-circle-check text-success" title="{$crinfo.last_result_title}" aria-label="{$crinfo.last_result_title}" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-title="{$crinfo.last_result_title}"></i>
                                    {else}
                                    <i class="fa-solid fa-triangle-exclamation text-danger" title="{$crinfo.last_result_title}" aria-label="{$crinfo.last_result_title}" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-title="{$crinfo.last_result_title}"></i>
                                    {/if}
                                    {/if}
                                </div>
                            </div>
                        </button>
                    </div>
                    <div id="cron-{$crid}" class="accordion-collapse collapse" data-bs-parent="#cronlist">
                        <ul class="list-group list-group-flush">
                            {foreach from=$crinfo.detail key=key item=value}
                            <li class="list-group-item bg-body-tertiary">
                                <div class="row g-2">
                                    <div class="col-6 fw-medium">
                                        {$key}:
                                    </div>
                                    <div class="col-6 text-break">
                                        {$value}
                                    </div>
                                </div>
                            </li>
                            {/foreach}
                            {if empty($crinfo.is_sys) or empty($crinfo.act)}
                            <li class="list-group-item bg-secondary-subtle">
                                <div class="hstack gap-1 justify-content-center">
                                    {if empty($crinfo.is_sys)}
                                    <button class="btn btn-secondary" data-toggle="editCron" data-id="{$crid}" data-checkss="{$smarty.const.NV_CHECK_SESSION}"><i class="fa-solid fa-pencil"></i> {$LANG->getGlobal('edit')}</button>
                                    {/if}
                                    <button class="btn btn-secondary" data-toggle="actCron" data-id="{$crid}" data-checkss="{$crinfo.act_checkss}"><i class="fa-solid fa-ban text-warning"></i> {$crinfo.act ? $LANG->getGlobal('disable') : $LANG->getGlobal('activate')}</button>
                                    {if empty($crinfo.is_sys)}
                                    <button class="btn btn-secondary" data-toggle="delCron" data-id="{$crid}" data-checkss="{$crinfo.del_checkss}"><i class="fa-solid fa-trash text-danger" data-icon="fa-trash"></i> {$LANG->getGlobal('delete')}</button>
                                    {/if}
                                </div>
                            </li>
                            {/if}
                        </ul>
                    </div>
                </div>
                {/foreach}
            </div>
        </div>
    </div>
</div>
<link type="text/css" href="{$smarty.const.ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet">
<script type="text/javascript" src="{$smarty.const.ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{$smarty.const.ASSETS_STATIC_URL}/js/language/jquery.ui.datepicker-{$smarty.const.NV_LANG_INTERFACE}.js"></script>
<div class="modal fade" id="mdCronForm" tabindex="-1" aria-labelledby="mdCronFormLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <form class="modal-content ajax-submit" method="post" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" novalidate>
            <div class="modal-header">
                <div class="modal-title fs-5 fw-medium" id="mdCronFormLabel">.</div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <label for="element_cron_name" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('cron_name')} <span class="text-danger">(*)</span></label>
                    <div class="col-sm-8 col-lg-7">
                        <input type="text" class="form-control" id="element_cron_name" name="cron_name" value="" maxlength="100">
                        <div class="invalid-feedback">{$LANG->getGlobal('required_invalid')}</div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="element_run_file" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('run_file')}</label>
                    <div class="col-sm-8 col-lg-7">
                        <select class="form-select" name="run_file" id="element_run_file">
                            <option value="">{$LANG->getModule('file_none')}</option>
                            {foreach from=$FILELIST item=file}
                            <option value="{$file}">{$file}</option>
                            {/foreach}
                        </select>
                        <div class="invalid-feedback">{$LANG->getGlobal('required_invalid')}</div>
                        <div class="form-text">{$LANG->getModule('run_file_info')}</div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="element_run_func_iavim" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('run_func')} <span class="text-danger">(*)</span></label>
                    <div class="col-sm-8 col-lg-7">
                        <input type="text" class="form-control" id="element_run_func_iavim" name="run_func_iavim" value="" maxlength="255">
                        <div class="invalid-feedback">{$LANG->getGlobal('required_invalid')}</div>
                        <div class="form-text">{$LANG->getModule('run_func_info')}</div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="element_params_iavim" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('params')}</label>
                    <div class="col-sm-8 col-lg-7">
                        <input type="text" class="form-control" id="element_params_iavim" name="params_iavim" value="" maxlength="255">
                        <div class="invalid-feedback">{$LANG->getGlobal('required_invalid')}</div>
                        <div class="form-text">{$LANG->getModule('params_info')}</div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('start_time')}</div>
                    <div class="col-sm-8">
                        <div class="hstack gap-1 flex-wrap">
                            <select name="hour" class="form-select fw-75">
                                {for $i=0 to 23}
                                <option value="{$i}">{$i}</option>
                                {/for}
                            </select>
                            <span>{$LANG->getModule('hour')}</span>
                            <select name="min" class="form-select fw-75">
                                {for $i=0 to 59}
                                <option value="{$i}">{$i}</option>
                                {/for}
                            </select>
                            <span>{$LANG->getModule('min')}, {$LANG->getModule('day')}</span>
                            <input name="start_date" value="{$START_TIME}" data-default="{$START_TIME}" class="form-control fw-100 datepicker" maxlength="10" type="text">
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="element_interval_iavim" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('interval')}</label>
                    <div class="col-sm-8 col-lg-7">
                        <div class="hstack gap-2">
                            <input type="number" class="form-control fw-75" id="element_interval_iavim" name="interval_iavim" value="0" min="0" max="9999">
                            <span>{$LANG->getModule('min')}</span>
                        </div>
                        <div class="form-text">{$LANG->getModule('interval_info')}</div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="element_inter_val_type" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('cron_interval_type')}</label>
                    <div class="col-sm-8 col-lg-7">
                        <select class="form-select" name="inter_val_type" id="element_inter_val_type">
                            {for $i=0 to 1}
                            <option value="{$i}">{$LANG->getModule("cron_interval_type`$i`")}</option>
                            {/for}
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-8 col-lg-7 offset-sm-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="del" value="1" id="element_del">
                            <label class="form-check-label" for="element_del">{$LANG->getModule('is_del')}</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="checkss" value="{$smarty.const.NV_CHECK_SESSION}">
                <input type="hidden" name="crontabcontent" value="1">
                <input type="hidden" name="id" value="0">
                <button type="submit" class="btn btn-primary">{$LANG->getGlobal('submit')}</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{$LANG->getGlobal('close')}</button>
            </div>
        </form>
    </div>
</div>
