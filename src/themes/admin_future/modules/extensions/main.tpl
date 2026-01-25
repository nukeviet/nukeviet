<div class="card">
    <div class="card-header">
        <form method="get" action="{$smarty.const.NV_BASE_ADMINURL}index.php">
            <input type="hidden" name="{$smarty.const.NV_LANG_VARIABLE}" value="{$smarty.const.NV_LANG_DATA}">
            <input type="hidden" name="{$smarty.const.NV_NAME_VARIABLE}" value="{$MODULE_NAME}">
            <input type="hidden" name="mode" value="search">
            <div class="input-group">
                <input type="text" name="q" value="{$REQUEST.q}" class="form-control flex-grow-0 w-auto" placeholder="{$LANG->getModule('search_key')}" aria-label="{$LANG->getModule('search_key')}" aria-describedby="element_search_btn">
                <button class="btn btn-primary" type="submit" id="element_search_btn"><i class="fa-solid fa-magnifying-glass"></i> {$LANG->getGlobal('search')}</button>
            </div>
        </form>
    </div>
    <div class="card-body">
        <div class="table-responsive-lg table-card">
            <table class="table table-striped align-middle mb-0">
                <colgroup>
                    <col style="width: 60%;">
                    <col style="width: 20%;">
                    <col style="width: 20%;">
                </colgroup>
                <tbody>
                    {foreach from=$ARRAY item=row}
                    <tr>
                        <td>
                            <div class="d-flex">
                                <div class="ext-thumbnail">
                                    <div class="rounded bg-body-tertiary">
                                        <img alt="{$row.title}" src="{if empty($row.image_small)}{$smarty.const.ASSETS_STATIC_URL}/images/no-photo.svg{else}{$row.image_small}{/if}">
                                    </div>
                                </div>
                                <div class="ext-titles ps-2">
                                    <h5>{$row.title}</h5>
                                    <div class="text-truncate-2 mb-1">{$row.introtext}</div>
                                    <div class="text-{empty($row.compatible) ? 'danger' : 'success'}">
                                        {$LANG->getModule(empty($row.compatible) ? 'incompatible' : 'compatible')}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <ul class="list-unstyled mb-0">
                                <li>{$LANG->getModule('author')} : <span class="text-primary">{$row.username}</span></li>
                                <li>{$LANG->getModule('ext_type')} : <span class="text-primary">{$LANG->getModule("types_`$row.tid`")}</span></li>
                            </ul>
                        </td>
                        <td class="text-center">
                            <div class="text-nowrap">
                                {for $star=1 to 5}<i class="fa-solid fa-star text-{$row.rating_avg|ceil ge $star ? 'warning' : 'muted'}"></i>{/for}
                            </div>
                            <div class="hstack gap-2 mt-2 d-inline-flex">
                                <button type="button" class="btn btn-secondary btn-sm text-nowrap ex-detail" data-title="{$LANG->getModule('detail_title', $row.title)}" data-url="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=detail&amp;id={$row.id}"><i class="fa-solid fa-expand"></i> {$LANG->getModule('detail')}</button>
                                {if not empty($row.compatible) and ($GCONFIG.extension_setup eq 2 or $GCONFIG.extension_setup eq 3)}
                                <a class="btn btn-primary btn-sm text-nowrap" href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=install&amp;id={$row.id}"><i class="fa-solid fa-cloud-arrow-down"></i> {$LANG->getModule('install')}</a>
                                {/if}
                            </div>
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
    {if not empty($PAGINATION)}
    <div class="card-footer border-top">
        <div class="d-flex">
            <div class="ms-auto pagination-wrap">
                {$PAGINATION}
            </div>
        </div>
    </div>
    {/if}
</div>
<div class="modal fade" id="mdExtDetail" tabindex="-1" aria-labelledby="mdExtDetailLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title fs-5 fw-medium" id="mdExtDetailLabel">{$LANG->getModule('file_name')}</div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>
