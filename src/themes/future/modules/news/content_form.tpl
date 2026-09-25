{if $smarty.const.NV_IS_USER}
{* Điều hướng dạng tab trên màn hình lớn, dạng dropdown trên di động *}
<ul class="nav nav-tabs mb-3 d-none d-md-flex">
    <li class="nav-item"><a class="nav-link" href="{$BASE_URL}">{$LANG->getModule('your_content')}</a></li>
    <li class="nav-item"><a class="nav-link{if empty($DATA.id)} active{/if}"{if empty($DATA.id)} aria-current="page"{/if} href="{$BASE_URL}&amp;contentid=0">{$LANG->getModule('add_content')}</a></li>
    <li class="nav-item"><a class="nav-link" href="{$BASE_URL}&amp;author_info=1">{$LANG->getModule('author_info')}</a></li>
</ul>
<div class="dropdown mb-3 d-md-none">
    <button type="button" class="btn btn-outline-secondary dropdown-toggle w-100 d-flex align-items-center justify-content-between" data-bs-toggle="dropdown" aria-expanded="false">{if empty($DATA.id)}{$LANG->getModule('add_content')}{else}{$LANG->getModule('update_content')}{/if}</button>
    <ul class="dropdown-menu w-100">
        <li><a class="dropdown-item" href="{$BASE_URL}">{$LANG->getModule('your_content')}</a></li>
        <li><a class="dropdown-item{if empty($DATA.id)} active{/if}"{if empty($DATA.id)} aria-current="page"{/if} href="{$BASE_URL}&amp;contentid=0">{$LANG->getModule('add_content')}</a></li>
        <li><a class="dropdown-item" href="{$BASE_URL}&amp;author_info=1">{$LANG->getModule('author_info')}</a></li>
    </ul>
