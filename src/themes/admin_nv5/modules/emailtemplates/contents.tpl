{* Lỗi *}
{if not empty($ERROR)}
<div role="alert" class="alert alert-danger alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="far fa-times-circle"></i></div>
    <div class="message">{$ERROR}</div>
</div>
{/if}
{* Thêm/Sửa danh mục *}
<form method="post" action="{$FORM_ACTION}" autocomplete="off">
    <div class="card card-border-color card-border-color-primary">
        <div class="card-header card-header-divider">{$LANG->get('tpl_basic_info')}</div>
        <div class="card-body">
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="tpl_send_name">{$LANG->get('tpl_send_name')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <div class="d-flex  align-items-center">
                        <div class="flex-grow-1 flex-shrink-1">
                            <input type="text" class="form-control form-control-sm" id="tpl_send_name" name="send_name" value="{$DATA['send_name']}">
                        </div>
                        <div class="flex-grow-1 flex-shrink-1 ml-2">
                            <input type="text" class="form-control form-control-sm" id="tpl_send_email" name="send_email" value="{$DATA['send_email']}" placeholder="name@email.com">
                        </div>
                    </div>
                    <span class="form-text text-muted">{$LANG->get('tpl_send_name_help')}</span>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="send_cc">{$LANG->get('tpl_send_cc')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="text" class="form-control form-control-sm" id="send_cc" name="send_cc" value="{$DATA['send_cc']}">
                    <span class="form-text text-muted">{$LANG->get('list_email_help')}</span>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="send_bcc">{$LANG->get('tpl_send_bcc')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="text" class="form-control form-control-sm" id="send_bcc" name="send_bcc" value="{$DATA['send_bcc']}">
                    <span class="form-text text-muted">{$LANG->get('list_email_help')}</span>
                </div>
            </div>
            <div class="form-group row pt-1 pb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right">{$LANG->get('tpl_is_plaintext')}</label>
                <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                    <label class="custom-control custom-checkbox custom-control-inline mb-1">
                        <input class="custom-control-input" type="checkbox" name="is_plaintext" value="1"{if $DATA['is_plaintext']} checked="checked"{/if}><span class="custom-control-label"> {$LANG->get('tpl_is_plaintext_help')}</span>
                    </label>
                </div>
            </div>
            <div class="form-group row pt-1 pb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right">{$LANG->get('tpl_is_disabled')}</label>
                <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                    <label class="custom-control custom-checkbox custom-control-inline mb-1">
                        <input class="custom-control-input" type="checkbox" name="is_disabled" value="1"{if $DATA['is_disabled']} checked="checked"{/if}><span class="custom-control-label"> {$LANG->get('tpl_is_disabled_help')}</span>
                    </label>
                </div>
            </div>
            {if not $DATA['is_system']}
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="tpl_title">{$LANG->get('tpl_title')} <i class="text-danger">(*)</i></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="text" class="form-control form-control-sm" id="tpl_title" name="title" value="{$DATA['title']}">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="tpl_catid">{$LANG->get('tpl_incat')}</label>
                <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
                    <select class="select2 select2-sm" id="tpl_catid" name="catid">
                        <option value="0">--</option>
                        {foreach from=$CATS item=row}
                        <option value="{$row.catid}"{if $row.catid eq $DATA['catid']} selected="selected"{/if}>{$row.title}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="tpl_pids">{$LANG->get('tpl_plugin')}</label>
                <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
                    <select class="select2 select2-sm" id="tpl_pids" name="pids[]" multiple="multiple">
                        {foreach from=$PLUGINS item=row}
                        <option value="{$row.pid}"{if in_array($row.pid, $DATA['pids'])} selected="selected"{/if}>{if empty({$row.plugin_module_name})}{$LANG->get('system')}{else}Module {$row.plugin_module_name}{/if}:{$row.plugin_file}</option>
                        {/foreach}
                    </select>
                    <div class="form-text text-muted">{$LANG->get('tpl_plugin_help')}</div>
                </div>
            </div>
            {else}
            <div class="d-none">
                <select name="pids[]" multiple="multiple">
                    {foreach from=$PLUGINS item=row}
                    <option value="{$row.pid}"{if in_array($row.pid, $DATA['pids'])} selected="selected"{/if}>{if empty({$row.plugin_module_name})}{$LANG->get('system')}{else}Module {$row.plugin_module_name}{/if}:{$row.plugin_file}</option>
                    {/foreach}
                </select>
            </div>
            {/if}
        </div>
    </div>
    <div class="card card-border-color card-border-color-primary">
        <div class="card-header card-header-divider">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1 flex-shrink-1">{$LANG->get('tpl_attachments')}</div>
                <div class="flex-grow-0 flex-shrink-0 pl-2">
                    <button class="btn btn-sm btn-success" type="button" data-toggle="attadd" data-size="{sizeof($DATA['attachments'])}"><i class="icon icon-left fas fa-plus"></i> {$LANG->get('add')}</button>
                </div>
            </div>
        </div>
        <div class="card-body" id="tpl-attachments">
            {foreach from=$DATA['attachments'] key=key item=row}
            <div class="d-flex align-items-center py-1">
                <div class="flex-grow-1 flex-shrink-1">
                    <input type="text" class="form-control form-control-sm" name="attachments[]" value="{if not empty($row)}{$NV_BASE_SITEURL}{$UPLOAD_PATH}/{$row}{/if}" id="tpl_att{$key}">
                </div>
                <div class="flex-grow-0 flex-shrink-0 pl-2">
                    <button class="btn btn-secondary btn-input-sm" type="button" data-toggle="browsefile" data-path="{$UPLOAD_PATH}" data-cpath="{$UPLOAD_PATH}" data-area="tpl_att{$key}"><i class="icon icon-left fas fa-folder-open"></i> {$LANG->get('browse_file')}</button>
                </div>
                <div class="flex-grow-0 flex-shrink-0 pl-2">
                    <button class="btn btn-danger btn-input-sm" type="button" data-toggle="attdel"><i class="icon icon-left fas fa-times"></i> {$LANG->get('delete')}</button>
                </div>
            </div>
            {/foreach}
        </div>
    </div>
    <div class="card card-border-color card-border-color-primary">
        <div class="card-header card-header-divider">
            {$LANG->get('default_content')}
            <div class="card-subtitle">{$LANG->get('default_content_info')}</div>
        </div>
        <div class="card-body">
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="default_subject">{$LANG->get('tpl_subject')} <i class="text-danger">(*)</i></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="text" class="form-control form-control-sm" id="default_subject" name="default_subject" value="{$DATA['default_subject']}">
                </div>
            </div>
            {$DATA.default_content}
        </div>
    </div>
    <div class="card card-border-color card-border-color-primary">
        <div class="card-header card-header-divider">
            {$LANG->get('lang_content')}
            <div class="card-subtitle">{$LANG->get('lang_content_info', $LANG_NAME)}</div>
        </div>
        <div class="card-body">
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="lang_subject">{$LANG->get('tpl_subject')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="text" class="form-control form-control-sm" id="lang_subject" name="lang_subject" value="{$DATA['lang_subject']}">
                </div>
            </div>
            {$DATA.lang_content}
            <div class="mt-4">
                <button class="btn btn-primary" type="submit" name="submit">{$LANG->get('submit')}</button>
            </div>
        </div>
    </div>
</form>

<div class="card">
    <div class="card-header card-header-divider">
        {$LANG->get('merge_field')}
        <div class="card-subtitle">{$LANG->get('merge_field_help')}</div>
    </div>
    <div class="pr-4 pb-4 pt-3">
        <div class="nv-scroller merge-fields-area" data-wheel="true">
            <div class="content px-4">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div id="merge-fields-content"></div>
                    </div>
                    <div class="col-12 col-md-6">
                        <h4 class="mt-0 pt-0">{$LANG->get('merge_field_guild1')}</h4>
                        {$LANG->get('merge_field_guild2')}
                        {literal}<pre><code>{if $username eq "hoaquynhtim99"}<br>Is Admin<br>{/if}</code></pre>{/literal}
                        {$LANG->get('merge_field_guild3')}.
                        <h4>{$LANG->get('merge_field_guild4')}</h4>
                        {$LANG->get('merge_field_guild5')}
                        {literal}<pre><code>{foreach from=$array_users item=user}<br>User: {$user}<br>{/foreach}</code></pre>{/literal}
                        {$LANG->get('merge_field_guild6')}.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<link data-offset="0" rel="stylesheet" href="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/select2/select2.min.css">

<script src="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script src="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/select2/i18n/{$NV_LANG_INTERFACE}.js"></script>

<script>
$(document).ready(function() {
    $(".select2").select2({
        width: "100%",
        containerCssClass: "select2-sm"
    });
    var fieldTimer;
    $('[name="pids[]"]').on('change', function() {
        if (fieldTimer) {
            clearTimeout(fieldTimer);
        }
        $('#merge-fields-content').html('');
        var pids = $(this).val();
        if (pids.length < 1) {
            updatePerfectScrollbar();
            return;
        }
        fieldTimer = setTimeout(function() {
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=contents&nocache=' + new Date().getTime(),
                data: {
                    'getMergeFields': 1,
                    'pids': pids
                },
                cache: false,
                success: function(res) {
                    $('#merge-fields-content').html(res);
                    fieldTimer = 0;
                    updatePerfectScrollbar();
                },
                error: function(jqXHR, exception) {
                    fieldTimer = 0;
                    updatePerfectScrollbar();
                }
            });
        }, 500);
    });
    $('[name="pids[]"]').trigger('change');
    var focusEditor = 'emailtemplates_default_content';
    if (typeof CKEDITOR != 'undefined') {
        CKEDITOR.instances.emailtemplates_default_content.on('focus', function() {
            focusEditor = 'emailtemplates_default_content';
        });
        CKEDITOR.instances.emailtemplates_lang_content.on('focus', function() {
            focusEditor = 'emailtemplates_lang_content';
        });
    } else {
        $('#emailtemplates_default_content').on('focus', function() {
            focusEditor = 'emailtemplates_default_content';
        });
        $('#emailtemplates_lang_content').on('focus', function() {
            focusEditor = 'emailtemplates_lang_content';
        });
    }
    {literal}
    $(document).delegate('[data-toggle="fchoose"]', 'click', function(e) {
        e.preventDefault();
        if (typeof CKEDITOR != 'undefined') {
            CKEDITOR.instances[focusEditor].insertHtml(' {' + $(this).data('value') + '}');
        } else {
            $('#' + focusEditor).val($('#' + focusEditor).val() + ' {' + $(this).data('value') + '}');
        }
    });
    {/literal}
});
</script>

<div class="d-none" id="tpl-attach-temp">
    <div class="d-flex align-items-center py-1">
        <div class="flex-grow-1 flex-shrink-1">
            <input type="text" class="form-control form-control-sm" name="attachments[]" value="">
        </div>
        <div class="flex-grow-0 flex-shrink-0 pl-2">
            <button class="btn btn-secondary btn-input-sm" type="button" data-toggle="browsefile" data-path="{$UPLOAD_PATH}" data-cpath="{$UPLOAD_PATH}"><i class="icon icon-left fas fa-folder-open"></i> {$LANG->get('browse_file')}</button>
        </div>
        <div class="flex-grow-0 flex-shrink-0 pl-2">
            <button class="btn btn-danger btn-input-sm" type="button" data-toggle="attdel"><i class="icon icon-left fas fa-times"></i> {$LANG->get('delete')}</button>
        </div>
    </div>
</div>
