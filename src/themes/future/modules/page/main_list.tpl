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
            <div class="flex-grow-1">
                <h3 class="mb-1">
                    <a href="{$row.link}" class="text-decoration-none text-primary fw-semibold">{$row.title}</a>
                </h3>
                <p class="text-secondary small mb-0">{$row.description}</p>

                {if $smarty.const.NV_IS_MODADMIN}
                <div class="mt-2 d-flex gap-2">
                    <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=content&amp;id={$row.id}" class="btn btn-primary btn-xs btn_edit">
                        <i class="fa fa-edit me-1"></i>
                    </a>
                    <a href="#" data-toggle="nv_del_content" data-id="{$row.id}" data-ss="{$row.admin_checkss}" data-adminurl="{$smarty.const.NV_BASE_ADMINURL}" class="btn btn-danger btn-xs">
                        <i class="fa fa-trash me-1"></i>
                    </a>
                </div>
                {/if}
            </div>
        </div>
    </div>
    {/foreach}
    {/if}
    {if not empty($GENERATE_PAGE)}
    <div class="d-flex justify-content-center mt-4">{$GENERATE_PAGE}</div>
    {/if}
</div>
