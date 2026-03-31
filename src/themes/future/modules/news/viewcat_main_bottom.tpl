{* Hiển thị tra mô tả của chuyên mục *}
{if $SHOW_DESCRIPTION and empty($HTML_POSTS)}
<div class="card mb-4">
    <div class="card-body">
        <h1>{$INFO_CAT.title}</h1>
        {if not empty($INFO_CAT.image)}
        <img src="{$smarty.const.NV_BASE_SITEURL}{$smarty.const.NV_FILES_DIR}/{$MODULE_UPLOAD}/{$INFO_CAT.image}" class="img-thumbnail fw-150 me-2 mb-1 float-start" alt="{$INFO_CAT.title}">
        {/if}
        <p class="mb-0">{$INFO_CAT.description}</p>
    </div>
</div>
{/if}
{* Phần viết các bài viết đầu chuyên mục *}
{if not empty($HTML_POSTS)}
<div class="cat-top-articles">
    {$HTML_POSTS}
</div>
{/if}
<div class="row g-3">
    {foreach from=$ARRAY_CATS item=datacat}
    {if isset($datacat.content)}
    <div class="col-sm-12 col-md-6 col-lg-12">
        <div class="catbox">
            {* Phần tiêu đề mỗi chuyên mục *}
            <div class="catbox-header mb-3 d-flex border-bottom border-4 border-primary align-items-center">
                <h2 class="mb-0 fs-4">
                    <a href="{$datacat.link}" class="text-bg-primary px-2 py-1 fw-medium d-block">{$datacat.title}</a>
                </h2>
                {if $datacat.numsubcat gt 0}
                <ul class="list-inline mb-0 ms-auto">
                    {assign var="stt" value=0}
                    {assign var="morecat" value=[]}
                    {foreach from=$datacat.subcats item=subcat}
                    {assign var="stt" value=($stt+1)}
                    {if $stt lte 3}
                    <li class="list-inline-item d-none d-lg-inline-block">
                        <a href="{$subcat.link}" class="link-body-emphasis">{$subcat.title}</a>
                    </li>
                    {else}
                    {append var="morecat" value=$subcat}
                    {/if}
                    {/foreach}
                    {if not empty($morecat)}
                    {* Chuyên mục con thứ 4 thì show dạng submenu *}
                    <li class="list-inline-item d-none d-lg-inline-block me-0">
                        <div class="dropdown">
                            <a class="d-block p-1 link-body-emphasis" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-caret-down"></i>
                                <span class="visually-hidden">{$LANG->getModule('more')}</span>
                            </a>
                            <ul class="dropdown-menu">
                                {foreach from=$morecat item=subcatmore}
                                <li><a class="dropdown-item" href="{$subcatmore.link}">{$subcatmore.title}</a></li>
                                {/foreach}
                            </ul>
                        </div>
                    </li>
                    {/if}
                    {* Các chuyên mục khác trên mobile *}
                    <li class="list-inline-item d-lg-none">
                        <div class="dropdown">
                            <a class="d-block p-1 link-body-emphasis" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-caret-down"></i>
                                <span class="visually-hidden">{$LANG->getModule('more')}</span>
                            </a>
                            <ul class="dropdown-menu">
                                {foreach from=$datacat.subcats item=subcat}
                                <li><a class="dropdown-item" href="{$subcat.link}">{$subcat.title}</a></li>
                                {/foreach}
                            </ul>
                        </div>
                    </li>
                </ul>
                {/if}
            </div>
            {* Quảng cáo phụ bên trên *}
            {if not empty($datacat.block_top)}
            <div class="mb-2 top-ads">
                {$datacat.block_top}
            </div>
            {/if}
            {* Phần nội dung chuyên mục *}
            <div class="catbox-contents vstack gap-4">
                {assign var="numitems" value=count($datacat.content)}
                {* Tin mới đầu tiên bên trên *}
                {assign var="row" value=$datacat.content[0]}
                <div class="row g-3">
                    {if not empty($row.imghome)}
                    <div class="col-12 col-sm-4 col-lg-6">
                        <a href="{$row.link}"{if $row.external_link} target="_blank"{/if} class="thumbnail-lg" style="--nv-aspect-ratio: {$IMGRATIO}%;">
                            <span><img src="{$row.imghome}" alt="{$row.homeimgalt ?: $row.title}"></span>
                        </a>
                    </div>
                    {/if}
                    <div class="col-12{if not empty($row.imghome)} col-sm-8 col-lg-6{/if}">
                        <div class="fs-5 mb-1">
                            <h3 class="mb-0 fs-5 d-inline">
                                <a class="link-body-emphasis" href="{$row.link}"{if $row.external_link} target="_blank"{/if}>{$row.title}</a>
                            </h3>
                            {if $smarty.const.NV_IS_MODADMIN}
                            {assign var="linkEdit" value=$row|editAllowed:true}
                            {assign var="linkDelete" value=$row|deleteAllowed:0:true}
                            {if not empty($linkEdit) or not empty($linkDelete)}
                            <span class="dropdown">
                                <a class="link-secondary" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-screwdriver-wrench"></i> <span class="visually-hidden">{$LANG->getModule('admtools')}</span>
                                </a>
                                <ul class="dropdown-menu">
                                    {if not empty($linkEdit)}
                                    <li><a class="dropdown-item" href="{$linkEdit}"><i class="fa-solid fa-pencil fa-fw text-center"></i> {$LANG->getGlobal('edit')}</a></li>
                                    {/if}
                                    {if not empty($linkDelete)}
                                    <li><a class="dropdown-item" href="#" data-toggle="nv_del_content" data-id="{$linkDelete.id}" data-checkss="{$linkDelete.checkss}" data-adminurl="{$smarty.const.NV_BASE_ADMINURL}"><i class="fa-solid fa-trash fa-fw text-center text-danger" data-icon="fa-trash"></i> {$LANG->getGlobal('delete')}</a></li>
                                    {/if}
                                </ul>
                            </span>
                            {/if}
                            {/if}
                            {assign var="newday" value=(($row.newday * 86400) + $row.publtime)}
                            {if $newday gte $smarty.now}
                            <span class="badge text-bg-danger badge-new">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M7.657 6.247c.11-.33.576-.33.686 0l.645 1.937a2.89 2.89 0 0 0 1.829 1.828l1.936.645c.33.11.33.576 0 .686l-1.937.645a2.89 2.89 0 0 0-1.828 1.829l-.645 1.936a.361.361 0 0 1-.686 0l-.645-1.937a2.89 2.89 0 0 0-1.828-1.828l-1.937-.645a.361.361 0 0 1 0-.686l1.937-.645a2.89 2.89 0 0 0 1.828-1.828zM3.794 1.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387A1.73 1.73 0 0 0 4.593 5.69l-.387 1.162a.217.217 0 0 1-.412 0L3.407 5.69A1.73 1.73 0 0 0 2.31 4.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387A1.73 1.73 0 0 0 3.407 2.31zM10.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.16 1.16 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.16 1.16 0 0 0-.732-.732L9.1 2.137a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732z"/>
                                </svg> {$LANG->getModule('newpost')}
                            </span>
                            {/if}
                        </div>
                        <ul class="list-inline small mb-1 text-muted">
                            <li class="list-inline-item"><i class="fa-regular fa-clock"></i> {1|ddate:$row.publtime}</li>
                        </ul>
                        <div class="text-truncate-3">
                            {$row.hometext}
                        </div>
                    </div>
                </div>
                {if $numitems gt 1}
                {* Các tin tiếp theo bên dưới *}
                <div class="other-items vstack gap-3">
                    {assign var="stt" value=0}
                    {foreach from=$datacat.content item=row}
                    {if $stt gt 0}
                    <article class="row">
                        {if not empty($row.imghome)}
                        <div class="col-4 col-sm-3">
                            <a href="{$row.link}"{if $row.external_link} target="_blank"{/if} class="thumbnail-lg" style="--nv-aspect-ratio: {$IMGRATIO}%;">
                                <span><img src="{$row.imghome}" alt="{$row.homeimgalt ?: $row.title}"></span>
                            </a>
                        </div>
                        {/if}
                        <div class="col-8{if not empty($row.imghome)} col-sm-9{/if}">
                            <ul class="list-inline small text-muted lh-1 mb-1">
                                <li class="list-inline-item"><i class="fa-regular fa-clock"></i> {1|ddate:$row.publtime}</li>
                            </ul>
                            <div class="fw-medium lh-sm">
                                <h3 class="mb-0 d-inline fs-6">
                                    <a class="link-body-emphasis" href="{$row.link}"{if $row.external_link} target="_blank"{/if}
                                        {if not empty($MCONFIG.showtooltip)}
                                        data-toggle="tooltipArticle" data-hometext="{$row.hometext_clean}" data-alt="{$row.homeimgalt ?: $row.title}" data-img="{$row.imghome}"
                                        data-bs-toggle="tooltip" data-bs-placement="{$MCONFIG.tooltip_position}"
                                        {/if}
                                    >{$row.title}</a>
                                </h3>
                                {if $smarty.const.NV_IS_MODADMIN}
                                {assign var="linkEdit" value=$row|editAllowed:true}
                                {assign var="linkDelete" value=$row|deleteAllowed:0:true}
                                {if not empty($linkEdit) or not empty($linkDelete)}
                                <span class="dropdown">
                                    <a class="link-secondary" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-screwdriver-wrench"></i> <span class="visually-hidden">{$LANG->getModule('admtools')}</span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        {if not empty($linkEdit)}
                                        <li><a class="dropdown-item" href="{$linkEdit}"><i class="fa-solid fa-pencil fa-fw text-center"></i> {$LANG->getGlobal('edit')}</a></li>
                                        {/if}
                                        {if not empty($linkDelete)}
                                        <li><a class="dropdown-item" href="#" data-toggle="nv_del_content" data-id="{$linkDelete.id}" data-checkss="{$linkDelete.checkss}" data-adminurl="{$smarty.const.NV_BASE_ADMINURL}"><i class="fa-solid fa-trash fa-fw text-center text-danger" data-icon="fa-trash"></i> {$LANG->getGlobal('delete')}</a></li>
                                        {/if}
                                    </ul>
                                </span>
                                {/if}
                                {/if}
                                {assign var="newday" value=(($row.newday * 86400) + $row.publtime)}
                                {if $newday gte $smarty.now}
                                <span class="badge text-bg-danger badge-new">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M7.657 6.247c.11-.33.576-.33.686 0l.645 1.937a2.89 2.89 0 0 0 1.829 1.828l1.936.645c.33.11.33.576 0 .686l-1.937.645a2.89 2.89 0 0 0-1.828 1.829l-.645 1.936a.361.361 0 0 1-.686 0l-.645-1.937a2.89 2.89 0 0 0-1.828-1.828l-1.937-.645a.361.361 0 0 1 0-.686l1.937-.645a2.89 2.89 0 0 0 1.828-1.828zM3.794 1.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387A1.73 1.73 0 0 0 4.593 5.69l-.387 1.162a.217.217 0 0 1-.412 0L3.407 5.69A1.73 1.73 0 0 0 2.31 4.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387A1.73 1.73 0 0 0 3.407 2.31zM10.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.16 1.16 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.16 1.16 0 0 0-.732-.732L9.1 2.137a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732z"/>
                                    </svg> {$LANG->getModule('newpost')}
                                </span>
                                {/if}
                            </div>
                        </div>
                    </article>
                    {/if}
                    {assign var="stt" value=($stt+1)}
                    {/foreach}
                </div>
                {/if}
            </div>
        </div>
        {* Quảng cáo phụ bên dưới *}
        {if not empty($datacat.block_bottom)}
        <div class="mt-2 bottom-ads">
            {$datacat.block_bottom}
        </div>
        {/if}
    </div>
    {/if}
    {/foreach}
</div>
