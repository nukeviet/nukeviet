{if not empty($ERROR)}
<div class="alert alert-danger">{"<br />"|implode:$ERROR}</div>
{/if}
<form method="post" action="{$FORM_ACTION}" autocomplete="off" id="form-emailtemplates">
    <div class="row">
        <div class="col-lg-14">
            <div class="panel panel-primary">
                <div class="panel-heading">{$LANG->get('tpl_basic_info')}</div>
                <div class="panel-body form-horizontal">
                    <div class="form-group">
                        <label class="col-xs-24 col-sm-6 control-label" for="tpl_send_name">{$LANG->get('tpl_send_name')}</label>
                        <div class="col-xs-24 col-sm-16">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 flex-shrink-1">
                                    <input type="text" class="form-control" id="tpl_send_name" name="send_name" value="{$DATA['send_name']}">
                                </div>
                                <div class="flex-grow-1 flex-shrink-1 ml-2">
                                    <input type="text" class="form-control" id="tpl_send_email" name="send_email" value="{$DATA['send_email']}" placeholder="name@email.com">
                                </div>
                            </div>
                            <span class="help-block mb-0 text-muted">{$LANG->get('tpl_send_name_help')}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-24 col-sm-6 control-label" for="send_cc">{$LANG->get('tpl_send_cc')}</label>
                        <div class="col-xs-24 col-sm-16">
                            <input type="text" class="form-control" id="send_cc" name="send_cc" value="{$DATA['send_cc']}">
                            <span class="help-block mb-0 text-muted">{$LANG->get('list_email_help')}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-24 col-sm-6 control-label" for="send_bcc">{$LANG->get('tpl_send_bcc')}</label>
                        <div class="col-xs-24 col-sm-16">
                            <input type="text" class="form-control" id="send_bcc" name="send_bcc" value="{$DATA['send_bcc']}">
                            <span class="help-block mb-0 text-muted">{$LANG->get('list_email_help')}</span>
                        </div>
                    </div>
                    {if not $DATA['is_system']}
                    {* Tên và danh mục không thể sửa đổi đối với các trường hệ thống *}
                    {foreach from=$DATA['title'] key=lang item=title}
                    <div class="form-group">
                        <label class="col-xs-24 col-sm-6 control-label" for="tpl_title_{$lang}">{$LANG->get('tpl_title')} ({$LANGUAGE_ARRAY[$lang].name}){if $lang eq $NV_LANG_DATA} <i class="text-danger">(*)</i>{/if}</label>
                        <div class="col-xs-24 col-sm-16">
                            <input type="text" class="form-control" id="tpl_title_{$lang}" name="title[{$lang}]" value="{$title}">
                        </div>
                    </div>
                    {/foreach}
                    <div class="form-group">
                        <label class="col-xs-24 col-sm-6 control-label" for="tpl_catid">{$LANG->get('tpl_incat')}</label>
                        <div class="col-xs-24 col-sm-16">
                            <select class="select2 form-control" id="tpl_catid" name="catid">
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
                    <div class="form-group">
                        <label class="col-xs-24 col-sm-6 control-label">{$LANG->get('tpl_pluginsys')}</label>
                        <div class="col-xs-24 col-sm-16">
                            <select class="select2 form-control" multiple="multiple" disabled="disabled" tabindex="-1" id="tpl_sys_pids">
                                {foreach from=$PLUGINS item=row}
                                {if in_array($row.pid, $DATA['sys_pids'])}<option value="{$row.pid}" selected="selected">{if empty({$row.plugin_module_name})}{$LANG->get('system')}{else}Module {$row.plugin_module_name}{/if}:{$row.plugin_file}</option>{/if}
                                {/foreach}
                            </select>
                            <div class="help-block mb-0 text-muted">{$LANG->get('tpl_pluginsys_help')}</div>
                        </div>
                    </div>
                    {/if}
                    <div class="form-group">
                        <label class="col-xs-24 col-sm-6 control-label" for="tpl_pids">{$LANG->get('tpl_plugin')}</label>
                        <div class="col-xs-24 col-sm-16">
                            <select class="select2 form-control" id="tpl_pids" name="pids[]" multiple="multiple">
                                {foreach from=$PLUGINS item=row}
                                {if not in_array($row.pid, $DATA['sys_pids'])}
                                <option value="{$row.pid}"{if in_array($row.pid, $DATA['pids'])} selected="selected"{/if}>{if empty({$row.plugin_module_name})}{$LANG->get('system')}{else}Module {$row.plugin_module_name}{/if}:{$row.plugin_file}</option>
                                {/if}
                                {/foreach}
                            </select>
                            <div class="help-block mb-0 text-muted">{$LANG->get('tpl_plugin_help')}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-10">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 flex-shrink-1">{$LANG->get('tpl_attachments')}</div>
                        <div class="flex-grow-0 flex-shrink-0 pl-2">
                            <button class="btn btn-xs btn-default" type="button" data-toggle="attadd" data-size="{sizeof($DATA['attachments'])}" disabled="disabled"><i class="fa fa-plus"></i> {$LANG->get('add')}</button>
                        </div>
                    </div>
                </div>
                <div class="panel-body" id="tpl-attachments">
                    {foreach from=$DATA['attachments'] key=key item=row}
                    <div class="input-group my-1">
                        <input type="text" class="form-control" name="attachments[]" value="{if not empty($row)}{$NV_BASE_SITEURL}{$UPLOAD_PATH}/{$row}{/if}" id="tpl_att{$key}">
                        <div class="input-group-btn">
                            <button class="btn btn-default" type="button" data-toggle="selectfile" data-target="tpl_att{$key}" data-path="{$UPLOAD_PATH}" data-type="file"><i class="fa fa-folder-open"></i> {$LANG->get('browse_file')}</button>
                            <button class="btn btn-danger" type="button" data-toggle="attdel"><i class="fa fa-times"></i> {$LANG->get('delete')}</button>
                        </div>
                    </div>
                    {/foreach}
                </div>
            </div>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    {$LANG->get('adv_info')}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label class="control-label">{$LANG->get('tpl_is_plaintext')}</label>
                        <div>
                            <label>
                                <input type="checkbox" name="is_plaintext" value="1"{if $DATA['is_plaintext']} checked="checked"{/if}> {$LANG->get('tpl_is_plaintext_help')}
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">{$LANG->get('tpl_is_disabled')}</label>
                        <div>
                            <label>
                                <input type="checkbox" name="is_disabled" value="1"{if $DATA['is_disabled']} checked="checked"{/if}> {$LANG->get('tpl_is_disabled_help')}
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">{$LANG->get('tpl_is_selftemplate')}</label>
                        <div>
                            <label>
                                <input type="checkbox" name="is_selftemplate" value="1"{if $DATA['is_selftemplate']} checked="checked"{/if}> {$LANG->get('tpl_is_selftemplate_help')}
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="element_mailtpl">{$LANG->get('tpl_mailtpl')}</label>
                        <select class="form-control" name="mailtpl" id="element_mailtpl">
                            <option value="">{$LANG->getModule('default')}</option>
                            {foreach from=$ARRAY_MAILTPL item=mailtpl}
                            <option value="{$mailtpl}"{if $mailtpl eq $DATA.mailtpl} selected="selected"{/if}>{$mailtpl}</option>
                            {/foreach}
                        </select>
                    </div>
                    {if not empty($DATA.module_name)}
                    <div class="form-group mb-0">
                        <label class="control-label text-danger" for="element_update_for">{$LANG->getModule('update_for')}</label>
                        <select class="form-control" name="update_for" id="element_update_for">
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
    <div class="panel panel-primary">
        <div class="panel-heading">
            {$LANG->get('default_content')} <a href="#" data-toggle="tooltip" title="{$LANG->get('default_content_info')}"><i class="fa fa-info-circle text-white"></i></a>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label for="default_subject" class="control-label">{$LANG->get('tpl_subject')} <i class="text-danger">(*)</i>:</label>
                <input type="text" class="form-control" id="default_subject" name="default_subject" value="{$DATA['default_subject']}">
            </div>
            <div class="form-group">
                <label for="emailtemplates_default_content" class="control-label">{$LANG->get('tpl_content')} <i class="text-danger">(*)</i>:</label>
                {$DATA.default_content}
            </div>
            <button class="btn btn-primary" type="submit">{$LANG->get('submit')}</button>
        </div>
    </div>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1 flex-shrink-1">{$LANG->get('lang_content')}</div>
                <div class="flex-grow-0 flex-shrink-0 pl-2">
                    <div class="btn-group">
                        <button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span data-toggle="collapsecontentlabel">{$LANGUAGE_ARRAY[$DATA.showlang].name}</span> <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right">
                            {foreach from=$DATA.content key=lang item=bodycontent}
                            <li><a href="#" data-toggle="collapsecontentchange" data-lang="{$lang}" class="text-black">{$LANGUAGE_ARRAY[$lang].name}</a></li>
                            {/foreach}
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-body">
            {foreach from=$DATA.content key=lang item=bodycontent}
            <div class="collapse{if $lang eq $DATA.showlang} in{/if}" data-toggle="collapsecontent" id="collapse-content-{$lang}">
                <div class="form-group">
                    <label for="lang_subject_{$lang}" class="control-label">{$LANG->getModule('tpl_subject')}:</label>
                    <input type="text" class="form-control" id="lang_subject_{$lang}" name="subject[{$lang}]" value="{$DATA.subject[$lang]}">
                </div>
                <div class="form-group">
                    <label for="emailtemplates_lang_content_{$lang}" class="control-label">{$LANG->get('tpl_content')}:</label>
                    {$bodycontent}
                </div>
            </div>
            {/foreach}
            <button class="btn btn-primary" type="submit">{$LANG->get('submit')}</button>
        </div>
    </div>
    {if $DATA.allow_rollback}
    <div class="panel panel-primary">
        <div class="panel-body">
            <p>{$LANG->getModule('rollback_message')}.</p>
            <button class="btn btn-primary" type="submit" name="submitrollback" value="1">{$LANG->get('submit')}</button>
        </div>
    </div>
    {/if}
    <input type="hidden" name="showlang" value="{$DATA.showlang}">
    <input type="hidden" name="saveform" value="{$smarty.const.NV_CHECK_SESSION}">
