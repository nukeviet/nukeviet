<div class="card card-table card-footer-nav">
    <div class="card-body">
        <form action="{$NV_BASE_ADMINURL}index.php" method="get" id="log-search-form">
            <input type="hidden" name="{$NV_LANG_VARIABLE}" value="{$NV_LANG_DATA}">
            <input type="hidden" name="{$NV_NAME_VARIABLE}" value="{$MODULE_NAME}">
            <input type="hidden" name="{$NV_OP_VARIABLE}" value="{$OP}">
            <div class="row card-body-search-form">
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-6 col-lg-4 mb-2">
                            <label>{$LANG->get('filter_enterkey')}:</label>
                            <input type="text" class="form-control form-control-sm" name="q" value="{$DATA_SEARCH.q}" placeholder="{$LANG->get('filter_enterkey')}">
                        </div>
                        <div class="col-md-6 col-lg-4 mb-2">
                            <div class="row">
                                <div class="col-6">
                                    <label>{$LANG->get('filter_from')}:</label>
                                    <input type="text" class="form-control form-control-sm datepicker" name="from" value="{$DATA_SEARCH.from}" autocomplete="off">
                                </div>
                                <div class="col-6">
                                    <label>{$LANG->get('filter_to')}:</label>
                                    <input type="text" class="form-control form-control-sm datepicker" name="to" value="{$DATA_SEARCH.to}" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 mb-2">
                            <label>{$LANG->get('filter_lang')}:</label>
                            <select class="select2 select2-sm" name="lang">
                                <option value="">{$LANG->get('All')}</option>
                                {foreach from=$ARRAY_LANG item=$lang}
                                <option value="{$lang}"{if $lang eq $DATA_SEARCH['lang']} selected="selected"{/if}>{$LANGUAGE_ARRAY[$lang]['name']}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="col-md-6 col-lg-4 mb-2">
                            <label>{$LANG->get('filter_user')}:</label>
                            <select class="select2 select2-sm" name="user">
                                <option value="">{$LANG->get('All')}</option>
                                <option value="system"{if "system" eq $DATA_SEARCH['user']} selected="selected"{/if}>{$LANG->get('filter_system')}</option>
                                {foreach from=$ARRAY_USER item=$user}
                                <option value="{$user['userid']}"{if $user['userid'] eq $DATA_SEARCH['user']} selected="selected"{/if}>{$user['username']}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="col-md-6 col-lg-4 mb-2">
                            <label>{$LANG->get('filter_module')}:</label>
                            <select class="select2 select2-sm" name="module">
                                <option value="">{$LANG->get('All')}</option>
                                {foreach from=$ARRAY_MODULE item=$module}
                                <option value="{$module}"{if $module eq $DATA_SEARCH['module']} selected="selected"{/if}>{if isset($SITE_MODS[$module])}{$SITE_MODS[$module]['custom_title']}{elseif isset($ADMIN_MODS[$module])}{$ADMIN_MODS[$module]['custom_title']}{else}{$module}{/if}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <label class="d-none d-md-block">&nbsp;</label>
                            <div>
                                <input type="submit" value="{$LANG->get('filter_action')}" class="btn btn-primary btn-space" />
                                {if $DATA_SEARCH['is_search']}<a href="{$URL_CANCEL}" class="btn btn-danger btn-space"/>{$LANG->get('filter_cancel')}</a>{/if}
                                <input type="button" id="clear-log-search-form" value="{$LANG->get('filter_clear')}" class="btn btn-secondary btn-space"/>
                                <input type="hidden" name="checksess" value="{$CHECKSESS}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <form id="list-logs">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th style="width:1%;">
                                <label class="custom-control custom-control-sm custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" name="check_all[]" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]', this.checked);"><span class="custom-control-label"></span>
                                </label>
                            </th>
                            <th class="sorting" style="width:1%;">
                                <div class="text-nowrap"><a href="{$DATA_ORDER.lang.data.url}"><i class="fas fa-{if $DATA_ORDER['lang']['data']['key'] eq 'asc'}sort-up{elseif $DATA_ORDER['lang']['data']['key'] eq 'desc'}sort-down{else}sort{/if}"></i> {$LANG->get('log_lang')}</a></div>
                            </th>
                            <th class="sorting" style="width:1%;">
                                <div class="text-nowrap"><a href="{$DATA_ORDER.module.data.url}"><i class="fas fa-{if $DATA_ORDER['module']['data']['key'] eq 'asc'}sort-up{elseif $DATA_ORDER['module']['data']['key'] eq 'desc'}sort-down{else}sort{/if}"></i> {$LANG->get('log_module_name')}</a></div>
                            </th>
                            <th style="width:23%;">{$LANG->get('log_name_key')}</th>
                            <th style="width:24%;">{$LANG->get('log_note')}</th>
                            <th style="width:15%;">{$LANG->get('log_username')}</th>
                            <th class="sorting" style="width:15%;">
                                <div class="text-nowrap"><a href="{$DATA_ORDER.time.data.url}"><i class="fas fa-{if $DATA_ORDER['time']['data']['key'] eq 'asc'}sort-up{elseif $DATA_ORDER['time']['data']['key'] eq 'desc'}sort-down{else}sort{/if}"></i> {$LANG->get('log_time')}</a></div>
                            </th>
                            {if $ALLOW_DELETE}
                            <th class="text-right text-nowrap" style="width:10%;">{$LANG->get('log_feature')}</th>
                            {/if}
                        </tr>
                    </thead>
                    <tbody>
                        {foreach from=$DATA item=row}
                        <tr>
                            <td>
                                <label class="custom-control custom-control-sm custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" name="idcheck[]" value="{$row.id}" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);"><span class="custom-control-label"></span>
                                </label>
                            </td>
                            <td>
                                {$row.lang}
                            </td>
                            <td>
                                {if isset($SITE_MODS[$row['module_name']])}
                                {$SITE_MODS[$row['module_name']]['custom_title']}
                                {elseif isset($ADMIN_MODS[$row['module_name']])}
                                {$ADMIN_MODS[$row['module_name']]['custom_title']}
                                {else}
                                {$row['module_name']}
                                {/if}
                            </td>
                            <td>
                                {$row.name_key}
                            </td>
                            <td>
                                {$row.note_action}
                            </td>
                            <td>
                                {if not empty($DATA_USER[$row['userid']])}
                                {$DATA_USER[$row['userid']]}
                                {else}
                                Unknow
                                {/if}
                            </td>
                            <td>
                                {$row.time}
                            </td>
                            {if $ALLOW_DELETE}
                            <td class="text-right">
                                <a title="{$LANG->get('delete')}" href="#" class="btn btn-danger btn-sm" data-toggle="del-log" data-id="{$row.id}" data-message="{$LANG->get('log_del_confirm')}"><i class="icon icon-left fas fa-trash-alt"></i> {$LANG->get('delete')}</a>
                            </td>
                            {/if}
                        </tr>
                        {/foreach}
                    </tbody>
                </table>
            </div>
        </form>
    </div>
    <div class="card-footer">
        <div class="page-tools">
            {if $ALLOW_DELETE}
            <button class="btn btn-space btn-hspace btn-danger" data-toggle="del-logs" data-message="{$LANG->get('log_del_confirm')}"><i class="icon icon-left fas fa-trash-alt"></i> {$LANG->get('delete')}</button>
            <button class="btn btn-space btn-danger" data-toggle="del-all-logs" data-message="{$LANG->get('log_del_confirm')}" data-checksess="{$CHECKSESS}"><i class="icon icon-left far fa-times-circle"></i> {$LANG->get('log_empty')}</button>
            {/if}
        </div>
        {if not empty($GENERATE_PAGE)}
        <nav class="page-nav">
            {$GENERATE_PAGE}
        </nav>
        {/if}
    </div>
</div>

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
    $(".datepicker").datepicker({
        autoclose: !0,
        templates: {
            rightArrow: '<i class="fas fa-chevron-right"></i>',
            leftArrow: '<i class="fas fa-chevron-left"></i>'
        },
        language: '{$NV_LANG_INTERFACE}',
        orientation: 'auto bottom',
        todayHighlight: true,
        format: 'dd.mm.yyyy'
    });
});
</script>
