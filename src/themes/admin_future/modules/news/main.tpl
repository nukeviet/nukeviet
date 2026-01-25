<link type="text/css" href="{$smarty.const.ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet">
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/select2/select2.min.js"></script>
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/select2/i18n/{$smarty.const.NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{$smarty.const.ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{$smarty.const.ASSETS_STATIC_URL}/js/language/jquery.ui.datepicker-{$smarty.const.NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{$smarty.const.ASSETS_STATIC_URL}/js/clipboard/clipboard.min.js"></script>

{if $DRAFTS.count gt 0}
<div class="card border-0 mb-3">
    <div class="card-header text-bg-info rounded-top-2 d-flex gap-2 justify-content-between align-items-center">
        <div class="fw-medium"><i class="fa-brands fa-firstdraft"></i> {$LANG->getModule('draft_count', $DRAFTS.count|nformat)}</div>
        <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=drafts" class="btn btn-sm btn-secondary"><i class="fa-solid fa-caret-right"></i> {$LANG->getModule('content_allshow')}</a>
    </div>
    <div class="card-body">
        <div class="table-responsive-lg table-card pb-1">
            <table class="table table-striped align-middle table-sticky mb-0 list" data-del-confirm="{$LANG->getModule('draft_del_confirm')}">
                <thead class="text-muted">
                    <tr>
                        <th class="text-nowrap" style="width: 50%;">{$LANG->getModule('name')}</th>
                        <th class="text-nowrap" style="width: 30%;">{$LANG->getModule('post_time')}</th>
                        <th class="text-nowrap text-center" style="width: 20%;">{$LANG->getModule('function')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$DRAFTS.list key=key item=row}
                    <tr>
                        <td>
                            {$row.title ?: $LANG->getModule('name_empty')}
                            {if not $row.allowed_edit}
                            <div class="text-danger small">{$LANG->getModule('draft_not_allowed')}</div>
                            {/if}
                        </td>
                        <td>
                            {$row.time_late|dformat}
                        </td>
                        <td class="text-center">
                            <div class="hstack gap-1 justify-content-center">
                                {if $row.allowed_edit}
                                <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=content{if not empty($row.new_id)}&amp;id={$row.new_id}{/if}&amp;draft_id={$row.id}" class="btn btn-sm btn-secondary text-nowrap"><i class="fa-solid fa-pencil"></i> {$LANG->getModule('draft_continue')}</a>
                                {/if}
                                <button type="button" class="btn btn-sm btn-danger" data-toggle="draft_cancel" data-id="{$row.id}"><i class="fa-solid fa-circle-xmark" data-icon="fa-circle-xmark"></i> {$LANG->getGlobal('cancel')}</button>
                            </div>
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
{/if}
{if $ARRAY_OTHERS_COUNT gt 0}
<div class="card mb-3 py-1">
    <ul class="list-group list-group-flush">
        {foreach from=$ARRAY_OTHERS item=other}
        <li class="list-group-item hstack gap-2 justify-content-between">
            <div>
                {$other.title}
            </div>
            <a href="{$other.link}" class="btn btn-primary btn-sm fw-medium">{$LANG->getGlobal('view')}</a>
        </li>
        {/foreach}
    </ul>
</div>
{/if}
<div class="card">
    {if $DRAFTS.count gt 0}
    <div class="card-header text-bg-primary rounded-top-2">
        <div class="fw-medium"><i class="fa-solid fa-newspaper"></i> {$LANG->getModule('all_articles')}</div>
    </div>
    {/if}
    <div class="card-body">
        <form method="get" action="{$smarty.const.NV_BASE_ADMINURL}index.php" id="form-search-post">
            <input type="hidden" name="{$smarty.const.NV_LANG_VARIABLE}" value="{$smarty.const.NV_LANG_DATA}">
            <input type="hidden" name="{$smarty.const.NV_NAME_VARIABLE}" value="{$MODULE_NAME}">
            <div class="row g-3 flex-xl-nowrap">
                <div class="col-md-6 flex-lg-fill">
                    <label for="element_q" class="form-label">{$LANG->getModule('search_key')}</label>
                    <input type="text" class="form-control" name="q" id="element_q" value="{$SEARCH.q}" maxlength="64" placeholder="{$LANG->getModule('search_note')}">
                </div>
                <div class="col-md-6 flex-lg-fill">
                    <label for="element_stype" class="form-label">{$LANG->getModule('search_type')}</label>
                    <select class="form-select" name="stype" id="element_stype">
                        {foreach from=$TYPE_SEARCH key=key item=type}
                        <option value="{$key}"{if $key eq $SEARCH.stype} selected{/if}>{$type}</option>
                        {/foreach}
                    </select>
                </div>
                <div class="col-sm-auto flex-sm-grow-1 flex-sm-shrink-1 flex-xl-grow-0 flex-xl-shrink-0 col-xl-3">
                    <label for="element_catid" class="form-label">{$LANG->getModule('content_cat')}</label>
                    <select class="form-select select2" name="catid" id="element_catid">
                        {foreach from=$LIST_CAT item=cat}
                        <option value="{$cat.value}"{if $cat.value eq $SEARCH.catid} selected{/if}>{$cat.title}</option>
                        {/foreach}
                    </select>
                </div>
                <div class="flex-grow-0 flex-shrink-1 w-auto">
                    <label for="submit_search" class="form-label d-none d-sm-block">&nbsp;</label>
                    <div class="d-flex align-items-center">
                        <button id="submit_search" type="submit" class="btn btn-primary text-nowrap me-2"><i class="fa-solid fa-magnifying-glass"></i> {$LANG->getModule('search')}</button>
                        <div data-bs-toggle="tooltip" data-bs-title="{$LANG->getModule('search_title_adv')}" data-bs-trigger="hover" aria-label="{$LANG->getModule('search_title_adv')}">
                            <button type="button" class="btn btn-secondary text-nowrap" data-bs-toggle="collapse" data-bs-target="#search-adv" aria-expanded="{$SEARCH.adv ? 'true' : 'false'}" aria-controls="search-adv"><i class="fa-solid fa-expand"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="collapse{if $SEARCH.adv} show{/if}" id="search-adv">
                <div class="row g-3 flex-xxl-nowrap pt-3">
                    <div class="col-6 col-lg-3 flex-xxl-fill">
                        <label for="element_sstatus" class="form-label text-truncate">{$LANG->getModule('search_status')}</label>
                        <select class="form-select" name="sstatus" id="element_sstatus">
                            <option value="-1">{$LANG->getModule('search_status_all')}</option>
                            {foreach from=$SEARCH_STATUS item=st}
                            <option value="{$st.key}"{if $st.selected} selected{/if}>{$st.value}</option>
                            {/foreach}
                        </select>
                    </div>
                    <div class="col-6 col-lg-3 flex-xxl-fill">
                        <label for="element_per_page" class="form-label text-truncate">{$LANG->getModule('search_per_page')}</label>
                        <select class="form-select" name="per_page" id="element_per_page">
                            {for $per_page=5 to 500 step 5}
                            <option value="{$per_page}"{if $per_page eq $PER_PAGE} selected{/if}>{$per_page}</option>
                            {/for}
                        </select>
                    </div>
                    <div class="col-6 col-lg-3 flex-xxl-fill">
                        <label for="element_add_from" class="form-label text-truncate">{$LANG->getModule('content_publ_date')} {$LANG->getModule('from_date_short')}</label>
                        <div class="input-group flex-nowrap">
                            <input type="text" name="add_from" id="element_add_from" value="{$SEARCH.addtime_from}" class="form-control datepicker" aria-describedby="element_add_from_btn" autocomplete="off">
                            <button class="btn btn-secondary" data-toggle="focusDate" type="button" id="element_add_from_btn" aria-label="{$LANG->getModule('content_publ_date')} {$LANG->getModule('from_date_short')}"><i class="fa-regular fa-calendar"></i></button>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 flex-xxl-fill">
                        <label for="element_add_to" class="form-label text-truncate">{$LANG->getModule('content_publ_date')} {$LANG->getModule('to_date_short')}</label>
                        <div class="input-group flex-nowrap">
                            <input type="text" name="add_to" id="element_add_to" value="{$SEARCH.addtime_to}" class="form-control datepicker" aria-describedby="element_add_to_btn" autocomplete="off">
                            <button class="btn btn-secondary" data-toggle="focusDate" type="button" id="element_add_to_btn" aria-label="{$LANG->getModule('content_publ_date')} {$LANG->getModule('to_date_short')}"><i class="fa-regular fa-calendar"></i></button>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 flex-xxl-fill">
                        <label for="element_pub_from" class="form-label text-truncate">{$LANG->getModule('search_public_time')} {$LANG->getModule('from_date_short')}</label>
                        <div class="input-group flex-nowrap">
                            <input type="text" name="pub_from" id="element_pub_from" value="{$SEARCH.publtime_from}" class="form-control datepicker" aria-describedby="element_pub_from_btn" autocomplete="off">
                            <button class="btn btn-secondary" data-toggle="focusDate" type="button" id="element_pub_from_btn" aria-label="{$LANG->getModule('content_publ_date')} {$LANG->getModule('from_date_short')}"><i class="fa-regular fa-calendar"></i></button>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 flex-xxl-fill">
                        <label for="element_pub_to" class="form-label text-truncate">{$LANG->getModule('search_public_time')} {$LANG->getModule('to_date_short')}</label>
                        <div class="input-group flex-nowrap">
                            <input type="text" name="pub_to" id="element_pub_to" value="{$SEARCH.publtime_to}" class="form-control datepicker" aria-describedby="element_pub_to_btn" autocomplete="off">
                            <button class="btn btn-secondary" data-toggle="focusDate" type="button" id="element_pub_to_btn" aria-label="{$LANG->getModule('content_publ_date')} {$LANG->getModule('to_date_short')}"><i class="fa-regular fa-calendar"></i></button>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 flex-xxl-fill">
                        <label for="element_exp_from" class="form-label text-truncate">{$LANG->getModule('content_exp_date')} {$LANG->getModule('from_date_short')}</label>
                        <div class="input-group flex-nowrap">
                            <input type="text" name="exp_from" id="element_exp_from" value="{$SEARCH.exptime_from}" class="form-control datepicker" aria-describedby="element_exp_from_btn" autocomplete="off">
                            <button class="btn btn-secondary" data-toggle="focusDate" type="button" id="element_exp_from_btn" aria-label="{$LANG->getModule('content_publ_date')} {$LANG->getModule('from_date_short')}"><i class="fa-regular fa-calendar"></i></button>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 flex-xxl-fill">
                        <label for="element_exp_to" class="form-label text-truncate">{$LANG->getModule('content_exp_date')} {$LANG->getModule('to_date_short')}</label>
                        <div class="input-group flex-nowrap">
                            <input type="text" name="exp_to" id="element_exp_to" value="{$SEARCH.exptime_to}" class="form-control datepicker" aria-describedby="element_exp_to_btn" autocomplete="off">
                            <button class="btn btn-secondary" data-toggle="focusDate" type="button" id="element_exp_to_btn" aria-label="{$LANG->getModule('content_publ_date')} {$LANG->getModule('to_date_short')}"><i class="fa-regular fa-calendar"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="adv" value="{$SEARCH.adv}">
        </form>
    </div>
    <div class="card-body">
        <div class="table-responsive-lg table-card" id="list-news-items">
            <table class="table table-striped align-middle table-sticky mb-0">
                <thead class="text-muted">
                    <tr>
                        <th class="text-nowrap" style="width: 1%;">
                            <input type="checkbox" data-toggle="checkAll" name="checkAll[]" class="form-check-input m-0 align-middle" aria-label="{$LANG->getGlobal('toggle_checkall')}">
                        </th>
                        <th class="text-nowrap" style="width: 25%;">
                            <a href="{$BASE_URL_ORDER}{if $ARRAY_ORDER.field neq 'title' or $ARRAY_ORDER.value neq 'desc'}&amp;of=title{if $ARRAY_ORDER.field neq 'title' or empty($ARRAY_ORDER.value)}&amp;ov=asc{else}&amp;ov=desc{/if}{/if}" class="d-flex align-items-center justify-content-between">
                                <span class="me-1">{$LANG->getModule('name')}</span>
                                {if $ARRAY_ORDER.field neq 'title' or empty($ARRAY_ORDER.value)}<i class="fa-solid fa-sort"></i>{elseif $ARRAY_ORDER.value eq 'asc'}<i class="fa-solid fa-sort-up"></i>{else}<i class="fa-solid fa-sort-down"></i>{/if}
                            </a>
                        </th>
                        <th class="text-nowrap" style="width: 10%;">
                            <a href="{$BASE_URL_ORDER}{if $ARRAY_ORDER.field neq 'publtime' or $ARRAY_ORDER.value neq 'desc'}&amp;of=publtime{if $ARRAY_ORDER.field neq 'publtime' or empty($ARRAY_ORDER.value)}&amp;ov=asc{else}&amp;ov=desc{/if}{/if}" class="d-flex align-items-center justify-content-between">
                                <span class="me-1">{$LANG->getModule('search_public_time')}</span>
                                {if $ARRAY_ORDER.field neq 'publtime' or empty($ARRAY_ORDER.value)}<i class="fa-solid fa-sort"></i>{elseif $ARRAY_ORDER.value eq 'asc'}<i class="fa-solid fa-sort-up"></i>{else}<i class="fa-solid fa-sort-down"></i>{/if}
                            </a>
                        </th>
                        <th class="text-nowrap" style="width: 10%;">{$LANG->getModule('author')}</th>
                        <th class="text-nowrap" style="width: 10%;">{$LANG->getModule('status')}</th>
                        <th class="text-nowrap" style="width: 1%;">
                            <a href="{$BASE_URL_ORDER}{if $ARRAY_ORDER.field neq 'hitstotal' or $ARRAY_ORDER.value neq 'desc'}&amp;of=hitstotal{if $ARRAY_ORDER.field neq 'hitstotal' or empty($ARRAY_ORDER.value)}&amp;ov=asc{else}&amp;ov=desc{/if}{/if}" class="d-flex align-items-center justify-content-between">
                                <span class="me-1"><i class="fa-solid fa-eye" aria-label="{$LANG->getModule('hitstotal')}" data-bs-toggle="tooltip" data-bs-title="{$LANG->getModule('hitstotal')}" data-bs-trigger="hover"></i></span>
                                {if $ARRAY_ORDER.field neq 'hitstotal' or empty($ARRAY_ORDER.value)}<i class="fa-solid fa-sort"></i>{elseif $ARRAY_ORDER.value eq 'asc'}<i class="fa-solid fa-sort-up"></i>{else}<i class="fa-solid fa-sort-down"></i>{/if}
                            </a>
                        </th>
                        <th class="text-nowrap" style="width: 1%;">
                            <a href="{$BASE_URL_ORDER}{if $ARRAY_ORDER.field neq 'hitscm' or $ARRAY_ORDER.value neq 'desc'}&amp;of=hitscm{if $ARRAY_ORDER.field neq 'hitscm' or empty($ARRAY_ORDER.value)}&amp;ov=asc{else}&amp;ov=desc{/if}{/if}" class="d-flex align-items-center justify-content-between">
                                <span class="me-1"><i class="fa-solid fa-comments" aria-label="{$LANG->getModule('numcomments')}" data-bs-toggle="tooltip" data-bs-title="{$LANG->getModule('numcomments')}" data-bs-trigger="hover"></i></span>
                                {if $ARRAY_ORDER.field neq 'hitscm' or empty($ARRAY_ORDER.value)}<i class="fa-solid fa-sort"></i>{elseif $ARRAY_ORDER.value eq 'asc'}<i class="fa-solid fa-sort-up"></i>{else}<i class="fa-solid fa-sort-down"></i>{/if}
                            </a>
                        </th>
                        <th class="text-nowrap" style="width: 1%;">
                            <i class="fa-solid fa-tags" aria-label="{$LANG->getModule('numtags')}" data-bs-toggle="tooltip" data-bs-title="{$LANG->getModule('numtags')}" data-bs-trigger="hover"></i>
                        </th>
                        <th class="text-nowrap" style="width: 1%;">{$LANG->getModule('function')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$DATA key=key item=row}
                    <tr>
                        <td class="indicator-{$STATUS_INDICATOR[$row.status_id] ?? $STATUS_INDICATOR['----']}">
                            <input type="checkbox" data-toggle="checkSingle" name="checkSingle[]" value="{$row.id}" class="form-check-input m-0 align-middle" aria-label="{$LANG->getGlobal('toggle_checksingle')}"{if $row.is_locked} disabled{/if}>
                        </td>
                        <td>
                            <div class="text-truncate-2">
                                {if $row.tool_sort}
                                <a href="#" class="d-inline-block me-1 link-info" data-toggle="sortArticle" data-id="{$row.id}" data-checksess="{$row.checksess}" data-title="{$LANG->getModule('order_articles')} &quot;{$row.title}&quot;" data-weight="{$row.weight}" aria-label="{$LANG->getModule('order_articles')}"><i class="fa-solid fa-sort" data-bs-toggle="tooltip" data-bs-title="{$LANG->getModule('order_articles_number')}: {$row.weight|nformat}" data-bs-trigger="hover"></i></a>
                                {/if}
                                {if $row.is_editing}
                                <i class="fa-solid {if $row.is_locked}fa-lock{else}fa-unlock{/if} me-1 text-warning" aria-label="{$row.user_editing} {$LANG->getModule('post_is_editing')}." data-bs-toggle="tooltip" data-bs-title="{$row.user_editing} {$LANG->getModule('post_is_editing')}." data-bs-trigger="hover"></i>
                                {/if}
                                {if $row.status_id eq 4}
                                <i class="fa-solid fa-compass-drafting" aria-label="{$LANG->getModule('status_4')}" title="{$LANG->getModule('status_4')}" data-bs-toggle="tooltip" data-bs-trigger="hover"></i>
                                {/if}
                                {if in_array($row.status_id, [6, 9]) and not empty($row.reject_reason)}
                                <a href="#md-reject-reason-{$row.id}" data-bs-toggle="modal" title="{$LANG->getModule('reject_reason_view')}" aria-label="{$LANG->getModule('reject_reason_view')}"><i data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-title="{$LANG->getModule('reject_reason_view')}" class="fa-solid fa-circle-exclamation"></i></a>
                                <!-- START FORFOOTER -->
                                <div class="modal fade" tabindex="-1" id="md-reject-reason-{$row.id}" aria-hidden="true" aria-labelledby="md-reject-reason-{$row.id}-label">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <div class="modal-title fw-medium fs-5" id="md-reject-reason-{$row.id}-label">{$LANG->getModule('reject_reason')}</div>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
                                            </div>
                                            <div class="modal-body">
                                                {$row.reject_reason}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END FORFOOTER -->
                                {/if}
                                <a target="_blank" href="{$row.link}" title="{$row.title}">{$row.title}</a>
                            </div>
                        </td>
                        <td>{$row.publtime}</td>
                        <td>{$row.author}</td>
                        <td>{$row.status}</td>
                        <td class="text-center text-nowrap fw-medium">{$row.hitstotal}</td>
                        <td class="text-center text-nowrap fw-medium">{$row.hitscm}</td>
                        <td class="text-center text-nowrap fw-medium">{$row.numtags}</td>
                        <td>
                            <div class="input-group flex-nowrap">
                                {if isset($row.feature.edit)}
                                <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=content&amp;id={$row.id}" class="btn btn-sm btn-secondary text-nowrap"><i class="fa-solid fa-pen"></i> {$LANG->getGlobal('edit')}</a>
                                {/if}
                                <button type="button" class="btn btn-sm btn-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="visually-hidden">{$LANG->getModule('function')}</span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" data-toggle="copyArticleUrl" data-message="{$LANG->getModule('link_copied')}" data-clipboard-text="{$row.abs_link}"><i class="fa-solid fa-clipboard fa-fw text-center"></i> {$LANG->getModule('copy_link')}</a></li>
                                    {if not empty($MCONFIG.copy_news)}
                                    <li><a class="dropdown-item" href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=content&amp;copy=1&amp;id={$row.id}"><i class="fa-solid fa-copy fa-fw text-center"></i> {$LANG->getModule('title_copy_news')}</a></li>
                                    {/if}
                                    {if $row.tool_excdata}
                                    <li><a class="dropdown-item" href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}=excdata&amp;{$smarty.const.NV_OP_VARIABLE}=send&amp;module={$MODULE_NAME}&amp;id={$row.id}"><i class="fa-solid fa-paper-plane fa-fw text-center"></i> {$LANG->getModule('send')}</a></li>
                                    {/if}
                                    {if $row.show_history}
                                    <li><a class="dropdown-item" href="#" data-loadurl="{$BASE_URL}&amp;loadhistory={$row.id}" data-toggle="historyArticle"><i class="fa-solid fa-clock-rotate-left fa-fw text-center"></i> {$LANG->getModule('history')}</a></li>
                                    {/if}
                                    {if isset($row.feature.delete)}
                                    <li><a class="dropdown-item" href="#" data-toggle="delArticle" data-id="{$row.id}" data-checksess="{$row.checksess}"><i class="fa-solid fa-trash fa-fw text-center text-danger" data-icon="fa-trash"></i> {$LANG->getGlobal('delete')}</a></li>
                                    {/if}
                                </ul>
                            </div>
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer border-top">
        <div class="d-flex flex-wrap justify-content-between align-items-center">
            <div class="d-flex flex-wrap flex-sm-nowrap align-items-center">
                <div class="me-2">
                    <input type="checkbox" data-toggle="checkAll" name="checkAll[]" class="form-check-input m-0 align-middle" aria-label="{$LANG->getGlobal('toggle_checkall')}">
                </div>
                <div class="input-group me-1 my-1">
                    <select id="element_action" class="form-select fw-150" aria-label="{$LANG->getGlobal('select_actions')}" aria-describedby="element_action_btn">
                        {foreach from=$ACTIONS item=action}
                        <option value="{$action.value}">{$action.title}</option>
                        {/foreach}
                    </select>
                    <button class="btn btn-primary" type="button" id="element_action_btn" data-toggle="actionArticle" data-ctn="#list-news-items">{$LANG->getModule('action')}</button>
                </div>
            </div>
            <div class="pagination-wrap">
                {$PAGINATION}
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mdSortArticle" tabindex="-1" aria-labelledby="mdSortArticleLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-truncate" id="mdSortArticleLabel">{$LANG->getModule('order_articles')}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <label for="sortArticleCurrent" class="form-label">{$LANG->getModule('order_articles_number')}</label>
                        <input type="number" class="form-control" id="sortArticleCurrent" readonly>
                    </div>
                    <div class="col">
                        <label for="sortArticleNew" class="form-label">{$LANG->getModule('order_articles_new')}</label>
                        <input type="number" class="form-control" id="sortArticleNew" min="1">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="sortArticleSave" type="button" class="btn btn-primary"><i class="fa-solid fa-check" data-icon="fa-check"></i> {$LANG->getGlobal('save')}</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i> {$LANG->getGlobal('cancel')}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mdHistoryArticle" tabindex="-1" aria-labelledby="mdHistoryArticleLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-truncate" id="mdHistoryArticleLabel">{$LANG->getModule('history')}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>
