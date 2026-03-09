{if not empty($ERROR)}
<div class="alert alert-danger" role="alert">{$ERROR|join:"<br />"}</div>
{/if}
<form method="post" action="{$FORM_ACTION}" autocomplete="off" id="form-emailtemplates">
    <div class="row g-3 mb-3">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header fw-medium fs-5 py-2">{$LANG->get('tpl_basic_info')}</div>
                <div class="card-body">
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label text-sm-end" for="tpl_send_name">{$LANG->get('tpl_send_name')}</label>
                        <div class="col-sm-8">
                            <div class="d-flex gap-2 align-items-center">
                                <div class="flex-grow-1 flex-shrink-1">
                                    <input type="text" class="form-control" id="tpl_send_name" name="send_name" value="{$DATA['send_name']}">
                                </div>
                                <div class="flex-grow-1 flex-shrink-1">
                                    <input type="text" class="form-control" id="tpl_send_email" name="send_email" value="{$DATA['send_email']}" placeholder="name@email.com">
                                </div>
                            </div>
                            <div class="form-text">{$LANG->get('tpl_send_name_help')}</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label text-sm-end" for="send_cc">{$LANG->get('tpl_send_cc')}</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="send_cc" name="send_cc" value="{$DATA['send_cc']}">
                            <div class="form-text">{$LANG->get('list_email_help')}</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label text-sm-end" for="send_bcc">{$LANG->get('tpl_send_bcc')}</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="send_bcc" name="send_bcc" value="{$DATA['send_bcc']}">
                            <div class="form-text">{$LANG->get('list_email_help')}</div>
                        </div>
                    </div>
                    {if not $DATA['is_system']}
                    {* Tên và danh mục không thể sửa đổi đối với các trường hệ thống *}
                    {foreach from=$DATA['title'] key=lang item=title}
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label text-sm-end" for="tpl_title_{$lang}">{$LANG->get('tpl_title')} ({$LANGUAGE_ARRAY[$lang].name}){if $lang eq $NV_LANG_DATA} <i class="text-danger">(*)</i>{/if}</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="tpl_title_{$lang}" name="title[{$lang}]" value="{$title}">
                        </div>
                    </div>
                    {/foreach}
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label text-sm-end" for="tpl_catid">{$LANG->get('tpl_incat')}</label>
                        <div class="col-sm-8">
                            <select class="select2 form-select" id="tpl_catid" name="catid">
                                <option value="0">--</option>
                                {foreach from=$CATS item=row}
                                <option value="{$row.catid}"{if $row.catid eq $DATA['catid']} selected="selected"{/if}>{$row.title}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                    {/if}
                    {* Các plugin của hệ thống bắt buộc chọn và không thể sửa *}
                    {if not empty($DATA.sys_pids)}
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label text-sm-end">{$LANG->get('tpl_pluginsys')}</label>
                        <div class="col-sm-8">
                            <select class="select2 form-control" multiple="multiple" disabled="disabled" tabindex="-1" id="tpl_sys_pids">
                                {foreach from=$PLUGINS item=row}
                                {if in_array($row.pid, $DATA['sys_pids'])}<option value="{$row.pid}" selected="selected">{if empty({$row.plugin_module_name})}{$LANG->get('system')}{else}Module {$row.plugin_module_name}{/if}:{$row.plugin_file}</option>{/if}
                                {/foreach}
                            </select>
                            <div class="form-text">{$LANG->get('tpl_pluginsys_help')}</div>
                        </div>
                    </div>
                    {/if}
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label text-sm-end" for="tpl_pids">{$LANG->get('tpl_plugin')}</label>
                        <div class="col-sm-8">
                            <select class="select2 form-control" id="tpl_pids" name="pids[]" multiple="multiple">
                                {foreach from=$PLUGINS item=row}
                                {if not in_array($row.pid, $DATA['sys_pids'])}
                                <option value="{$row.pid}"{if in_array($row.pid, $DATA['pids'])} selected="selected"{/if}>{if empty({$row.plugin_module_name})}{$LANG->get('system')}{else}Module {$row.plugin_module_name}{/if}:{$row.plugin_file}</option>
                                {/if}
                                {/foreach}
                            </select>
                            <div class="form-text">{$LANG->get('tpl_plugin_help')}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card mb-3">
                <div class="card-header fw-medium fs-5 py-2">
                    <div class="d-flex align-items-center gap-2">
                        <div class="flex-grow-1 flex-shrink-1">{$LANG->get('tpl_attachments')}</div>
                        <div class="flex-grow-0 flex-shrink-0">
                            <button class="btn btn-sm btn-secondary" type="button" data-toggle="attadd" data-size="{sizeof($DATA['attachments'])}" disabled="disabled"><i class="fa-solid fa-plus"></i> {$LANG->get('add')}</button>
                        </div>
                    </div>
                </div>
                <div class="card-body vstack gap-2" id="tpl-attachments">
                    {foreach from=$DATA['attachments'] key=key item=row}
                    <div class="input-group">
                        <input type="text" class="form-control" name="attachments[]" value="{if not empty($row)}{$NV_BASE_SITEURL}{$UPLOAD_PATH}/{$row}{/if}" id="tpl_att{$key}">
                        <button class="btn btn-secondary" type="button" data-toggle="selectfile" data-target="tpl_att{$key}" data-path="{$UPLOAD_PATH}" data-type="file"><i class="fa-regular fa-folder-open"></i> {$LANG->get('browse_file')}</button>
                        <button class="btn btn-danger" type="button" data-toggle="attdel"><i class="fa-solid fa-xmark"></i> {$LANG->get('delete')}</button>
                    </div>
                    {/foreach}
                </div>
            </div>
            <div class="card">
                <div class="card-header fw-medium fs-5 py-2">
                    {$LANG->get('adv_info')}
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label" for="element_is_plaintext">{$LANG->get('tpl_is_plaintext')}</label>
                        <div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="element_is_plaintext" name="is_plaintext" value="1"{if $DATA['is_plaintext']} checked{/if}>
                                <label class="form-check-label" for="element_is_plaintext">{$LANG->get('tpl_is_plaintext_help')}</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="element_is_disabled">{$LANG->get('tpl_is_disabled')}</label>
                        <div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="element_is_disabled" name="is_disabled" value="1"{if $DATA['is_disabled']} checked{/if}>
                                <label class="form-check-label" for="element_is_disabled">{$LANG->get('tpl_is_disabled_help')}</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="element_is_selftemplate">{$LANG->get('tpl_is_selftemplate')}</label>
                        <div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="element_is_selftemplate" name="is_selftemplate" value="1"{if $DATA['is_selftemplate']} checked{/if}>
                                <label class="form-check-label" for="element_is_selftemplate">{$LANG->get('tpl_is_selftemplate_help')}</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="element_mailtpl">{$LANG->get('tpl_mailtpl')}</label>
                        <select class="form-select" name="mailtpl" id="element_mailtpl">
                            <option value="">{$LANG->getModule('default')}</option>
                            {foreach from=$ARRAY_MAILTPL item=mailtpl}
                            <option value="{$mailtpl}"{if $mailtpl eq $DATA.mailtpl} selected="selected"{/if}>{$mailtpl}</option>
                            {/foreach}
                        </select>
                    </div>
                    {if not empty($DATA.module_name)}
                    <div>
                        <label class="form-label text-danger" for="element_update_for">{$LANG->getModule('update_for')}</label>
                        <select class="form-select" name="update_for" id="element_update_for">
                            {foreach from=$UPDATE_FOR key=key item=value}
                            <option value="{$key}"{if $key eq $DATA.update_for} selected="selected"{/if}>{$value}</option>
                            {/foreach}
                        </select>
                    </div>
                    {/if}
                </div>
            </div>
        </div>
    </div>
    <div class="card mb-3">
        <div class="card-header fw-medium fs-5 py-2">
            {$LANG->get('default_content')} <a href="#" data-toggle="tooltip" title="{$LANG->get('default_content_info')}"><i class="fa fa-info-circle text-white"></i></a>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label for="default_subject" class="form-label">{$LANG->get('tpl_subject')} <i class="text-danger">(*)</i>:</label>
                <input type="text" class="form-control" id="default_subject" name="default_subject" value="{$DATA['default_subject']}">
            </div>
            <div class="mb-3">
                <label for="emailtemplates_default_content" class="form-label">{$LANG->get('tpl_content')} <i class="text-danger">(*)</i>:</label>
                {$DATA.default_content}
            </div>
            <button class="btn btn-primary" type="submit">{$LANG->get('submit')}</button>
        </div>
    </div>
    <div class="card">
        <div class="card-header fw-medium fs-5 py-2">
            <div class="d-flex align-items-center gap-2">
                <div class="flex-grow-1 flex-shrink-1">{$LANG->get('lang_content')}</div>
                <div class="flex-grow-0 flex-shrink-0">
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <span data-toggle="collapsecontentlabel">{$LANGUAGE_ARRAY[$DATA.showlang].name}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            {foreach from=$DATA.content key=lang item=bodycontent}
                            <li><a href="#" data-toggle="collapsecontentchange" data-lang="{$lang}" class="dropdown-item">{$LANGUAGE_ARRAY[$lang].name}</a></li>
                            {/foreach}
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            {foreach from=$DATA.content key=lang item=bodycontent}
            <div class="collapse{if $lang eq $DATA.showlang} show{/if}" data-toggle="collapsecontent" id="collapse-content-{$lang}">
                <div class="mb-3">
                    <label for="lang_subject_{$lang}" class="form-label">{$LANG->getModule('tpl_subject')}:</label>
                    <input type="text" class="form-control" id="lang_subject_{$lang}" name="subject[{$lang}]" value="{$DATA.subject[$lang]}">
                </div>
                <div class="mb-3">
                    <label for="emailtemplates_lang_content_{$lang}" class="form-label">{$LANG->get('tpl_content')}:</label>
                    {$bodycontent}
                </div>
            </div>
            {/foreach}
            <button class="btn btn-primary" type="submit">{$LANG->get('submit')}</button>
        </div>
    </div>
    {if $DATA.allow_rollback}
    <div class="card">
        <div class="card-body">
            <p>{$LANG->getModule('rollback_message')}.</p>
            <button class="btn btn-primary" type="submit" name="submitrollback" value="1">{$LANG->get('submit')}</button>
        </div>
    </div>
    {/if}
    <input type="hidden" name="showlang" value="{$DATA.showlang}">
    <input type="hidden" name="saveform" value="{$smarty.const.NV_CHECK_SESSION}">
