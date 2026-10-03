{* Nhãn "Mới" dùng lại cho danh sách bài viết và các tin khác *}
{capture name="badgeNew"}
<span class="badge text-bg-danger badge-new">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
        <path d="M7.657 6.247c.11-.33.576-.33.686 0l.645 1.937a2.89 2.89 0 0 0 1.829 1.828l1.936.645c.33.11.33.576 0 .686l-1.937.645a2.89 2.89 0 0 0-1.828 1.829l-.645 1.936a.361.361 0 0 1-.686 0l-.645-1.937a2.89 2.89 0 0 0-1.828-1.828l-1.937-.645a.361.361 0 0 1 0-.686l1.937-.645a2.89 2.89 0 0 0 1.828-1.828zM3.794 1.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387A1.73 1.73 0 0 0 4.593 5.69l-.387 1.162a.217.217 0 0 1-.412 0L3.407 5.69A1.73 1.73 0 0 0 2.31 4.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387A1.73 1.73 0 0 0 3.407 2.31zM10.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.16 1.16 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.16 1.16 0 0 0-.732-.732L9.1 2.137a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732z"/>
    </svg> {$LANG->getModule('newpost')}
</span>
{/capture}
{* Có tiêu đề phụ (trang tác giả) thì tiêu đề mỗi bài hạ xuống h3 *}
{if empty($HEADER.list_title)}
{assign var="htag" value="h2"}
{else}
{assign var="htag" value="h3"}
{/if}
{* Phần đầu trang *}
{if not empty($HEADER.description) or not empty($HEADER.image)}
<div class="card mb-4">
    <div class="card-body clearfix">
        <h1>{$HEADER.title}</h1>
        {if not empty($HEADER.image)}
        <img src="{$HEADER.image}" class="img-fluid rounded fw-150 me-2 mb-1 float-start" alt="{$HEADER.title}">
        {/if}
        {if not empty($HEADER.description)}
        <div>{$HEADER.description}</div>
        {/if}
    </div>
</div>
{elseif not $HOME}
<h1 class="visually-hidden">{$PAGE_TITLE}</h1>
{/if}
{if not empty($HEADER.list_title)}
<h2 class="fs-4 mb-3">{$HEADER.list_title}</h2>
{/if}
{* Danh sách bài viết *}
{if not empty($ARTICLES)}
<div class="border-top">
    {foreach from=$ARTICLES item=row}
    {* Bài nổi bật giữ nguyên thẻ heading nhưng ảnh lớn hơn, tiêu đề to hơn; trên mobile ảnh full width, nội dung nằm dưới *}
    {assign var="isFeatured" value=(not empty($row.featured))}
    <article class="row gx-3 py-3 mx-0 border-bottom">
        {if not empty($row.imghome)}
        <div class="{if $isFeatured}col-12 col-sm-5 col-md-4 px-0 pe-sm-2 mb-3 mb-sm-0{else}col-4 col-md-3 ps-0{/if}">
            <a href="{$row.link}"{if not empty($row.external_link)} target="_blank"{/if} class="ratio d-block rounded overflow-hidden" style="--bs-aspect-ratio: {$IMGRATIO}%;">
                <img src="{$row.imghome}" alt="{$row.alt}" class="object-fit-cover">
            </a>
        </div>
        {/if}
        <div class="{if empty($row.imghome)}col-12 px-0{elseif $isFeatured}col-12 col-sm-7 col-md-8 px-0 ps-sm-2{else}col-8 col-md-9 pe-0{/if} d-flex flex-column">
            <div class="mb-2">
                <{$htag} class="{if $isFeatured}fs-4{else}fs-5{/if} fw-medium d-inline mb-0">
                    <a class="link-body-emphasis" href="{$row.link}"{if not empty($row.external_link)} target="_blank"{/if}>{$row.title}</a>
                </{$htag}>
                {if not empty($row.newday) and (($row.newday * 86400) + $row.publtime) gte $smarty.now}
                {$smarty.capture.badgeNew}
                {/if}
            </div>
            <div class="text-truncate-3 mb-2{if not $isFeatured} d-none d-sm-block{/if}">{$row.hometext}</div>
            <div class="mt-auto d-flex flex-wrap align-items-center gap-3 small">
                <ul class="list-inline text-muted mb-0 me-auto">
                    <li class="list-inline-item"><i class="fa-regular fa-clock"></i> {$row.publtime|ddatetime}</li>
                    {if isset($row.hitstotal)}
                    <li class="list-inline-item"><i class="fa-regular fa-eye"></i> <span class="d-none d-sm-inline">{$LANG->getModule('view')}:</span> {$row.hitstotal|dnumber}</li>
                    {/if}
                    {if $COMMENT_ENABLED and isset($row.hitscm)}
                    <li class="list-inline-item"><i class="fa-regular fa-comment"></i> <span class="d-none d-sm-inline">{$LANG->getModule('total_comment')}:</span> {$row.hitscm|dnumber}</li>
                    {/if}
                </ul>
                {if $smarty.const.NV_IS_MODADMIN}
                {assign var="linkEdit" value=$row|editAllowed:true}
                {assign var="linkDelete" value=$row|deleteAllowed:0:true}
                {if not empty($linkEdit)}
                <a class="link-primary text-decoration-none" href="{$linkEdit}"><i class="fa-solid fa-pencil"></i> {$LANG->getGlobal('edit')}</a>
                {/if}
                {if not empty($linkDelete)}
                <a class="link-danger text-decoration-none" href="#" role="button" data-toggle="nv_del_content" data-id="{$linkDelete.id}" data-checkss="{$linkDelete.checkss}" data-adminurl="{$smarty.const.NV_BASE_ADMINURL}"><i class="fa-solid fa-trash" data-icon="fa-trash"></i> {$LANG->getGlobal('delete')}</a>
                {/if}
                {/if}
            </div>
        </div>
    </article>
    {/foreach}
</div>
{/if}
{* Các tin khác *}
{if not empty($OTHERS)}
<div class="mt-4">
    <div class="fw-medium fs-5 border-bottom pb-2 mb-3">{$LANG->getModule('other')}</div>
    <ul class="list-unstyled vstack gap-2 mb-0">
        {foreach from=$OTHERS item=row}
        <li>
            <i class="fa-solid fa-angle-right text-muted"></i>
            <a class="link-body-emphasis" href="{$row.link}"{if not empty($row.external_link)} target="_blank"{/if}>{$row.title}</a>
            <span class="small text-muted">({1|ddate:$row.publtime})</span>
            {if not empty($row.newday) and (($row.newday * 86400) + $row.publtime) gte $smarty.now}
            {$smarty.capture.badgeNew}
            {/if}
        </li>
        {/foreach}
    </ul>
</div>
{/if}
{if not empty($GENERATE_PAGE)}
<div class="d-flex justify-content-center mt-4">
    {$GENERATE_PAGE}
</div>
{/if}