</form>

<div class="panel panel-default">
    <div class="panel-heading">
        {$LANG->get('merge_field')} <a href="#" data-toggle="tooltip" title="{$LANG->get('merge_field_help')}"><i class="fa fa-info-circle"></i></a>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-xs-24 col-md-12">
                <div id="merge-fields-content"></div>
            </div>
            <div class="col-xs-24 col-md-12">
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

<link rel="stylesheet" href="{$ASSETS_STATIC_URL}/js/select2/select2.min.css">

<script src="{$ASSETS_STATIC_URL}/js/select2/select2.min.js"></script>
<script src="{$ASSETS_STATIC_URL}/js/select2/i18n/{$NV_LANG_INTERFACE}.js"></script>

<div class="hidden" id="tpl-attach-temp">
    <div class="input-group my-1">
        <input type="text" class="form-control" name="attachments[]" value="">
        <div class="input-group-btn">
            <button class="btn btn-default" type="button" data-toggle="selectfile" data-target="" data-path="{$UPLOAD_PATH}" data-type="file"><i class="fa fa-folder-open"></i> {$LANG->get('browse_file')}</button>
            <button class="btn btn-danger" type="button" data-toggle="attdel"><i class="fa fa-times"></i> {$LANG->get('delete')}</button>
        </div>
    </div>
</div>