</form>
<div class="card mt-3">
    <div class="card-header fw-medium fs-5 py-2">
        {$LANG->get('merge_field')} <span class="text-primary" data-bs-toggle="tooltip" data-bs-title="{$LANG->get('merge_field_help')}" title="{$LANG->get('merge_field_help')}"><i class="fa-solid fa-circle-info"></i></span>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div id="merge-fields-content"></div>
            </div>
            <div class="col-md-6">
                <h4 class="mb-1"><strong>{$LANG->get('merge_field_guild1')}</strong></h4>
                {$LANG->get('merge_field_guild2')}
                {literal}<pre><code>{if $username eq "nukeviet"}<br>Is Admin<br>{/if}</code></pre>{/literal}
                {$LANG->get('merge_field_guild3')}.
                <h4 class="mb-1 mt-2"><strong>{$LANG->get('merge_field_guild4')}</strong></h4>
                {$LANG->get('merge_field_guild5')}
                {literal}<pre><code>{foreach from=$array_users item=user}<br>User: {$user}<br>{/foreach}</code></pre>{/literal}
                {$LANG->get('merge_field_guild6')}.
            </div>
        </div>
    </div>
</div>

<script src="{$ASSETS_STATIC_URL}/js/select2/select2.min.js"></script>
<script src="{$ASSETS_STATIC_URL}/js/select2/i18n/{$NV_LANG_INTERFACE}.js"></script>

<div class="d-none" id="tpl-attach-temp">
    <div class="input-group">
        <input type="text" class="form-control" name="attachments[]" value="">
        <button class="btn btn-secondary" type="button" data-toggle="selectfile" data-target="" data-path="{$UPLOAD_PATH}" data-type="file"><i class="fa-regular fa-folder-open"></i> {$LANG->get('browse_file')}</button>
        <button class="btn btn-danger" type="button" data-toggle="attdel"><i class="fa-solid fa-xmark"></i> {$LANG->get('delete')}</button>
    </div>
</div>
