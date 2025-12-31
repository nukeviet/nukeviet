<div class="page my-4">
    {if not empty($DATA)}
    {foreach from=$DATA item=row}
    <div class="py-3 border-bottom">
        <div class="d-flex align-items-center">
            {if not empty($row.image)}
            <div class="flex-shrink-0 me-3">
                <a href="{$row.link}" title="{$row.title}">
                    <img src="{$row.image}" alt="{$row.imagealt}" class="img-thumbnail page-img-thumbnail">
                </a>
            </div>
            {/if}
            <div class="mb-1">
                <h2 class="fs-5 fw-medium mb-0">
                    <a href="{$row.link}" class="text-primary fw-semibold">{$row.title}</a>
                    {if $smarty.const.NV_IS_MODADMIN}
                    <span class="dropdown">
                        <a class="link-secondary" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-screwdriver-wrench fa-xs"></i></i> <span class="visually-hidden">{$LANG->getModule('admtools')}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=content&amp;id={$row.id}"><i class="fa-solid fa-pencil fa-fw text-center"></i> {$LANG->getGlobal('edit')}</a></li>
                            <li><a class="dropdown-item" href="#" data-toggle="nv_del_content" data-id="{$row.id}" data-ss="{$row.admin_checkss}" data-adminurl="{$smarty.const.NV_BASE_ADMINURL}"><i class="fa-solid fa-trash fa-fw text-center text-danger" data-icon="fa-trash"></i> {$LANG->getGlobal('delete')}</a></li>
                        </ul>
                    </span>
                    {/if}
                </h2>
                <p class="mb-0">{$row.description}</p>
            </div>
        </div>
    </div>
    {/foreach}
    {/if}
    {if not empty($GENERATE_PAGE)}
    <div class="d-flex justify-content-center mt-4">{$GENERATE_PAGE}</div>
    {/if}
</div>
