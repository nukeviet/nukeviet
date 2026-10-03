{* Mô tả chuyên mục và bài đầu tiên *}
{$HTML_POSTS}
{* Các bài còn lại dạng lưới *}
{if not empty($ARRAY_ARTICLES)}
<div class="mt-4">
    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-3">
        {foreach from=$ARRAY_ARTICLES item=row}
        <div class="col">
            <article class="card h-100">
                {if not empty($row.imghome)}
                <a href="{$row.link}"{if not empty($row.external_link)} target="_blank"{/if} class="ratio card-img-top overflow-hidden" style="--bs-aspect-ratio: {$IMGRATIO}%;">
                    <img src="{$row.imghome}" alt="{$row.homeimgalt ?: $row.title}" class="object-fit-cover">
                </a>
                {/if}
                <div class="card-body">
                    <h2 class="fs-6 fw-medium d-inline mb-0">
                        <a class="link-body-emphasis" href="{$row.link}"{if not empty($row.external_link)} target="_blank"{/if}
                            {if not empty($MCONFIG.showtooltip)}
                            data-toggle="tooltipArticle" data-hometext="{$row.hometext_clean}" data-alt="{$row.homeimgalt ?: $row.title}" data-img="{$row.imghome}"
                            data-bs-toggle="tooltip" data-bs-placement="{$MCONFIG.tooltip_position}"
                            {/if}
                        >{$row.title}</a>
                    </h2>
                    {if $smarty.const.NV_IS_MODADMIN}
                    {assign var="linkEdit" value=$row|editAllowed:true}
                    {assign var="linkDelete" value=$row|deleteAllowed:0:true}
                    {if not empty($linkEdit) or not empty($linkDelete)}
                    <span class="dropdown">
                        <a class="link-secondary" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-screwdriver-wrench"></i> <span class="visually-hidden">{$LANG->getGlobal('admtools')}</span>
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
                    {if (($row.newday * 86400) + $row.publtime) gte $smarty.now}
                    <span class="badge text-bg-danger badge-new">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M7.657 6.247c.11-.33.576-.33.686 0l.645 1.937a2.89 2.89 0 0 0 1.829 1.828l1.936.645c.33.11.33.576 0 .686l-1.937.645a2.89 2.89 0 0 0-1.828 1.829l-.645 1.936a.361.361 0 0 1-.686 0l-.645-1.937a2.89 2.89 0 0 0-1.828-1.828l-1.937-.645a.361.361 0 0 1 0-.686l1.937-.645a2.89 2.89 0 0 0 1.828-1.828zM3.794 1.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387A1.73 1.73 0 0 0 4.593 5.69l-.387 1.162a.217.217 0 0 1-.412 0L3.407 5.69A1.73 1.73 0 0 0 2.31 4.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387A1.73 1.73 0 0 0 3.407 2.31zM10.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.16 1.16 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.16 1.16 0 0 0-.732-.732L9.1 2.137a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732z"/>
                        </svg> {$LANG->getModule('newpost')}
                    </span>
                    {/if}
                </div>
            </article>
        </div>
        {/foreach}
    </div>
</div>
{/if}
{if not empty($GENERATE_PAGE)}
<div class="d-flex justify-content-center mt-4">
    {$GENERATE_PAGE}
</div>
{/if}
