<div class="page vstack gap-3">
    {if not empty($DATA)}
    {foreach from=$DATA item=row}
    <div class="pb-3 border-bottom">
        <article class="row g-3 align-items-start">
            {if not empty($row.image)}
            <div class="col-4 col-md-3">
                <a href="{$row.link}" class="ratio d-block page-img-wrap align-baseline-xs">
                    <img class="object-fit-cover" src="{$row.image}" alt="{$row.imagealt}" loading="lazy">
                </a>
            </div>
            {/if}
            <div class="{if not empty($row.image)}col-8 col-md-9{else}col-12{/if}">
                <h2 class="fs-5 fw-medium mb-0">
                    <a href="{$row.link}" class="text-primary fw-semibold">{$row.title}</a>
                    {if $smarty.const.NV_IS_MODADMIN}
                    <span class="dropdown">
                        <a class="link-secondary" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-screwdriver-wrench fa-xs"></i></i> <span class="visually-hidden">{$LANG->getModule('admtools')}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{$row.admin_edit}"><i class="fa-solid fa-pencil fa-fw text-center"></i> {$LANG->getGlobal('edit')}</a></li>
                            <li><a class="dropdown-item" href="#" data-toggle="nv_del_content" data-id="{$row.id}" data-checkss="{$row.admin_checkss}" data-adminurl="{$smarty.const.NV_BASE_ADMINURL}"><i class="fa-solid fa-trash fa-fw text-center text-danger" data-icon="fa-trash"></i> {$LANG->getGlobal('delete')}</a></li>
                        </ul>
                    </span>
                    {/if}
                </h2>
                <p class="mb-0 text-truncate-4">{$row.description}</p>
            </div>
        </article>
    </div>
    {/foreach}
    {/if}
    {if not empty($GENERATE_PAGE)}
    <div class="d-flex justify-content-center mt-4">{$GENERATE_PAGE}</div>
    {/if}
</div>
