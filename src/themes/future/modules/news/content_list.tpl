{* Điều hướng dạng tab trên màn hình lớn, dạng dropdown trên di động *}
<ul class="nav nav-tabs mb-3 d-none d-md-flex">
    <li class="nav-item"><a class="nav-link active" aria-current="page" href="{$BASE_URL}">{$LANG->getModule('your_content')}</a></li>
    <li class="nav-item"><a class="nav-link" href="{$BASE_URL}&amp;contentid=0">{$LANG->getModule('add_content')}</a></li>
    <li class="nav-item"><a class="nav-link" href="{$BASE_URL}&amp;author_info=1">{$LANG->getModule('author_info')}</a></li>
</ul>
<div class="dropdown mb-3 d-md-none">
    <button type="button" class="btn btn-outline-secondary dropdown-toggle w-100 d-flex align-items-center justify-content-between" data-bs-toggle="dropdown" aria-expanded="false">{$LANG->getModule('your_content')}</button>
    <ul class="dropdown-menu w-100">
        <li><a class="dropdown-item active" aria-current="page" href="{$BASE_URL}">{$LANG->getModule('your_content')}</a></li>
        <li><a class="dropdown-item" href="{$BASE_URL}&amp;contentid=0">{$LANG->getModule('add_content')}</a></li>
        <li><a class="dropdown-item" href="{$BASE_URL}&amp;author_info=1">{$LANG->getModule('author_info')}</a></li>
    </ul>
</div>
<div class="d-flex flex-wrap align-items-center gap-2 mb-3">
    <h1 class="fs-4 mb-0 flex-grow-1">{$LANG->getModule('your_content')}</h1>
    <a href="{$AUTHOR_PAGE_URL}"><i class="fa-solid fa-arrow-right"></i> {$LANG->getModule('author_page')}</a>
</div>
<div class="border-top">
    {foreach from=$ARTICLES item=row}
    {* Bài chưa xuất bản thì chỉ quản trị module mới xem được *}
    {assign var="canView" value=($row.status eq 1 or $smarty.const.NV_IS_MODADMIN)}
    <article class="row gx-3 py-3 mx-0 border-bottom">
        {if not empty($row.imghome)}
        <div class="col-4 col-md-3 ps-0">
            {if $canView}
            <a href="{$row.link}" class="ratio d-block rounded overflow-hidden" style="--bs-aspect-ratio: {$IMGRATIO}%;">
                <img src="{$row.imghome}" alt="{$row.homeimgalt ?: $row.title}" class="object-fit-cover">
            </a>
            {else}
            <div class="ratio rounded overflow-hidden" style="--bs-aspect-ratio: {$IMGRATIO}%;">
                <img src="{$row.imghome}" alt="{$row.homeimgalt ?: $row.title}" class="object-fit-cover">
            </div>
            {/if}
        </div>
        {/if}
        <div class="{if not empty($row.imghome)}col-8 col-md-9 pe-0{else}col-12 px-0{/if} d-flex flex-column">
            <h2 class="fs-5 fw-medium mb-2">
                {if $canView}
                <a class="link-body-emphasis" href="{$row.link}">{$row.title}</a>
                {else}
                {$row.title}
                {/if}
            </h2>
            {if not empty($row.status_note)}
            <div class="mb-2"><span class="badge text-bg-warning text-wrap text-start">{$row.status_note}</span></div>
            {/if}
            <div class="text-truncate-3 mb-2">{$row.hometext}</div>
            <div class="mt-auto d-flex flex-wrap align-items-center gap-3 small">
                <ul class="list-inline text-muted mb-0 me-auto">
                    <li class="list-inline-item"><i class="fa-regular fa-clock"></i> {$row.publtime}</li>
                    <li class="list-inline-item"><i class="fa-regular fa-eye"></i> {$LANG->getModule('view')}: {$row.hitstotal|dnumber}</li>
                </ul>
                {if $row.is_edit_content}
                <a class="link-primary text-decoration-none" href="{$BASE_URL}&amp;contentid={$row.id}"><i class="fa-solid fa-pencil"></i> {$LANG->getGlobal('edit')}</a>
                {/if}
                {if $row.is_del_content}
                <a class="link-danger text-decoration-none" href="#" role="button" data-toggle="newsContentDel" data-url="{$BASE_URL}&amp;contentid={$row.id}&amp;delcontent=1" data-checkss="{$CHECKSS}"><i class="fa-solid fa-trash" data-icon="fa-trash"></i> {$LANG->getGlobal('delete')}</a>
                {/if}
            </div>
        </div>
    </article>
    {/foreach}
</div>
{if not empty($GENERATE_PAGE)}
<div class="text-center mt-4">
    {$GENERATE_PAGE}
</div>
{/if}
