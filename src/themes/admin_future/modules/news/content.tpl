<script src="{$smarty.const.ASSETS_STATIC_URL}/js/select2/select2.min.js"></script>
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/select2/i18n/{$smarty.const.NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{$smarty.const.ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{$smarty.const.ASSETS_STATIC_URL}/js/language/jquery.ui.datepicker-{$smarty.const.NV_LANG_INTERFACE}.js"></script>
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/jquery/jquery.cookie.js"></script>
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/flatpickr/flatpickr.min.js"></script>
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/language/flatpickr-{$smarty.const.NV_LANG_INTERFACE}.js"></script>
{if not $IS_SUBMIT and $TOTAL_NEWS_CURRENT eq $smarty.const.NV_MIN_MEDIUM_SYSTEM_ROWS and $DATA.mode eq 'add'}
{* Thông báo vượt quá hệ thống lớn *}
<div class="alert alert-info" role="alert">
    {$LANG->getModule('large_sys_message', $TOTAL_NEWS_CURRENT|dnumber)}
</div>
{/if}
{if not empty($ERROR)}
<div class="alert alert-danger" role="alert">{$ERROR|join:"<br />"}</div>
{/if}
{if $RESTORE_ID and not $IS_SUBMIT}
{* Tự động submit form khôi phục phiên bản của bài đăng *}
<div class="alert alert-info d-flex align-items-center gap-1" role="alert">
    <div class="spinner-border spinner-border-sm" role="status">
        <span class="visually-hidden">{$LANG->getGlobal('wait_page_load')}</span>
    </div>
    {$LANG->getModule('history_recovering')}
</div>
{/if}
<form id="form-news-content" method="post" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" novalidate
    data-mdata="{$MODULE_DATA}" data-editor="{if $HAS_EDITOR}1{else}0{/if}"
    data-editor-hometext="{if $HAS_EDITOR and not empty($MCONFIG.htmlhometext)}1{else}0{/if}"
    data-auto-submit="{if $RESTORE_ID and not $IS_SUBMIT}1{else}0{/if}"
    data-auto-save="{if not empty($MCONFIG.auto_save)}1{else}0{/if}"
    data-is-edit="{if $DATA.mode eq 'edit'}1{else}0{/if}"
    data-id="{$DATA.id}"
    data-draft-id="{$DATA.draft_id ?? 0}"
    data-last-data-saved="{$smarty.const.NV_CURRENTTIME}"
    data-notice-empty-alias="{$LANG->getModule('alias_empty_notice')}"
    data-notice-empty-tags="{$LANG->getModule('content_tags_empty')}.{if not empty($MCONFIG.auto_tags)} {$LANG->getModule('content_tags_empty_auto')}.{/if}"
>
    <div class="row g-3">
        <div class="col-lg-8 col-xxl-9">
            <div class="alert alert-danger d-none" id="show_error"></div>
            {if not empty($REPORTLIST)}
            <div class="card border-danger border-1 mb-3">
                <div class="card-header border-0 text-bg-danger fw-medium" role="button" data-bs-toggle="collapse" data-bs-target="#reportlist-body" aria-expanded="{$REPORT_ID ? 'true' : 'false'}" aria-controls="reportlist-body">
                    <i class="fa-solid fa-triangle-exclamation"></i> {$LANG->getModule('report')} (<strong>{count($REPORTLIST)|dnumber}</strong>)
                </div>
                <div class="collapse{$REPORT_ID ? ' show' : ''}" id="reportlist-body">
                    <div class="accordion list-report" id="reportlist-items" data-del-confirm="{$LANG->getModule('report_del_confirm')}">
                        {foreach from=$REPORTLIST item=report}
                        <div class="accordion-item border-start-0 border-end-0">
                            <div class="accordion-header">
                                <button class="accordion-button fw-medium px-3{$REPORT_ID eq $report.id ? '' : ' collapsed'}" type="button" data-bs-toggle="collapse" data-bs-target="#reportlist-item-{$report.id}" aria-expanded="{$REPORT_ID eq $report.id ? 'true' : 'false'}" aria-controls="reportlist-item-{$report.id}">
                                    {assign var="report_title" value=$report.orig_content|text_split:50}
                                    {$report_title.0}{not empty($report_title.1) ? '...' : ''}
                                </button>
                            </div>
                            <div id="reportlist-item-{$report.id}" class="accordion-collapse collapse{$REPORT_ID eq $report.id ? ' show' : ''}" data-bs-parent="#accordionExample">
                                <div class="accordion-body p-3">
                                    <span class="bg-body-tertiary border rounded-1 p-1">
                                        {$report.post_time|ddatetime}, {$LANG->getModule('post_ip')}: {$report.post_ip}{if not empty($report.post_email)}, {$LANG->getModule('post_email')}: {$report.post_email}{/if}
                                    </span>
                                    <div class="mb-3 mt-3">
                                        <div class="mb-1 fw-medium">{$LANG->getModule('error_text')}:</div>
                                        <div class="bg-danger-subtle text-danger-emphasis p-2 rounded-2">{$report.orig_content}</div>
                                    </div>
                                    {if not empty($report.repl_content)}
                                    <div class="mb-3">
                                        <div class="mb-1 fw-medium">{$LANG->getModule('proposal_text')}:</div>
                                        <div class="bg-info-subtle text-info-emphasis p-2 rounded-2">{$report.repl_content}</div>
                                    </div>
                                    {/if}
                                    <div class="hstack gap-2 justify-content-end flex-wrap">
                                        <button type="button" class="btn btn-secondary" data-toggle="report_del_action" data-send-mail="no" data-id="{$report.id}"><i class="fa-solid fa-trash-can text-danger" data-icon="fa-trash-can"></i> {$LANG->getGlobal('delete')}</button>
                                        <button type="button" class="btn btn-secondary" data-toggle="report_del_action" data-send-mail="yes" data-id="{$report.id}"><i class="fa-solid fa-trash-can-arrow-up text-danger" data-icon="fa-trash-can-arrow-up"></i> {$LANG->getModule('report_delete')}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {/foreach}
                    </div>
                </div>
            </div>
            {/if}
            <div class="card mb-3">
                <div class="card-body">
                    <div class="mb-3">
                        <label for="idtitle" class="form-label">{$LANG->getModule('name')} <span class="text-danger">(*)</span>:</label>
                        <div class="position-relative">
                            <input type="text" class="form-control required" id="idtitle" name="title" value="{$DATA.title}" maxlength="250">
                            <div class="invalid-tooltip">{$LANG->getModule('error_title')}</div>
                        </div>
                        <div class="form-text"> {$LANG->getGlobal('length_characters')}: <span id="titlelength" class="fw-bold text-danger">0</span>. {$LANG->getGlobal('title_suggest_max')}.</div>
                    </div>
                    <div class="mb-3">
                        <label for="idalias" class="form-label">{$LANG->getModule('alias')}:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="idalias" name="alias" value="{$DATA.alias}" maxlength="250">
                            <button class="btn btn-secondary" type="button" aria-label="{$LANG->getModule('alias_get')}" data-toggle="getaliaspost" data-auto-alias="{empty($DATA.alias) ? '1' : '0'}" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-title="{$LANG->getModule('alias_get')}"><i class="fa-solid fa-rotate"></i></button>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-7">
                            <div class="mb-3">
                                <label for="homeimg" class="form-label">{$LANG->getModule('content_homeimg')}:</label>
                                <div class="input-group">
                                    <input class="form-control" type="text" name="homeimg" id="homeimg" value="{$DATA.homeimgfile}">
                                    <button type="button" class="btn btn-secondary" aria-label="{$LANG->getGlobal('browse_image')}" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-title="{$LANG->getGlobal('browse_image')}" data-toggle="selectfile" data-target="homeimg" data-path="{$UPLOADS_DIR_USER}" data-currentpath="{$UPLOAD_CURRENT}" data-type="image" data-alt="homeimgalt"><i class="fa-solid fa-file-image"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="mb-3">
                                <label for="imgposition" class="form-label">{$LANG->getModule('imgposition')}:</label>
                                <select class="form-select" name="imgposition" id="imgposition">
                                    {foreach from=$ARRAY_IMGPOSITION key=key item=value}
                                    <option value="{$key}" {if $key eq $DATA.imgposition} selected{/if}>{$value}</option>
                                    {/foreach}
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="homeimgalt" class="form-label">{$LANG->getModule('content_homeimgalt')}:</label>
                        <input type="text" class="form-control" id="homeimgalt" name="homeimgalt" value="{$DATA.homeimgalt}" maxlength="255">
                    </div>
                    <div class="mb-3">
                        <label for="{$MODULE_NAME}_hometext" class="form-label">{$LANG->getModule('content_hometext')}:</label>
                        {if $HAS_EDITOR and not empty($MCONFIG.htmlhometext)}
                        {editor('hometext', '100%', '100px', $DATA.hometext, 'responsive', $UPLOADS_DIR_USER, $UPLOAD_CURRENT)}
                        {else}
                        <textarea class="form-control" id="{$MODULE_NAME}_hometext" name="hometext" rows="5">{$DATA.hometext}</textarea>
                        {/if}
                        <div class="form-text">{$LANG->getModule('content_notehome')}</div>
                    </div>
                    <div class="mb-0">
                        <label for="{$MODULE_NAME}_bodyhtml" class="form-label">{$LANG->getModule('content_bodytext')}<span data-toggle="required-bodyhtml"{if not empty($DATA.external_link)} class="d-none"{/if}> <span class="text-danger">(*)</span></span>:</label>
                        <div class="position-relative">
                            <div data-toggle="container-bodyhtml">
                                {if $HAS_EDITOR}
                                {editor('bodyhtml', '100%', '400px', $DATA.bodyhtml, '', $UPLOADS_DIR_USER, $UPLOAD_CURRENT)}
                                {else}
                                <textarea class="form-control required" id="{$MODULE_NAME}_bodyhtml" name="bodyhtml" rows="15">{$DATA.bodyhtml}</textarea>
                                {/if}
                            </div>
                            <div class="invalid-tooltip">{$LANG->getModule('error_bodytext')}</div>
                        </div>
                        <div class="form-text">{$LANG->getModule('content_bodytext_note')}</div>
                    </div>
                </div>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-header fw-medium fs-5">
                            {$LANG->getModule('content_topic')}
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="newcontent_topicid" class="form-label">{$LANG->getModule('admin_topic_sl')}:</label>
                                <select class="form-select" id="newcontent_topicid" name="topicid">
                                    {foreach from=$DATA_TOPICS key=key item=value}
                                    <option value="{$key}"{if $key eq $DATA.topicid} selected{/if}>{$value}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="mb-0">
                                <label for="topictext" class="form-label">{$LANG->getModule('admin_topic_manual')}:</label>
                                <input type="text" class="form-control" id="topictext" name="topictext" value="{$DATA.topictext}" maxlength="255">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-header fw-medium fs-5">
                            {$LANG->getModule('content_sourceid')}
                        </div>
                        <div class="card-body">
                            <div class="mb-3 position-relative">
                                <label for="newcontent_sourceid" class="form-label">{$LANG->getModule('content_sourceid_guide')}:</label>
                                <input type="text" class="form-control" id="newcontent_sourceid" name="sourcetext" maxlength="255" value="{$DATA.sourcetext}">
                            </div>
                            <div class="mb-0">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" name="external_link" id="external_link"{if not empty($DATA.external_link)} checked{/if}>
                                    <label class="form-check-label" for="external_link">
                                        {$LANG->getModule('content_external_link')}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header fw-medium fs-5">
                    {$LANG->getModule('fileattach')}
                </div>
                <div class="card-body">
                    <div id="newcontent-fileattach" class="row g-2">
                        {foreach from=$FILES item=file}
                        <div class="col-md-6 item">
                            <div class="input-group">
                                <input class="form-control" type="text" name="files[]" id="file_{$file.id}" value="{$file.value}" aria-label="{$LANG->getModule('fileupload')}">
                                <button type="button" data-toggle="selectfile" data-target="file_{$file.id}" data-path="{$UPLOAD_CURRENT}" data-currentpath="{$UPLOAD_CURRENT}" data-type="file" class="btn btn-primary" aria-label="{$LANG->getGlobal('browse_file')}"><i class="fa-solid fa-folder-open"></i></button>
                                <button type="button" class="btn btn-secondary" data-toggle="del_file" aria-label="{$LANG->getGlobal('delete')}" title="{$LANG->getGlobal('delete')}"><i class="fa-solid fa-xmark text-danger"></i></button>
                                <button type="button" class="btn btn-secondary" data-toggle="add_file" aria-label="{$LANG->getGlobal('add')}" title="{$LANG->getGlobal('add')}"><i class="fa-solid fa-plus"></i></button>
                            </div>
                        </div>
                        {/foreach}
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header fw-medium fs-5">
                    {$LANG->getModule('localization')}
                </div>
                <div class="card-body">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" name="enable_localization" value="1" id="enable_localization"{if not empty($DATA.localversions)} checked{/if}>
                        <label class="form-check-label" for="enable_localization">{$LANG->getModule('enable_localization')}</label>
                    </div>
                    {assign var="localversions" value=$DATA.localversions}
                    {if empty($localversions)}
                    {assign var="localversions" value=['' => '']}
                    {/if}
                    <div class="collapse{if not empty($DATA.localversions)} show{/if}" id="localization_sector">
                        <div class="pt-3">
                            <div class="locallist mb-3 vstack gap-2">
                                {foreach from=$localversions key=code item=url}
                                <div class="localitem d-flex gap-2 align-items-center">
                                    <select class="form-select fw-150" name="locallang[]">
                                        <option value="">{$LANG->getModule('select_lang')}</option>
                                        {foreach from=$LANGUES key=lang item=value}
                                        <option value="{$lang}"{if $lang eq $code} selected{/if}>{$value.name}</option>
                                        {/foreach}
                                    </select>
                                    <div class="flex-grow-1 flex-shrink-1">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="locallink[]" value="{$url}" placeholder="{$LANG->getModule('local_url')}..." aria-label="{$LANG->getModule('local_url')}...">
                                            <button class="btn btn-secondary" type="button" data-toggle="del_local" aria-label="{$LANG->getGlobal('delete')}" title="{$LANG->getGlobal('delete')}"><i class="fa-solid fa-xmark text-danger"></i></button>
                                            <button class="btn btn-secondary" type="button" data-toggle="add_local" aria-label="{$LANG->getGlobal('add')}" title="{$LANG->getGlobal('add')}"><i class="fa-solid fa-plus"></i></button>
                                        </div>
                                    </div>
                                </div>
                                {/foreach}
                            </div>
                            <div class="form-text">{$LANG->getModule('select_lang_note')}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-xxl-3">
            <div class="card mb-3">
                <div class="card-header fw-medium fs-5">
                    {$LANG->getModule('content_cat')} <span class="text-danger">(*)</span>
                </div>
                <div class="pb-1 position-relative">
                    <div class="position-relative maxh-300 overflow-hidden catids-items" data-nv-toggle="scroll">
                        <ul class="list-group list-group-flush" data-toggle="catids">
                            {foreach from=$LIST_CATS key=key item=value}
                            <li class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between gap-1">
                                    <div style="padding-left: {$value.space * 16}px;">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" data-toggle="contentCatids" name="catids[]" value="{$value.catid}" id="catid_{$value.catid}" {if $value.checked} checked{/if}{if $value.disabled} disabled{/if}>
                                            <label class="form-check-label" for="catid_{$value.catid}">
                                                {$value.title}
                                            </label>
                                        </div>
                                    </div>
                                    <div>
                                        <input class="form-check-input{if empty($value.visible)} invisible{/if}" type="radio" name="catid" value="{$value.catid}"{if $value.catid eq $DATA.catid} checked{/if} aria-label="{$LANG->getModule('content_checkcat')}">
                                    </div>
                                </div>
                            </li>
                            {/foreach}
                        </ul>
                    </div>
                    <div class="invalid-tooltip">{$LANG->getModule('error_cat')}</div>
                </div>
            </div>
            {if not empty($LIST_BLOCKS)}
            <div class="card mb-3">
                <div class="card-header fw-medium fs-5">
                    {$LANG->getModule('content_block')}
                </div>
                <div class="card-body">
                    {foreach from=$LIST_BLOCKS key=key item=value}
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="{$key}" name="bids[]" id="bids_{$key}" {if in_array($key, $DATA_BLOCKS)} checked{/if}>
                        <label class="form-check-label" for="bids_{$key}">
                            {$value}
                        </label>
                    </div>
                    {/foreach}
                </div>
            </div>
            {/if}
            <div class="card mb-3">
                <div class="card-header py-2">
                    <div class="d-flex gap-2 justify-content-between align-items-center">
                        <div class="fw-medium fs-5 text-truncate">{$LANG->getModule('content_keyword')}</div>
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-title="{$LANG->getModule('keywords_auto_create')}" aria-label="{$LANG->getModule('keywords_auto_create')}" data-toggle="keywords_auto_create"><i class="fa-solid fa-key" data-icon="fa-key"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <select id="newcontent_keywords" class="form-control" aria-label="{$LANG->getModule('content_keyword')}" name="keywords[]" multiple data-placeholder="{$LANG->getModule('input_keyword')}">
                        {foreach from=$DATA.keywords item=value}
                        <option value="{$value}" selected>{$value}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header py-2">
                    <div class="d-flex gap-2 justify-content-between align-items-center">
                        <div class="fw-medium fs-5 text-truncate">{$LANG->getModule('content_tag')}</div>
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-title="{$LANG->getModule('tags_auto_create')}" aria-label="{$LANG->getModule('tags_auto_create')}" data-toggle="tags_auto_create"><i class="fa-solid fa-tags" data-icon="fa-tags"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <select id="newcontent_tags" class="form-control" aria-label="{$LANG->getModule('content_tag')}" name="tags[]" multiple data-placeholder="{$LANG->getModule('input_tag')}">
                        {foreach from=$DATA.tags item=value}
                        <option value="{$value}" selected>{$value}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header fw-medium fs-5">
                    {$LANG->getModule('author')}
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="newcontent_internal_authors" class="form-label">{$LANG->getModule('content_internal_author')}:</label>
                        <select id="newcontent_internal_authors" class="form-control" aria-label="{$LANG->getModule('content_internal_author')}" name="internal_authors[]" multiple data-placeholder="{$LANG->getModule('input_pseudonym')}">
                            {foreach from=$DATA.internal_authors item=author_id}
                            <option value="{$author_id}" selected>{isset($AUTHORS_LIST[$author_id]) ? {$AUTHORS_LIST[$author_id].pseudonym} : 'N/A'}</option>
                            {/foreach}
                        </select>
                    </div>
                    <div class="mb-0">
                        <label for="content_author" class="form-label">{$LANG->getModule('content_author')}:</label>
                        <input type="text" class="form-control" id="content_author" name="author" value="{$DATA.author}" maxlength="255">
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header fw-medium fs-5">
                    {$LANG->getModule('related_articles')}
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <select id="newcontent_related_ids" data-id="{$DATA.id}" class="form-control" aria-label="{$LANG->getModule('related_articles')}" name="related_ids[]" multiple data-placeholder="{$LANG->getModule('related_articles_ph')}">
                            {foreach from=$RELATED_NEWS item=news}
                            <option value="{$news.id}" selected>{$news.title}</option>
                            {/foreach}
                        </select>
                    </div>
                    <div class="mb-0">
                        <label for="related_pos" class="form-label">{$LANG->getModule('showtooltip_position')}:</label>
                        <select id="related_pos" name="related_pos" class="form-select">
                            {for $pos=0 to 2}
                            <option value="{$pos}"{if $pos eq $DATA.related_pos} selected{/if}>{$LANG->getModule("related_articles_p`$pos`")}</option>
                            {/for}
                        </select>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header fw-medium fs-5">
                    {$LANG->getModule('content_extra')}
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <div class="form-check form-switch mb-1">
                            <input class="form-check-input" type="checkbox" role="switch" name="inhome" value="1" id="inhome" {if not empty($DATA.inhome)} checked{/if}>
                            <label class="form-check-label" for="inhome">
                                {$LANG->getModule('content_inhome')}
                            </label>
                        </div>
                        {if not empty($MCONFIG.allowed_rating)}
                        <div class="form-check form-switch mb-1">
                            <input class="form-check-input" type="checkbox" role="switch" name="allowed_rating" value="1" id="allowed_rating" {if not empty($DATA.allowed_rating)} checked{/if}>
                            <label class="form-check-label" for="allowed_rating">
                                {$LANG->getModule('content_allowed_rating')}
                            </label>
                        </div>
                        {else}
                        <input type="hidden" name="allowed_rating" value="{$DATA.allowed_rating}">
                        {/if}
                        <div class="form-check form-switch mb-1">
                            <input class="form-check-input" type="checkbox" role="switch" name="allowed_send" value="1" id="allowed_send" {if not empty($DATA.allowed_send)} checked{/if}>
                            <label class="form-check-label" for="allowed_send">
                                {$LANG->getModule('content_allowed_send')}
                            </label>
                        </div>
                        <div class="form-check form-switch mb-1">
                            <input class="form-check-input" type="checkbox" role="switch" name="allowed_print" value="1" id="allowed_print" {if not empty($DATA.allowed_print)} checked{/if}>
                            <label class="form-check-label" for="allowed_print">
                                {$LANG->getModule('content_allowed_print')}
                            </label>
                        </div>
                        <div class="form-check form-switch mb-1">
                            <input class="form-check-input" type="checkbox" role="switch" name="allowed_save" value="1" id="allowed_save" {if not empty($DATA.allowed_save)} checked{/if}>
                            <label class="form-check-label" for="allowed_save">
                                {$LANG->getModule('content_allowed_save')}
                            </label>
                        </div>
                        <div class="form-check form-switch mb-1">
                            <input class="form-check-input" type="checkbox" role="switch" name="copyright" value="1" id="copyright" {if not empty($DATA.copyright)} checked{/if}>
                            <label class="form-check-label" for="copyright">
                                {$LANG->getModule('content_copyright')}
                            </label>
                        </div>
                    </div>
                    <div class="mb-0">
                        <label for="auto_nav" class="form-label">{$LANG->getModule('auto_nav')}: <i class="fa-solid fa-circle-info text-info" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-title="{$LANG->getModule('auto_nav_note')}" aria-label="{$LANG->getModule('auto_nav_note')}" title="{$LANG->getModule('auto_nav_note')}"></i></label>
                        <select class="form-select" name="auto_nav" id="auto_nav">
                            {for $i=0 to 3}
                            <option value="{$i}"{if $i eq $DATA.auto_nav} selected{/if}>{$LANG->getModule("auto_nav`$i`")}</option>
                            {/for}
                        </select>
                    </div>
                </div>
            </div>
            {if not empty($MCONFIG.instant_articles_active)}
            <div class="card mb-3">
                <div class="card-header fw-medium fs-5">
                    {$LANG->getModule('content_insart')}
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="instant_active" name="instant_active"{if not empty($DATA.instant_active)} checked{/if}>
                            <label class="form-check-label" for="instant_active">
                                {$LANG->getModule('content_instant_active')}
                            </label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="instant_template" class="form-label">{$LANG->getModule('content_instant_template')}:</label>
                        <input type="text" class="form-control" id="instant_template" name="instant_template" value="{$DATA.instant_template}" maxlength="255" placeholder="{$LANG->getModule('content_instant_templatenote')}">
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="1" id="instant_creatauto" name="instant_creatauto"{if not empty($DATA.instant_creatauto)} checked{/if}>
                        <label class="form-check-label" for="instant_creatauto">
                            {$LANG->getModule('content_instant_creatauto')}
                        </label>
                    </div>
                </div>
            </div>
            {/if}
        </div>
    </div>
    <div class="accordion mb-3" id="newcontent-advanced-options">
        <div class="accordion-item">
            <div class="accordion-header">
                <button class="accordion-button fw-medium fs-5 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#newcontent-advanced-body" aria-expanded="false" aria-controls="newcontent-advanced-body">
                    {$LANG->getModule('content_advfeature')}
                </button>
            </div>
            <div id="newcontent-advanced-body" class="accordion-collapse collapse" data-bs-parent="#newcontent-advanced-options">
                <div class="accordion-body pb-0">
                    <div class="row g-3">
                        <div class="col-xl-4 col-xxl-3">
                            <div class="mb-3">
                                <label for="idtitlesite" class="form-label">{$LANG->getModule('titlesite')}:</label>
                                <input type="text" class="form-control" id="idtitlesite" name="titlesite" value="{$DATA.titlesite}" maxlength="250">
                                <div class="form-text"> {$LANG->getGlobal('length_characters')}: <span id="titlesitelength" class="fw-bold text-danger">0</span>. {$LANG->getGlobal('title_suggest_max')}.</div>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">{$LANG->getModule('content_description')}:</label>
                                <textarea class="form-control" id="description" name="description">{$DATA.description}</textarea>
                                <div class="form-text"> {$LANG->getGlobal('length_characters')}: <span id="descriptionlength" class="fw-bold text-danger">0</span>. {$LANG->getGlobal('description_suggest_max')}.</div>
                            </div>
                            <div class="mb-3">
                                <label for="schema_type" class="form-label">{$LANG->getModule('content_schema_type')} <a href="#" data-bs-toggle="tooltip" data-bs-title="{$LANG->getModule('setting_schema_type_help')}" data-bs-trigger="focus hover" aria-label="{$LANG->getModule('setting_schema_type_help')}"><i class="fa-solid fa-circle-info"></i></a>:</label>
                                <select class="form-select" name="schema_type" id="schema_type">
                                    {foreach from=$SCHEMA_TYPES key=key item=value}
                                    <option value="{$key}"{if $key eq $DATA.schema_type} selected{/if}>{$value}</option>
                                    {/foreach}
                                </select>
                            </div>
                        </div>
                        {if not empty($ARRAY_VOICES)}
                        <div class="col-lg-6 col-xl-4 col-xxl-3">
                            <div class="form-label">{$LANG->getModule('content_voice')}:</div>
                            {foreach from=$ARRAY_VOICES key=key item=value}
                            <div class="mb-3">
                                <div class="input-group">
                                    <div class="input-group-text fw-125">
                                        <div class="text-truncate" aria-label="{$value.title}" title="{$value.title}" id="lbl_voice_{$value.id}">{$value.title}</div>
                                    </div>
                                    <input class="form-control" type="text" id="voice_{$value.id}" name="voice_{$value.id}" value="{$value.value}" aria-describedby="lbl_voice_{$value.id}">
                                    <button type="button" data-toggle="selectfile" data-target="voice_{$value.id}" data-path="{$UPLOADS_DIR_USER}" data-currentpath="{$UPLOAD_CURRENT}" data-type="file" class="btn btn-secondary" title="{$LANG->getGlobal('browse_file')}" aria-label="{$LANG->getGlobal('browse_file')}"><i class="fa-regular fa-folder-open"></i></button>
                                </div>
                            </div>
                            {/foreach}
                        </div>
                        {/if}
                        <div class="col-lg-6 col-xl-4 col-xxl-3">
                            <div class="mb-3">
                                <label for="layout_func" class="form-label">{$LANG->getModule('pick_layout')}:</label>
                                <select name="layout_func" id="layout_func" class="form-select">
                                    <option value="">{$LANG->getModule('default_layout')}</option>
                                    {foreach from=$ARRAY_LAYOUT item=layout}
                                    <option value="{$layout}"{if $layout eq $DATA.layout_func} selected{/if}>{$layout}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="publ_date" class="form-label">{$LANG->getModule('content_publ_date')}:</label>
                                <input type="text" name="publ_date" id="publ_date" value="{$DATA.publ_date}" class="form-control" autocomplete="off">
                            </div>
                            <div class="mb-3">
                                <label for="exp_date" class="form-label">{$LANG->getModule('content_exp_date')}:</label>
                                <input type="text" name="exp_date" id="exp_date" value="{$DATA.exp_date}" class="form-control" autocomplete="off">
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" value="1" name="archive" id="content_archive"{if not empty($DATA.archive)} checked{/if}>
                                <label class="form-check-label" for="content_archive">
                                    {$LANG->getModule('content_archive')}
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xl-4 col-xxl-3">
                            <div class="mb-3">
                                <div class="form-label">{$LANG->getModule('group_view')}:</div>
                                <div class="position-relative maxh-250 overflow-hidden" data-nv-toggle="scroll">
                                    <div class="glists">
                                        {foreach from=$GROUPS_LIST key=key item=value}
                                        <div class="form-check mb-1">
                                            <input class="form-check-input" type="checkbox" value="{$key}" name="group_view[]" id="group_view_{$key}" {if in_array($key, $DATA.group_view)} checked{/if}>
                                            <label class="form-check-label" for="group_view_{$key}">
                                                {$value}
                                            </label>
                                        </div>
                                        {/foreach}
                                    </div>
                                </div>
                                <div class="form-text">{$LANG->getModule('group_view_note')}</div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xl-4 col-xxl-3">
                            <div class="mb-3">
                                <div class="form-label">{$LANG->getModule('content_allowed_comm')}:</div>
                                <div class="position-relative maxh-250 overflow-hidden" data-nv-toggle="scroll">
                                    <div class="glists">
                                        {foreach from=$GROUPS_LIST key=key item=value}
                                        <div class="form-check mb-1">
                                            <input class="form-check-input" type="checkbox" value="{$key}" name="allowed_comm[]" id="allowed_comm_{$key}" {if in_array($key, $DATA.allowed_comm)} checked{/if}>
                                            <label class="form-check-label" for="allowed_comm_{$key}">
                                                {$value}
                                            </label>
                                        </div>
                                        {/foreach}
                                    </div>
                                </div>
                                {if $MCONFIG.allowed_comm neq -1}
                                <div class="form-text">{$LANG->getModule('content_note_comm')}</div>
                                {/if}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" value="1" name="save">
    <input type="hidden" value="{$smarty.const.NV_CHECK_SESSION}" name="checkss">
    <input type="hidden" value="{$ISCOPY}" name="copy">
    <input type="hidden" value="{$DATA.id}" name="id">
    <input type="hidden" value="{$DATA.referer}" name="referer">
    <input type="hidden" value="{$RESTORE_ID}" name="restore">
    <input type="hidden" value="{$RESTORE_HASH}" name="restorehash">
    <input type="hidden" value="{$DATA.uuid}" name="uuid">
    <div class="hstack gap-2 flex-wrap justify-content-center">
        {if $DATA.status eq 1 and $DATA.id gt 0}
        <button class="btn btn-primary submit-post" type="submit" name="statussave" value="1">{$LANG->getModule('save')}</button>
        {else}
        {if not empty($CATS_PUBLIC)}
        <button class="btn btn-primary submit-post" type="submit" name="status1" value="1">{$LANG->getModule('publtime')}</button>
        {/if}
        {if not empty($CATS_CENSOR) and $DATA.status neq 8}
        <button class="btn btn-primary submit-post" type="submit" name="status8" value="1">{$LANG->getModule('status_8')}</button>
        {/if}
        {if $DATA.status neq 5}
        <button class="btn btn-primary submit-post" type="submit" name="status5" value="1">{$LANG->getModule('status_5')}</button>
        {/if}
        <button class="btn btn-warning submit-post" type="submit" name="status4" value="1">{$LANG->getModule('save_temp')}</button>
        {if not empty($CATS_CENSOR) and in_array($DATA.status, [5, 6, 7], true)}
        <button class="btn btn-secondary submit-post submit-reject" data-type="6" type="button">{$LANG->getModule('status_6')}</button>
        {/if}
        {if not empty($CATS_PUBLIC) and in_array($DATA.status, [8, 9, 10], true)}
        <button class="btn btn-secondary submit-post submit-reject" data-type="9" type="button">{$LANG->getModule('status_9')}</button>
        {/if}
        {/if}
    </div>
    <div class="modal fade" id="modal-confirm-reject" tabindex="-1" aria-labelledby="modal-confirm-reject-label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <label for="reject_reason" class="form-label">{$LANG->getModule('reject_reason')} <span class="text-danger">(*)</span>:</label>
                    <textarea class="form-control" id="reject_reason" name="reject_reason" rows="5">{$DATA.reject_reason}</textarea>
                    <div class="text-center mt-3">
                        <button type="submit" class="btn btn-primary" name="save_reject" data-error="{$LANG->getModule('reject_reason_error')}">{$LANG->getGlobal('submit')}</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{$LANG->getGlobal('close')}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<div class="alert alert-warning mt-3 mb-0 d-none" role="alert" id="realtime-notice"></div>