</div>
{/if}
<div class="card">
    <div class="card-body">
        <h1 class="h5 border-bottom pb-3 mb-3">{if empty($DATA.id)}{$LANG->getModule('add_content')}{else}{$LANG->getModule('update_content')}{/if}</h1>
        <form action="{$BASE_URL}&amp;contentid={$DATA.id}" method="post" data-toggle="ajax-form" data-precheck="nv_precheck_form" data-form="newsContent" autocomplete="off" novalidate{$CAPTCHA_ATTRS}>
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label" for="newsContentTitle">{$LANG->getModule('name')} <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="newsContentTitle" name="title" value="{$DATA.title}" placeholder="{$LANG->getModule('name')}" data-valid data-error-type="feedback">
                    <div class="invalid-feedback">{$LANG->getModule('error_title')}</div>
                </div>
                {if $MCONFIG.frontend_edit_alias eq 1 and empty($DATA.id)}
                <div class="col-12">
                    <label class="form-label" for="newsContentAlias">{$LANG->getModule('alias')}</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="newsContentAlias" name="alias" value="{$DATA.alias}" maxlength="255" placeholder="{$LANG->getModule('alias')}">
                        <button type="button" class="btn btn-secondary" data-toggle="newsContentAlias" data-url="{$BASE_URL}" aria-label="{$LANG->getModule('alias')}" data-bs-toggle="tooltip" title="{$LANG->getModule('alias')}"><i class="fa-solid fa-rotate" data-icon="fa-rotate"></i></button>
                    </div>
                </div>
                {/if}
                <div class="col-md-6">
                    <div class="form-label">{$LANG->getModule('content_cat')} <span class="text-danger">*</span></div>
                    <div class="border rounded px-3 py-2 maxh-200 overflow-auto">
                        {foreach from=$CATS item=cat}
                        <div class="d-flex">
                            {if $cat.lev gt 0}<span class="flex-shrink-0 fw-{$cat.lev * 20}"></span>{/if}
                            <div class="form-check mb-1">
                                <input class="form-check-input" type="checkbox" name="catids[]" value="{$cat.catid}" id="newsContentCat{$cat.catid}"{if $cat.checked} checked{/if} data-valid data-min="1" data-max="{count($CATS)}" data-error-type="feedback">
                                <label class="form-check-label" for="newsContentCat{$cat.catid}">{$cat.title}</label>
                                {if $cat@last}
                                <div class="invalid-feedback">{$LANG->getModule('error_cat')}</div>
                                {/if}
                            </div>
                        </div>
                        {/foreach}
                    </div>
                </div>
                <div class="col-md-6 vstack gap-3">
                    <div>
                        <label class="form-label" for="newsContentTopic">{$LANG->getModule('content_topic')}</label>
                        <select class="form-select" id="newsContentTopic" name="topicid">
                            {foreach from=$TOPICS key=topicid item=title}
                            <option value="{$topicid}"{if $topicid eq $DATA.topicid} selected{/if}>{$title}</option>
                            {/foreach}
                        </select>
                    </div>
                    {if $MCONFIG.frontend_edit_layout eq 1}
                    <div>
                        <label class="form-label" for="newsContentLayout">{$LANG->getModule('pick_layout')}</label>
                        <select class="form-select" id="newsContentLayout" name="layout_func">
                            <option value="">{$LANG->getModule('default_layout')}</option>
                            {foreach from=$LAYOUTS item=layout}
                            <option value="{$layout}"{if $layout eq $DATA.layout_func} selected{/if}>{$layout}</option>
                            {/foreach}
                        </select>
                    </div>
                    {/if}
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="newsContentHomeimg">{$LANG->getModule('content_homeimg')}</label>
                    <input type="text" class="form-control" id="newsContentHomeimg" name="homeimgfile" value="{$DATA.homeimgfile}" placeholder="{$LANG->getModule('content_homeimg')}">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="newsContentHomeimgalt">{$LANG->getModule('content_homeimgalt')}</label>
                    <input type="text" class="form-control" id="newsContentHomeimgalt" name="homeimgalt" value="{$DATA.homeimgalt}" maxlength="255" placeholder="{$LANG->getModule('content_homeimgalt')}">
                </div>
                <div class="col-12">
                    <label class="form-label" for="newsContentHometext">{$LANG->getModule('content_hometext')}</label>
                    <textarea class="form-control" id="newsContentHometext" name="hometext" rows="4" placeholder="{$LANG->getModule('content_hometext')}">{$DATA.hometext}</textarea>
                </div>
                <div class="col-12">
                    <div class="form-label">{$LANG->getModule('content_bodytext')} <span class="text-danger">*</span></div>
                    <div data-valid="editor" data-name="bodyhtml" data-error-type="feedback" data-error-mess="{$LANG->getModule('error_bodytext')}">{$HTMLBODYTEXT}</div>
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="newsContentSource">{$LANG->getModule('source')}</label>
                    <input type="text" class="form-control" id="newsContentSource" name="sourcetext" value="{$DATA.sourcetext}" maxlength="255" placeholder="{$LANG->getModule('source')}">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="newsContentAuthor">{if $smarty.const.NV_IS_USER}{$LANG->getModule('external_author')}{else}{$LANG->getModule('author')}{/if}</label>
                    <input type="text" class="form-control" id="newsContentAuthor" name="author" value="{$DATA.author}" maxlength="255">
                </div>
                {if not empty($DATA.internal_authors)}
                <div class="col-12">
                    <div class="form-label">{$LANG->getModule('internal_author')}</div>
                    <div class="d-flex flex-wrap gap-2">
                        {foreach from=$DATA.internal_authors item=author}
                        <a class="btn btn-sm btn-outline-secondary" href="{$author.href}" target="_blank">{$author.pseudonym}</a>
                        {/foreach}
                    </div>
                </div>
                {/if}
                {if not empty($GCONFIG.data_warning) or not empty($GCONFIG.antispam_warning)}
                <div class="col-12">
                    <div class="alert alert-info vstack gap-2 mb-0">
                        {if not empty($GCONFIG.data_warning)}
                        <div class="form-check mb-0">
                            <input class="form-check-input" type="checkbox" name="data_permission_confirm" value="1" id="data_permission_confirm" data-valid="checkbox" data-min="1" data-max="1" data-error-type="feedback">
                            <label class="form-check-label" for="data_permission_confirm"><small>{$GCONFIG.data_warning_content|default:$LANG->getGlobal('data_warning_content')}</small></label>
                            <div class="invalid-feedback">{$LANG->getGlobal('data_warning_error')}</div>
                        </div>
                        {/if}
                        {if not empty($GCONFIG.antispam_warning)}
                        <div class="form-check mb-0">
                            <input class="form-check-input" type="checkbox" name="antispam_confirm" value="1" id="antispam_confirm" data-valid="checkbox" data-min="1" data-max="1" data-error-type="feedback">
                            <label class="form-check-label" for="antispam_confirm"><small>{$GCONFIG.antispam_warning_content|default:$LANG->getGlobal('antispam_warning_content')}</small></label>
                            <div class="invalid-feedback">{$LANG->getGlobal('antispam_warning_error')}</div>
                        </div>
                        {/if}
                    </div>
                </div>
                {/if}
            </div>
            <input type="hidden" name="save" value="1">
            <input type="hidden" name="checkss" value="{$CHECKSS}">
            <div class="d-flex flex-wrap gap-2 mt-4">
                <select class="form-select w-auto" name="status" aria-label="{$LANG->getModule('status')}">
                    {foreach from=$POST_STATUS item=status}
                    <option value="{$status}"{if $status eq $DATA.status} selected{/if}>{$LANG->getModule("action_`$status`")}</option>
                    {/foreach}
                </select>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-paper-plane"></i> {$LANG->getGlobal('submit')}</button>
            </div>
        </form>
    </div>
</div>
