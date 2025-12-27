<div class="page my-4">
    {if not empty($DATA)}
    {foreach from=$DATA item=row}
    <div class="card mb-3 shadow-sm card-hover">
        <div class="card-body">
            <div class="d-flex align-items-start">
                {if not empty($row.image)}
                <div class="flex-shrink-0 me-3">
                    <a href="{$row.link}" title="{$row.title}">
                        <img src="{$row.image}" alt="{$row.imagealt}" class="img-thumbnail page-img-thumbnail">
                    </a>
                </div>
                {/if}
                <div class="flex-grow-1">
                    <h3 class="h5 mb-1">
                        <a href="{$row.link}" title="{$row.title}" class="text-decoration-none hover-primary">
                            {$row.title}
                        </a>
                    </h3>
                    <p class="text-secondary small mb-2">{$row.description}</p>
                    {if $smarty.const.NV_IS_MODADMIN}
                    <div class="text-end border-top pt-2 mt-2">
                        <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=content&amp;id={$row.id}" class="btn btn-sm btn-light text-primary border">
                            <i class="fa fa-edit"></i> {$LANG->getModule('edit')}
                        </a>
                        <a href="#" data-toggle="nv_del_content" data-id="{$row.id}" data-ss="{$row.admin_checkss}" data-adminurl="{$smarty.const.NV_BASE_ADMINURL}" class="btn btn-sm btn-light text-danger border ms-1">
                            <i class="fa fa-trash-o"></i> {$LANG->getModule('delete')}
                        </a>
                    </div>
                    {/if}
                </div>
            </div>
        </div>
    </div>
    {/foreach}
    {/if}
    {if not empty($GENERATE_PAGE)}
    <div class="d-flex justify-content-center mt-4">
        <nav aria-label="Page navigation">
            {$GENERATE_PAGE}
        </nav>
    </div>
    {/if}
</div>
